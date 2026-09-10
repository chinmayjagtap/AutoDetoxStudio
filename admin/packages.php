<?php
require __DIR__ . '/includes/auth.php';
require_admin();

$pageTitle = 'Packages';
$active = 'packages';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        set_flash('error', 'Session expired, please try again.');
        redirect('packages.php');
    }

    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'delete' && $id) {
        $pdo->prepare('DELETE FROM packages WHERE id = :id')->execute(['id' => $id]);
        set_flash('success', 'Package deleted.');
        redirect('packages.php');
    }

    if ($action === 'save') {
        $slug = trim($_POST['slug'] ?? '');
        $tag = trim($_POST['tag'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $examples = trim($_POST['examples'] ?? '');
        $price = (float) ($_POST['price'] ?? 0);
        $strikePrice = (float) ($_POST['strike_price'] ?? 0);
        $note = trim($_POST['note'] ?? '');
        $features = trim($_POST['features'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $displayOrder = (int) ($_POST['display_order'] ?? 0);

        $errors = [];
        if ($slug === '' || !preg_match('/^[a-z0-9-]+$/', $slug)) {
            $errors[] = 'Slug must be lowercase letters, numbers and hyphens only.';
        }
        if ($name === '') {
            $errors[] = 'Name is required.';
        }
        if ($price <= 0 || $strikePrice <= 0) {
            $errors[] = 'Prices must be greater than 0.';
        }

        if ($errors) {
            set_flash('error', implode(' ', $errors));
            redirect('packages.php');
        }

        if ($id) {
            $stmt = $pdo->prepare(
                'UPDATE packages SET slug=:slug, tag=:tag, name=:name, examples=:examples, price=:price,
                 strike_price=:strike_price, note=:note, features=:features, is_featured=:is_featured, display_order=:display_order
                 WHERE id=:id'
            );
            $stmt->execute([
                'slug' => $slug, 'tag' => $tag, 'name' => $name, 'examples' => $examples, 'price' => $price,
                'strike_price' => $strikePrice, 'note' => $note, 'features' => $features,
                'is_featured' => $isFeatured, 'display_order' => $displayOrder, 'id' => $id,
            ]);
            set_flash('success', 'Package updated.');
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO packages (slug, tag, name, examples, price, strike_price, note, features, is_featured, display_order)
                 VALUES (:slug, :tag, :name, :examples, :price, :strike_price, :note, :features, :is_featured, :display_order)'
            );
            $stmt->execute([
                'slug' => $slug, 'tag' => $tag, 'name' => $name, 'examples' => $examples, 'price' => $price,
                'strike_price' => $strikePrice, 'note' => $note, 'features' => $features,
                'is_featured' => $isFeatured, 'display_order' => $displayOrder,
            ]);
            set_flash('success', 'Package created.');
        }
        redirect('packages.php');
    }
}

$packages = $pdo->query('SELECT * FROM packages ORDER BY display_order ASC')->fetchAll();

$editing = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM packages WHERE id = :id');
    $stmt->execute(['id' => (int) $_GET['edit']]);
    $editing = $stmt->fetch() ?: null;
}

require __DIR__ . '/includes/header.php';
?>

<div class="admin-card">
  <h3 style="margin-bottom:14px;"><?= $editing ? 'Edit Package' : 'Add New Package' ?></h3>
  <form method="post" action="packages.php">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>" />
    <input type="hidden" name="action" value="save" />
    <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>" /><?php endif; ?>

    <div class="admin-form-grid">
      <div class="admin-form-row">
        <label>Slug (unique, e.g. hatchback)</label>
        <input type="text" name="slug" value="<?= h($editing['slug'] ?? '') ?>" required />
      </div>
      <div class="admin-form-row">
        <label>Tag (e.g. Compact, Most Booked)</label>
        <input type="text" name="tag" value="<?= h($editing['tag'] ?? '') ?>" />
      </div>
      <div class="admin-form-row">
        <label>Name</label>
        <input type="text" name="name" value="<?= h($editing['name'] ?? '') ?>" required />
      </div>
      <div class="admin-form-row">
        <label>Example models</label>
        <input type="text" name="examples" value="<?= h($editing['examples'] ?? '') ?>" />
      </div>
      <div class="admin-form-row">
        <label>Price (₹)</label>
        <input type="number" step="1" name="price" value="<?= h((string) ($editing['price'] ?? '')) ?>" required />
      </div>
      <div class="admin-form-row">
        <label>Strike-through Price (₹)</label>
        <input type="number" step="1" name="strike_price" value="<?= h((string) ($editing['strike_price'] ?? '')) ?>" required />
      </div>
      <div class="admin-form-row">
        <label>Note</label>
        <input type="text" name="note" value="<?= h($editing['note'] ?? 'After 50% introductory discount') ?>" />
      </div>
      <div class="admin-form-row">
        <label>Display Order</label>
        <input type="number" name="display_order" value="<?= h((string) ($editing['display_order'] ?? 0)) ?>" />
      </div>
    </div>

    <div class="admin-form-row">
      <label>Features (one per line)</label>
      <textarea name="features" rows="5"><?= h($editing['features'] ?? "Full 23-step detox\nInterior + Exterior + Engine bay\nPremium liquid wax finish\nComplete steam sanitization") ?></textarea>
    </div>

    <div class="admin-form-row" style="flex-direction:row; align-items:center; gap:8px;">
      <input type="checkbox" name="is_featured" id="is_featured" <?= !empty($editing['is_featured']) ? 'checked' : '' ?> style="width:auto;" />
      <label for="is_featured" style="margin:0;">Mark as "Most Booked" (featured)</label>
    </div>

    <button type="submit" class="admin-btn admin-btn-primary"><?= $editing ? 'Update Package' : 'Create Package' ?></button>
    <?php if ($editing): ?><a href="packages.php" class="admin-btn">Cancel</a><?php endif; ?>
  </form>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead><tr><th>Order</th><th>Name</th><th>Tag</th><th>Price</th><th>Featured</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($packages as $p): ?>
        <tr>
          <td><?= (int) $p['display_order'] ?></td>
          <td><?= h($p['name']) ?></td>
          <td><?= h($p['tag']) ?></td>
          <td><?= format_inr($p['price']) ?></td>
          <td><?= $p['is_featured'] ? 'Yes' : '—' ?></td>
          <td style="display:flex; gap:6px;">
            <a href="packages.php?edit=<?= (int) $p['id'] ?>" class="admin-btn admin-btn-sm">Edit</a>
            <form method="post" action="packages.php" onsubmit="return confirm('Delete this package?');">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>" />
              <input type="hidden" name="action" value="delete" />
              <input type="hidden" name="id" value="<?= (int) $p['id'] ?>" />
              <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$packages): ?>
        <tr><td colspan="6">No packages yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
