<?php
require __DIR__ . '/includes/auth.php';
require_admin();

$pageTitle = 'Dashboard';
$active = 'dashboard';

$totalBookings = (int) $pdo->query('SELECT COUNT(*) FROM bookings')->fetchColumn();
$pendingBookings = (int) $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();
$totalCustomers = (int) $pdo->query('SELECT COUNT(*) FROM customers')->fetchColumn();
$totalPackages = (int) $pdo->query('SELECT COUNT(*) FROM packages')->fetchColumn();

$recent = $pdo->query(
    'SELECT b.id, b.customer_name, b.phone, b.preferred_date, b.preferred_time, b.status, p.name AS package_name
     FROM bookings b LEFT JOIN packages p ON p.id = b.package_id
     ORDER BY b.created_at DESC LIMIT 8'
)->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="admin-stats">
  <div class="admin-stat"><div class="num"><?= $totalBookings ?></div><div class="label">Total Bookings</div></div>
  <div class="admin-stat"><div class="num"><?= $pendingBookings ?></div><div class="label">Pending Bookings</div></div>
  <div class="admin-stat"><div class="num"><?= $totalCustomers ?></div><div class="label">Customers</div></div>
  <div class="admin-stat"><div class="num"><?= $totalPackages ?></div><div class="label">Packages</div></div>
</div>

<div class="admin-card">
  <h3 style="margin-bottom:14px;">Recent Bookings</h3>
  <table class="admin-table">
    <thead><tr><th>Customer</th><th>Phone</th><th>Package</th><th>Date</th><th>Time</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach ($recent as $b): ?>
        <tr>
          <td><?= h($b['customer_name']) ?></td>
          <td><?= h($b['phone']) ?></td>
          <td><?= h($b['package_name'] ?? '—') ?></td>
          <td><?= h(date('d M Y', strtotime($b['preferred_date']))) ?></td>
          <td><?= h($b['preferred_time']) ?></td>
          <td><?= h(ucfirst($b['status'])) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$recent): ?>
        <tr><td colspan="6">No bookings yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
