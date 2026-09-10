<?php
require __DIR__ . '/includes/auth.php';
require_admin();

$pageTitle = 'Customers';
$active = 'customers';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        set_flash('error', 'Session expired, please try again.');
        redirect('customers.php');
    }

    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'toggle_block' && $id) {
        $stmt = $pdo->prepare('UPDATE customers SET is_blocked = 1 - is_blocked WHERE id = :id');
        $stmt->execute(['id' => $id]);
        set_flash('success', 'Customer updated.');
    } elseif ($action === 'delete' && $id) {
        $stmt = $pdo->prepare('DELETE FROM customers WHERE id = :id');
        $stmt->execute(['id' => $id]);
        set_flash('success', 'Customer deleted along with their bookings.');
    }
    redirect('customers.php');
}

$customers = $pdo->query(
    'SELECT c.*, COUNT(b.id) AS booking_count
     FROM customers c LEFT JOIN bookings b ON b.customer_id = c.id
     GROUP BY c.id ORDER BY c.created_at DESC'
)->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr><th>Name</th><th>Phone</th><th>Email</th><th>Bookings</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($customers as $c): ?>
        <tr>
          <td><?= h($c['name']) ?></td>
          <td><?= h($c['phone']) ?></td>
          <td><?= h($c['email'] ?: '—') ?></td>
          <td><?= (int) $c['booking_count'] ?></td>
          <td><?= $c['is_blocked'] ? 'Blocked' : 'Active' ?></td>
          <td style="display:flex; gap:6px;">
            <form method="post" action="customers.php">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>" />
              <input type="hidden" name="action" value="toggle_block" />
              <input type="hidden" name="id" value="<?= (int) $c['id'] ?>" />
              <button type="submit" class="admin-btn admin-btn-sm"><?= $c['is_blocked'] ? 'Unblock' : 'Block' ?></button>
            </form>
            <form method="post" action="customers.php" onsubmit="return confirm('Delete this customer and all their bookings?');">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>" />
              <input type="hidden" name="action" value="delete" />
              <input type="hidden" name="id" value="<?= (int) $c['id'] ?>" />
              <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$customers): ?>
        <tr><td colspan="6">No customers yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
