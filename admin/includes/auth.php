<?php
require_once __DIR__ . '/../../includes/bootstrap.php';

function require_admin(): void
{
    if (empty($_SESSION['admin_id'])) {
        redirect('login.php');
    }
}
