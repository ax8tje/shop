<?php
require_once '../includes/auth.php';
if (!isAdmin()) {
    header('Location: ../public/login.php');
    exit;
}