<?php
require __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php#book');
}

if (!csrf_check()) {
    set_flash('error', 'Session expired, please try again.');
    redirect('index.php#book');
}

$name    = trim($_POST['name'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$email   = trim($_POST['email'] ?? '');
$carModel = trim($_POST['car_model'] ?? '');
$packageId = (int) ($_POST['package_id'] ?? 0);
$date    = trim($_POST['preferred_date'] ?? '');
$time    = trim($_POST['preferred_time'] ?? '');
$notes   = trim($_POST['notes'] ?? '');

$errors = [];

if ($name === '' || mb_strlen($name) > 120) {
    $errors[] = 'Please enter a valid name.';
}
if (!preg_match('/^[6-9]\d{9}$/', $phone)) {
    $errors[] = 'Please enter a valid 10-digit phone number.';
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if (!$packageId) {
    $errors[] = 'Please select a package.';
}
$today = date('Y-m-d');
if (!$date || $date < $today) {
    $errors[] = 'Please choose a valid date (today or later).';
}
$allowedSlots = ['09:00 AM', '11:00 AM', '01:00 PM', '03:00 PM', '05:00 PM'];
if (!in_array($time, $allowedSlots, true)) {
    $errors[] = 'Please choose a valid time slot.';
}

if (!$errors) {
    $pkgStmt = $pdo->prepare('SELECT id FROM packages WHERE id = :id');
    $pkgStmt->execute(['id' => $packageId]);
    if (!$pkgStmt->fetch()) {
        $errors[] = 'Selected package no longer exists.';
    }
}

if ($errors) {
    set_flash('error', implode(' ', $errors));
    redirect('index.php#book');
}

$custStmt = $pdo->prepare('SELECT id, is_blocked FROM customers WHERE phone = :phone');
$custStmt->execute(['phone' => $phone]);
$customer = $custStmt->fetch();

if ($customer && (int) $customer['is_blocked'] === 1) {
    set_flash('error', 'This phone number is not able to book right now. Please call us directly.');
    redirect('index.php#book');
}

if ($customer) {
    $customerId = $customer['id'];
    $upd = $pdo->prepare('UPDATE customers SET name = :name, email = :email WHERE id = :id');
    $upd->execute(['name' => $name, 'email' => $email ?: null, 'id' => $customerId]);
} else {
    $ins = $pdo->prepare('INSERT INTO customers (name, phone, email) VALUES (:name, :phone, :email)');
    $ins->execute(['name' => $name, 'phone' => $phone, 'email' => $email ?: null]);
    $customerId = (int) $pdo->lastInsertId();
}

$bookStmt = $pdo->prepare(
    'INSERT INTO bookings (customer_id, package_id, customer_name, phone, email, car_model, preferred_date, preferred_time, notes)
     VALUES (:customer_id, :package_id, :customer_name, :phone, :email, :car_model, :preferred_date, :preferred_time, :notes)'
);
$bookStmt->execute([
    'customer_id' => $customerId,
    'package_id' => $packageId,
    'customer_name' => $name,
    'phone' => $phone,
    'email' => $email ?: null,
    'car_model' => $carModel,
    'preferred_date' => $date,
    'preferred_time' => $time,
    'notes' => $notes,
]);

set_flash('success', 'Booking received! We will confirm shortly on your phone/email. You can check status anytime on the "Check Booking" page.');
redirect('index.php#book');
