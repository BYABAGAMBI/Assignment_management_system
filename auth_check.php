<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Store the requested page for redirect after login
    $current_page = basename($_SERVER['PHP_SELF']);
    $query_string = $_SERVER['QUERY_STRING'] ?? '';
    $redirect_url = $query_string ? $current_page . '?' . $query_string : $current_page;
    
    header('Location: login.php?redirect=' . urlencode($redirect_url));
    exit;
}

// Function to logout
function logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Handle logout request
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    logout();
}
?>
