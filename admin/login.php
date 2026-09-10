<?php
require __DIR__ . '/../includes/bootstrap.php';

if (!empty($_SESSION['admin_id'])) {
    redirect('dashboard.php');
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $error = 'Session expired, please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username = :u');
        $stmt->execute(['u' => $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            redirect('dashboard.php');
        }
        $error = 'Invalid username or password.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Login — Auto Detox Studio</title>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="assets/admin.css" />
</head>
<body>
  <div class="admin-login-wrap">
    <div class="admin-login-card">
      <h1>Admin Login</h1>
      <?php if ($error): ?>
        <div class="admin-alert admin-alert-error"><?= h($error) ?></div>
      <?php endif; ?>
      <form method="post" action="login.php">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>" />
        <div class="admin-form-row">
          <label>Username</label>
          <input type="text" name="username" required autofocus />
        </div>
        <div class="admin-form-row">
          <label>Password</label>
          <input type="password" name="password" required />
        </div>
        <button type="submit" class="admin-btn admin-btn-primary" style="width:100%">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
