<?php
require_once __DIR__ . '/includes/auth.php';
if (isLoggedIn()) {
    header('Location: ' . SITE_URL . '/pages/dashboard.php');
} else {
    header('Location: ' . SITE_URL . '/pages/login.php');
}
exit;
