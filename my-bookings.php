<?php
require __DIR__ . '/includes/bootstrap.php';

$phone = trim($_GET['phone'] ?? '');
$bookings = [];
$searched = false;

if ($phone !== '') {
    $searched = true;
    if (preg_match('/^[6-9]\d{9}$/', $phone)) {
        $stmt = $pdo->prepare(
            'SELECT b.id, b.customer_name, b.preferred_date, b.preferred_time, b.status, b.created_at,
                    p.name AS package_name
             FROM bookings b
             LEFT JOIN packages p ON p.id = b.package_id
             WHERE b.phone = :phone
             ORDER BY b.created_at DESC'
        );
        $stmt->execute(['phone' => $phone]);
        $bookings = $stmt->fetchAll();
    }
}

$statusLabels = [
    'pending' => 'Pending Confirmation',
    'confirmed' => 'Confirmed',
    'completed' => 'Completed',
    'cancelled' => 'Cancelled',
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="theme-color" content="#FFFFFF" />
  <title>Check My Booking — Auto Detox Studio</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg" />
  <style>
    .lookup-wrap { max-width: 640px; margin: 0 auto; padding: 140px 24px 80px; }
    .lookup-wrap h1 { font-size: 30px; margin-bottom: 8px; }
    .lookup-wrap .muted { color: var(--text-2); margin-bottom: 28px; }
    .lookup-form { display: flex; gap: 10px; margin-bottom: 32px; }
    .lookup-form input {
      flex: 1; padding: 14px 16px; border-radius: var(--radius-sm);
      border: 1px solid var(--border); background: var(--bg-2); color: var(--text); font-size: 15px;
    }
    .lookup-form input:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-08); }
    .lookup-table { width: 100%; border-collapse: collapse; }
    .lookup-table th, .lookup-table td {
      text-align: left; padding: 12px 10px; border-bottom: 1px solid var(--border); font-size: 14px;
    }
    .lookup-table th { color: var(--text-2); font-size: 12px; letter-spacing: .06em; text-transform: uppercase; }
    .status-pill {
      display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;
      border: 1px solid var(--border);
    }
    .status-pending { background: var(--gold-08); color: var(--gold-dark); border-color: var(--gold-25); }
    .status-confirmed { background: var(--gold-15); color: #8a6200; border-color: var(--gold-25); }
    .status-completed { background: rgba(34,160,107,.1); color: #1c8f5c; border-color: rgba(34,160,107,.3); }
    .status-cancelled { background: rgba(229,62,91,.1); color: #c23a54; border-color: rgba(229,62,91,.3); }
    .back-link { display: inline-block; margin-bottom: 20px; color: var(--gold-dark); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: .05em; }
    .back-link:hover { color: var(--gold); }
  </style>
</head>
<body>
  <div class="lookup-wrap">
    <a href="index.php" class="back-link">← Back to Home</a>
    <h1>Check My Booking</h1>
    <p class="muted">Enter the phone number you used while booking to see its status.</p>

    <form class="lookup-form" method="get" action="my-bookings.php">
      <input type="tel" name="phone" placeholder="10-digit phone number" value="<?= h($phone) ?>" pattern="[6-9][0-9]{9}" required />
      <button type="submit" class="btn btn-primary">Check</button>
    </form>

    <?php if ($searched): ?>
      <?php if (!preg_match('/^[6-9]\d{9}$/', $phone)): ?>
        <p class="muted">Please enter a valid 10-digit phone number.</p>
      <?php elseif (!$bookings): ?>
        <p class="muted">No bookings found for this phone number.</p>
      <?php else: ?>
        <table class="lookup-table">
          <thead>
            <tr><th>Package</th><th>Date</th><th>Time</th><th>Status</th></tr>
          </thead>
          <tbody>
            <?php foreach ($bookings as $b): ?>
              <tr>
                <td><?= h($b['package_name'] ?? '—') ?></td>
                <td><?= h(date('d M Y', strtotime($b['preferred_date']))) ?></td>
                <td><?= h($b['preferred_time']) ?></td>
                <td>
                  <span class="status-pill status-<?= h($b['status']) ?>">
                    <?= h($statusLabels[$b['status']] ?? $b['status']) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</body>
</html>
