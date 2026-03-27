        </div> <!-- End Page Content Wrapper -->
    </main> <!-- End Main Content Wrapper -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom Script -->
    <script>
        // Mobile Sidebar Toggle Logic
        window.toggleSidebar = function() {
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('mobileOverlay');
            
            sidebar.classList.toggle('sidebar-open');
            
            if (overlay.style.display === 'none' || overlay.style.display === '') {
                overlay.style.display = 'block';
                setTimeout(() => overlay.style.opacity = '1', 10);
            } else {
                overlay.style.opacity = '0';
                setTimeout(() => overlay.style.display = 'none', 300);
            }
        };

        $(document).ready(function() {
            // Initialize datatables
            if ($('.datatable').length) {
                $('.datatable').DataTable({
                    "pageLength": 10,
                    "ordering": true,
                    "info": true,
                    "dom": "<'dt-container-row'lf>" +
                           "<'table-container'tr>" +
                           "<'dt-container-row'ip>",
                    "language": {
                        "search": "_INPUT_",
                        "searchPlaceholder": "Search records...",
                        "emptyTable": `<div class="dt-empty-state">
                            <span class="material-symbols-outlined dt-empty-icon">folder_off</span>
                            <p style="font-size: 0.9375rem; font-weight: 600; color: var(--color-on-surface-variant);">No records available.</p>
                            <p style="font-size: 0.75rem; color: var(--color-outline); margin-top: 0.25rem;">Try adjusting your filters or adding new data.</p>
                        </div>`,
                        "zeroRecords": `<div class="dt-empty-state">
                            <span class="material-symbols-outlined dt-empty-icon">search_off</span>
                            <p style="font-size: 0.9375rem; font-weight: 600; color: var(--color-on-surface-variant);">No matching records found.</p>
                            <p style="font-size: 0.75rem; color: var(--color-outline); margin-top: 0.25rem;">Please try a different search query.</p>
                        </div>`
                    }
                });
            }
            // Bind top global search to DataTables and generic cards
            $('#topGlobalSearch').on('keyup', function() {
                var searchVal = $(this).val().toLowerCase();
                
                // 1. Filter DataTables
                if ($('.datatable').length) {
                    $('.datatable').DataTable().search(searchVal).draw();
                }
                
                // 2. Filter Custom Grids / Cards
                if ($('.searchable-item').length) {
                    $('.searchable-item').each(function() {
                        var textContent = $(this).text().toLowerCase();
                        if (textContent.indexOf(searchVal) > -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
