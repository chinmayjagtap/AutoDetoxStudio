<?php
/** @var string $pageTitle */
$flash = get_flash();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= h($pageTitle ?? 'Admin') ?> — Auto Detox Studio</title>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="assets/admin.css" />
  <link rel="icon" type="image/svg+xml" href="../assets/favicon.svg" />
</head>
<body class="admin-body">
  <div class="admin-shell">
    <aside class="admin-sidebar">
      <div class="admin-brand">AUTO <em>DETOX</em><br /><small>Admin Panel</small></div>
      <nav class="admin-nav">
        <a href="dashboard.php" class="<?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
        <a href="bookings.php" class="<?= ($active ?? '') === 'bookings' ? 'active' : '' ?>">Bookings</a>
        <a href="customers.php" class="<?= ($active ?? '') === 'customers' ? 'active' : '' ?>">Customers</a>
        <a href="packages.php" class="<?= ($active ?? '') === 'packages' ? 'active' : '' ?>">Packages</a>
        <a href="gallery.php" class="<?= ($active ?? '') === 'gallery' ? 'active' : '' ?>">Gallery & Videos</a>
        <a href="enquiries.php" class="<?= ($active ?? '') === 'enquiries' ? 'active' : '' ?>">Enquiries</a>
      </nav>
      <form action="logout.php" method="post" class="admin-logout-form">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>" />
        <button type="submit" class="admin-logout-btn">Logout (<?= h($_SESSION['admin_username'] ?? '') ?>)</button>
      </form>
    </aside>
    <main class="admin-main">
      <h1 class="admin-title"><?= h($pageTitle ?? '') ?></h1>
      <?php if ($flash): ?>
        <div class="admin-alert admin-alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
      <?php endif; ?>
