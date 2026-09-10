<?php
require __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    set_flash('error', 'Please submit the enquiry form again.');
    redirect('index.php');
}
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');
$errors = [];
if ($name === '' || mb_strlen($name) > 120) $errors[] = 'Please enter a valid name.';
if (!preg_match('/^[6-9]\d{9}$/', $phone)) $errors[] = 'Please enter a valid 10-digit phone number.';
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
if (!$errors) {
    $stmt = $pdo->prepare('INSERT INTO enquiries (name, phone, email, service, message) VALUES (:name,:phone,:email,:service,:message)');
    $stmt->execute(['name'=>$name,'phone'=>$phone,'email'=>$email ?: null,'service'=>$service ?: null,'message'=>$message ?: null]);
    set_flash('success', 'Thanks! Your enquiry has been received. Our team will contact you shortly.');
} else {
    set_flash('error', implode(' ', $errors));
}
redirect('index.php');
