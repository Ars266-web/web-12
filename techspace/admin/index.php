<?php
/**
 * TechSpace Admin Panel - Entry Point
 * Redirects to login page if not authenticated
 */

// Start session
session_start();

// Check if admin is logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // Redirect to dashboard
    header('Location: dashboard.php');
    exit;
} else {
    // Redirect to login page
    header('Location: login.php');
    exit;
}
