<?php
require_once '../includes/auth.php';
if (!isAdmin()) {
    header('Location: /login.php');
    exit;
}