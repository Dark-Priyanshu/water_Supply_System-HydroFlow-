<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lang'])) {
    $allowed_langs = ['en', 'hi'];
    $lang = $_POST['lang'];
    
    if (in_array($lang, $allowed_langs)) {
        $_SESSION['lang'] = $lang;
        echo json_encode(['status' => 'success', 'lang' => $lang]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid language']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
