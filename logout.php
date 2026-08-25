<?php
/**
 * Fee Payment Record System - Logout Page
 * STEP 16: Logout Process
 */

require_once 'config/config.php';
require_once 'config/session.php';

// Destroy session
session_destroy();

// STEP 16: Redirect to Login Page
header('Location: login.php?logout=1');
exit();

?>
