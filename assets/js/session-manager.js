/**
 * HydroFlow — Session Manager
 * Handles:
 *   1. Auto logout countdown with warning modal
 *   2. Single-tab enforcement (blocks duplicate tabs)
 *   3. Re-authentication lock screen modal
 *   4. Keep-alive pings to extend session on activity
 *
 * Requires: BASE_URL and SESSION_TIMEOUT_SECONDS to be set globally before load.
 */
(function () {
    'use strict';

    /* ── Config (injected from PHP via header.php) ── */
    const BASE     = window.HYDRO_BASE_URL     || '/waterS/';
    const TIMEOUT  = window.HYDRO_SESSION_SECS || 1800;   // seconds
    const WARN_AT  = 120;                                  // show warning 2 min before
    const TAB_KEY  = 'hydroflow_active_tab';
    const CTRL_URL = BASE + 'controllers/sessionController.php';

    /* ── Unique ID for this tab ── */
    const TAB_ID = Math.random().toString(36).slice(2);

    /* =====================================================================
     *  1. SINGLE-TAB ENFORCEMENT
     * ================================================================== */
    function initTabGuard() {
        // Claim this tab
        localStorage.setItem(TAB_KEY, TAB_ID);

        // Listen for other tabs claiming ownership
        window.addEventListener('storage', function (e) {
            if (e.key === TAB_KEY && e.newValue !== TAB_ID) {
                // Another tab has taken over — show the "session moved" screen
                showTabDuplicateOverlay();
            }
        });

        // On page focus: re-claim if we lost it
        window.addEventListener('focus', function () {
            const current = localStorage.getItem(TAB_KEY);
            if (current && current !== TAB_ID) {
                showTabDuplicateOverlay();
            }
        });

        // Claim ownership again when this tab is focused
        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) {
                // Re-check; if someone else owns it, show overlay
                const current = localStorage.getItem(TAB_KEY);
                if (current && current !== TAB_ID) {
                    showTabDuplicateOverlay();
                }
            }
        });
    }

    function showTabDuplicateOverlay() {
        // Avoid duplicating
        if (document.getElementById('hydro-tab-overlay')) return;

        const overlay = document.createElement('div');
        overlay.id = 'hydro-tab-overlay';
        overlay.innerHTML = `
            <div class="hydro-modal-backdrop" style="
                position:fixed;inset:0;z-index:99999;
                background:rgba(0,0,0,0.85);backdrop-filter:blur(12px);
                display:flex;align-items:center;justify-content:center;
                animation:hydroFadeIn 0.3s ease;
            ">
                <div style="
                    background:var(--color-surface,#fff);
                    border-radius:1.5rem;padding:2.5rem;max-width:26rem;width:90%;
                    box-shadow:0 32px 64px rgba(0,0,0,0.3);text-align:center;
                    border:1px solid var(--color-outline-variant,#bfc7d1);
                    animation:hydroSlideUp 0.3s ease;
                ">
                    <div style="
                        width:4rem;height:4rem;border-radius:50%;margin:0 auto 1.25rem;
                        background:var(--color-error-container,#ffdad6);
                        display:flex;align-items:center;justify-content:center;
                    ">
                        <span class="material-symbols-outlined" style="color:var(--color-error,#ba1a1a);font-size:2rem;">tab_close</span>
                    </div>
                    <h3 style="font-size:1.25rem;font-weight:800;margin-bottom:0.5rem;color:var(--color-on-surface,#191c1e);">
                        Session Moved to Another Tab
                    </h3>
                    <p style="font-size:0.875rem;color:var(--color-on-surface-variant,#404850);line-height:1.6;margin-bottom:1.5rem;">
                        HydroFlow is active in another browser tab. For security, only one tab is allowed at a time.
                    </p>
                    <button id="hydro-reclaim-tab" style="
                        width:100%;padding:0.85rem;border:none;border-radius:0.875rem;cursor:pointer;
                        background:linear-gradient(135deg,var(--color-primary,#005d90),#0077b6);
                        color:#fff;font-weight:700;font-size:0.9rem;
                        box-shadow:0 8px 24px rgba(0,93,144,0.25);
                    ">
                        <span class="material-symbols-outlined" style="font-size:1rem;vertical-align:middle;margin-right:0.25rem;">open_in_browser</span>
                        Use This Tab
                    </button>
                    <button id="hydro-close-tab" style="
                        width:100%;padding:0.85rem;border:1px solid var(--color-outline-variant,#bfc7d1);
                        border-radius:0.875rem;cursor:pointer;margin-top:0.75rem;
                        background:var(--color-surface-container,#eceef0);
                        color:var(--color-on-surface,#191c1e);font-weight:600;font-size:0.9rem;
                    ">
                        Go to Dashboard
                    </button>
                </div>
            </div>`;

        document.body.appendChild(overlay);

        document.getElementById('hydro-reclaim-tab').addEventListener('click', function () {
            localStorage.setItem(TAB_KEY, TAB_ID);
            overlay.remove();
        });

        document.getElementById('hydro-close-tab').addEventListener('click', function () {
            window.location.href = BASE + 'dashboard.php';
        });
    }

    /* =====================================================================
     *  2. SESSION TIMEOUT COUNTDOWN + WARNING MODAL
     * ================================================================== */
    let secondsLeft = TIMEOUT;
    let countdownInterval = null;
    let warningShown = false;
    let logoutScheduled = false;

    function initSessionTimer() {
        countdownInterval = setInterval(tickTimer, 1000);
        // Reset timer on any user activity
        ['mousemove', 'keydown', 'mousedown', 'touchstart', 'scroll'].forEach(function (evt) {
            document.addEventListener(evt, resetTimer, { passive: true });
        });
    }

    function tickTimer() {
        secondsLeft--;
        if (secondsLeft <= 0 && !logoutScheduled) {
            logoutScheduled = true;
            performLogout('timeout');
        } else if (secondsLeft <= WARN_AT && !warningShown) {
            warningShown = true;
            showTimeoutWarning();
        }
        // Update countdown in modal if visible
        const el = document.getElementById('hydro-countdown-secs');
        if (el) el.textContent = formatTime(secondsLeft);
    }

    function resetTimer() {
        if (secondsLeft > WARN_AT) return; // Only ping server when close to timeout
        // Only reset if we haven't shown warning yet
        if (!warningShown) return;
        // User is active even with warning — keep alive
        keepAlive();
    }

    function keepAlive() {
        fetch(CTRL_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=keep_alive'
        }).then(function (r) { return r.json(); }).then(function (data) {
            if (data.success) {
                secondsLeft = TIMEOUT;
                warningShown = false;
                logoutScheduled = false;
                const modal = document.getElementById('hydro-timeout-modal');
                if (modal) modal.remove();
            }
        }).catch(function () { /* network error — let timeout proceed */ });
    }

    function performLogout(reason) {
        clearInterval(countdownInterval);
        window.location.href = BASE + 'logout.php?reason=' + (reason || 'timeout');
    }

    function formatTime(secs) {
        const m = Math.floor(Math.max(0, secs) / 60);
        const s = Math.max(0, secs) % 60;
        return m + ':' + (s < 10 ? '0' : '') + s;
    }

    function showTimeoutWarning() {
        if (document.getElementById('hydro-timeout-modal')) return;

        const modal = document.createElement('div');
        modal.id = 'hydro-timeout-modal';
        modal.innerHTML = `
            <div style="
                position:fixed;inset:0;z-index:99998;
                background:rgba(0,0,0,0.6);backdrop-filter:blur(8px);
                display:flex;align-items:center;justify-content:center;
                animation:hydroFadeIn 0.3s ease;
            ">
                <div style="
                    background:var(--color-surface,#fff);
                    border-radius:1.5rem;padding:2.5rem;max-width:24rem;width:90%;
                    box-shadow:0 32px 64px rgba(0,0,0,0.25);text-align:center;
                    border:1px solid var(--color-outline-variant,#bfc7d1);
                    animation:hydroSlideUp 0.3s ease;
                ">
                    <div style="
                        width:4rem;height:4rem;border-radius:50%;margin:0 auto 1.25rem;
                        background:var(--color-error-container,#ffdad6);
                        display:flex;align-items:center;justify-content:center;
                    ">
                        <span class="material-symbols-outlined" style="color:var(--color-error,#ba1a1a);font-size:2rem;">timer</span>
                    </div>
                    <h3 style="font-size:1.125rem;font-weight:800;margin-bottom:0.5rem;color:var(--color-on-surface,#191c1e);">
                        Session Expiring Soon
                    </h3>
                    <p style="font-size:0.875rem;color:var(--color-on-surface-variant,#404850);line-height:1.6;margin-bottom:1rem;">
                        You will be automatically logged out in
                    </p>
                    <div id="hydro-countdown-secs" style="
                        font-size:2.5rem;font-weight:900;color:var(--color-error,#ba1a1a);
                        font-family:var(--font-headline,'Inter',sans-serif);
                        letter-spacing:-0.05em;margin-bottom:1.5rem;
                    ">${formatTime(secondsLeft)}</div>
                    <button id="hydro-stay-btn" style="
                        width:100%;padding:0.85rem;border:none;border-radius:0.875rem;cursor:pointer;
                        background:linear-gradient(135deg,var(--color-primary,#005d90),#0077b6);
                        color:#fff;font-weight:700;font-size:0.9rem;
                        box-shadow:0 8px 24px rgba(0,93,144,0.25);
                    ">
                        <span class="material-symbols-outlined" style="font-size:1rem;vertical-align:middle;margin-right:0.25rem;">refresh</span>
                        Stay Logged In
                    </button>
                    <button id="hydro-logout-now-btn" style="
                        width:100%;padding:0.85rem;border:1px solid var(--color-outline-variant,#bfc7d1);
                        border-radius:0.875rem;cursor:pointer;margin-top:0.75rem;
                        background:var(--color-surface-container,#eceef0);
                        color:var(--color-on-surface,#191c1e);font-weight:600;font-size:0.9rem;
                    ">
                        Logout Now
                    </button>
                </div>
            </div>`;

        document.body.appendChild(modal);

        document.getElementById('hydro-stay-btn').addEventListener('click', function () {
            keepAlive();
        });
        document.getElementById('hydro-logout-now-btn').addEventListener('click', function () {
            performLogout('manual');
        });
    }

    /* =====================================================================
     *  3. RE-AUTHENTICATION LOCK SCREEN
     * ================================================================== */
    function showReauthModal() {
        if (document.getElementById('hydro-reauth-modal')) return;

        const modal = document.createElement('div');
        modal.id = 'hydro-reauth-modal';
        modal.innerHTML = `
            <div style="
                position:fixed;inset:0;z-index:99999;
                background:rgba(0,0,0,0.9);backdrop-filter:blur(20px);
                display:flex;align-items:center;justify-content:center;
                animation:hydroFadeIn 0.3s ease;
            ">
                <div style="
                    background:var(--color-surface,#fff);
                    border-radius:1.5rem;padding:2.5rem;max-width:24rem;width:90%;
                    box-shadow:0 32px 64px rgba(0,0,0,0.4);text-align:center;
                    border:1px solid var(--color-outline-variant,#bfc7d1);
                    animation:hydroSlideUp 0.3s ease;
                ">
                    <div style="
                        width:4.5rem;height:4.5rem;border-radius:50%;margin:0 auto 1.25rem;
                        background:var(--color-primary-fixed,#cce6f4);
                        display:flex;align-items:center;justify-content:center;
                    ">
                        <span class="material-symbols-outlined" style="color:var(--color-primary,#005d90);font-size:2.25rem;">lock_person</span>
                    </div>
                    <h3 style="font-size:1.25rem;font-weight:800;margin-bottom:0.5rem;color:var(--color-on-surface,#191c1e);">
                        Confirm Your Identity
                    </h3>
                    <p style="font-size:0.875rem;color:var(--color-on-surface-variant,#404850);line-height:1.6;margin-bottom:1.5rem;">
                        For security, please re-enter your password to continue.
                    </p>
                    <div id="hydro-reauth-error" style="
                        display:none;
                        background:var(--color-error-container,#ffdad6);
                        color:var(--color-error,#ba1a1a);
                        padding:0.75rem;border-radius:0.75rem;
                        font-size:0.8rem;font-weight:600;margin-bottom:1rem;
                    ">
                        <span class="material-symbols-outlined" style="font-size:1rem;vertical-align:middle;margin-right:0.25rem;">error</span>
                        Incorrect password. Please try again.
                    </div>
                    <div style="position:relative;margin-bottom:1rem;">
                        <span class="material-symbols-outlined" style="
                            position:absolute;left:0.875rem;top:50%;transform:translateY(-50%);
                            color:var(--color-on-surface-variant,#404850);font-size:1.25rem;
                        ">lock</span>
                        <input
                            id="hydro-reauth-input"
                            type="password"
                            placeholder="Enter your password"
                            style="
                                width:100%;padding:0.875rem 1rem 0.875rem 3rem;
                                border:1.5px solid var(--color-outline-variant,#bfc7d1);
                                border-radius:0.875rem;font-size:0.9rem;
                                background:var(--color-surface-container-low,#f2f4f6);
                                color:var(--color-on-surface,#191c1e);outline:none;
                                transition:border-color 0.2s;
                            "
                            autocomplete="current-password"
                        />
                    </div>
                    <button id="hydro-reauth-submit" style="
                        width:100%;padding:0.85rem;border:none;border-radius:0.875rem;cursor:pointer;
                        background:linear-gradient(135deg,var(--color-primary,#005d90),#0077b6);
                        color:#fff;font-weight:700;font-size:0.9rem;
                        box-shadow:0 8px 24px rgba(0,93,144,0.25);
                        display:flex;align-items:center;justify-content:center;gap:0.5rem;
                    ">
                        <span class="material-symbols-outlined" style="font-size:1.1rem;">verified_user</span>
                        Verify & Continue
                    </button>
                    <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--color-outline-variant,#bfc7d1);">
                        <a href="${BASE}logout.php" style="
                            font-size:0.8rem;color:var(--color-error,#ba1a1a);font-weight:600;text-decoration:none;
                        ">Not you? Logout</a>
                    </div>
                </div>
            </div>`;

        document.body.appendChild(modal);

        const input = document.getElementById('hydro-reauth-input');
        const errBox = document.getElementById('hydro-reauth-error');
        const btn = document.getElementById('hydro-reauth-submit');

        input.focus();

        // Submit on Enter
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') btn.click();
        });

        // Focus border highlight
        input.addEventListener('focus', function () {
            this.style.borderColor = 'var(--color-primary,#005d90)';
        });
        input.addEventListener('blur', function () {
            this.style.borderColor = 'var(--color-outline-variant,#bfc7d1)';
        });

        btn.addEventListener('click', function () {
            const pwd = input.value;
            if (!pwd) { input.focus(); return; }

            btn.disabled = true;
            btn.innerHTML = `<span class="material-symbols-outlined" style="font-size:1.1rem;animation:hydroSpin 1s linear infinite">progress_activity</span> Verifying...`;

            fetch(CTRL_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=verify_reauth&password=' + encodeURIComponent(pwd)
            }).then(function (r) { return r.json(); }).then(function (data) {
                if (data.success) {
                    modal.remove();
                    secondsLeft = TIMEOUT;
                    warningShown = false;
                    logoutScheduled = false;
                } else {
                    errBox.style.display = 'block';
                    input.value = '';
                    input.focus();
                    btn.disabled = false;
                    btn.innerHTML = `<span class="material-symbols-outlined" style="font-size:1.1rem;">verified_user</span> Verify & Continue`;
                }
            }).catch(function () {
                btn.disabled = false;
                btn.innerHTML = `<span class="material-symbols-outlined" style="font-size:1.1rem;">verified_user</span> Verify & Continue`;
            });
        });
    }

    /* =====================================================================
     *  4. GLOBAL ANIMATIONS (injected once)
     * ================================================================== */
    function injectStyles() {
        if (document.getElementById('hydro-session-styles')) return;
        const style = document.createElement('style');
        style.id = 'hydro-session-styles';
        style.textContent = `
            @keyframes hydroFadeIn  { from{opacity:0}   to{opacity:1} }
            @keyframes hydroSlideUp { from{transform:translateY(24px);opacity:0} to{transform:translateY(0);opacity:1} }
            @keyframes hydroSpin    { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
        `;
        document.head.appendChild(style);
    }

    /* =====================================================================
     *  BOOT
     * ================================================================== */
    document.addEventListener('DOMContentLoaded', function () {
        injectStyles();
        initTabGuard();
        initSessionTimer();

        // If PHP flagged reauth required, show immediately
        if (window.HYDRO_REAUTH_REQUIRED) {
            showReauthModal();
        }
    });

    // Expose for settings page to call after saving timeout
    window.HydroSession = {
        keepAlive: keepAlive,
        showReauth: showReauthModal,
    };

})();
