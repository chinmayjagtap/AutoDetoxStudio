<?php
require __DIR__ . '/includes/auth.php';
require_admin();

$pageTitle = 'Bookings';
$active = 'bookings';

$validStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        set_flash('error', 'Session expired, please try again.');
        redirect('bookings.php');
    }

    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'update_status') {
        $status = $_POST['status'] ?? '';
        if ($id && in_array($status, $validStatuses, true)) {
            $stmt = $pdo->prepare('UPDATE bookings SET status = :status WHERE id = :id');
            $stmt->execute(['status' => $status, 'id' => $id]);
            set_flash('success', 'Booking status updated.');
        }
    } elseif ($action === 'delete') {
        if ($id) {
            $stmt = $pdo->prepare('DELETE FROM bookings WHERE id = :id');
            $stmt->execute(['id' => $id]);
            set_flash('success', 'Booking deleted.');
        }
    }
    redirect('bookings.php' . (isset($_GET['status']) ? '?status=' . urlencode($_GET['status']) : ''));
}

$filter = $_GET['status'] ?? '';
$sql = 'SELECT b.*, p.name AS package_name FROM bookings b LEFT JOIN packages p ON p.id = b.package_id';
$params = [];
if (in_array($filter, $validStatuses, true)) {
    $sql .= ' WHERE b.status = :status';
    $params['status'] = $filter;
}
$sql .= ' ORDER BY b.created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="admin-card">
  <div style="display:flex; gap:8px; margin-bottom:16px;">
    <a class="admin-btn <?= $filter === '' ? 'admin-btn-primary' : '' ?> admin-btn-sm" href="bookings.php">All</a>
    <?php foreach ($validStatuses as $s): ?>
      <a class="admin-btn <?= $filter === $s ? 'admin-btn-primary' : '' ?> admin-btn-sm" href="bookings.php?status=<?= h($s) ?>"><?= h(ucfirst($s)) ?></a>
    <?php endforeach; ?>
  </div>

  <table class="admin-table">
    <thead>
      <tr><th>Customer</th><th>Phone</th><th>Package</th><th>Car</th><th>Date</th><th>Time</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($bookings as $b): ?>
        <tr>
          <td><?= h($b['customer_name']) ?></td>
          <td><?= h($b['phone']) ?></td>
          <td><?= h($b['package_name'] ?? '—') ?></td>
          <td><?= h($b['car_model']) ?></td>
          <td><?= h(date('d M Y', strtotime($b['preferred_date']))) ?></td>
          <td><?= h($b['preferred_time']) ?></td>
          <td>
            <form class="admin-inline-form" method="post" action="bookings.php<?= $filter ? '?status=' . h($filter) : '' ?>">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>" />
              <input type="hidden" name="action" value="update_status" />
              <input type="hidden" name="id" value="<?= (int) $b['id'] ?>" />
              <select name="status" onchange="this.form.submit()">
                <?php foreach ($validStatuses as $s): ?>
                  <option value="<?= h($s) ?>" <?= $b['status'] === $s ? 'selected' : '' ?>><?= h(ucfirst($s)) ?></option>
                <?php endforeach; ?>
              </select>
            </form>
          </td>
          <td>
            <form class="admin-inline-form" method="post" action="bookings.php<?= $filter ? '?status=' . h($filter) : '' ?>" onsubmit="return confirm('Delete this booking?');">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>" />
              <input type="hidden" name="action" value="delete" />
              <input type="hidden" name="id" value="<?= (int) $b['id'] ?>" />
              <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$bookings): ?>
        <tr><td colspan="8">No bookings found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
