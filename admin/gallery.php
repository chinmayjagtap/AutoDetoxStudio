<?php
require __DIR__ . '/includes/auth.php';
require_admin();
$pageTitle = 'Gallery & Videos';
$active = 'gallery';
$uploadDir = dirname(__DIR__) . '/assets/uploads/';
$publicBase = '../assets/uploads/';
if (!is_dir($uploadDir . 'gallery')) @mkdir($uploadDir . 'gallery', 0755, true);
if (!is_dir($uploadDir . 'videos')) @mkdir($uploadDir . 'videos', 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_check()) { set_flash('error','Session expired, please try again.'); redirect('gallery.php'); }
  $action = $_POST['action'] ?? '';
  if ($action === 'upload' && isset($_FILES['media'])) {
    $file = $_FILES['media'];
    $title = trim($_POST['title'] ?? 'Gallery');
    $subtitle = trim($_POST['subtitle'] ?? 'Auto Detox Studio');
    $type = $_POST['media_type'] ?? 'image';
    $allowed = $type === 'video' ? ['mp4','webm'] : ['jpg','jpeg','png','webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $max = $type === 'video' ? 30 * 1024 * 1024 : 8 * 1024 * 1024;
    if ($file['error'] !== UPLOAD_ERR_OK || !in_array($ext, $allowed, true) || $file['size'] > $max) {
      set_flash('error', 'Invalid file. Images: JPG/PNG/WEBP up to 8MB; videos: MP4/WEBM up to 30MB.');
    } else {
      $name = bin2hex(random_bytes(8)) . '.' . $ext;
      $folder = $type === 'video' ? 'videos' : 'gallery';
      $relative = 'assets/uploads/' . $folder . '/' . $name;
      if (move_uploaded_file($file['tmp_name'], $uploadDir . $folder . '/' . $name)) {
        $stmt = $pdo->prepare('INSERT INTO gallery_media (media_type,file_path,title,subtitle,display_order) VALUES (:type,:path,:title,:subtitle,0)');
        $stmt->execute(['type'=>$type,'path'=>$relative,'title'=>$title ?: 'Gallery','subtitle'=>$subtitle ?: 'Auto Detox Studio']);
        set_flash('success', ucfirst($type) . ' uploaded successfully.');
      } else set_flash('error','Upload failed. Check folder permissions.');
    }
    redirect('gallery.php');
  }
  if ($action === 'toggle') {
    $stmt = $pdo->prepare('UPDATE gallery_media SET is_active = IF(is_active=1,0,1) WHERE id=:id');
    $stmt->execute(['id'=>(int)$_POST['id']]); set_flash('success','Gallery visibility updated.'); redirect('gallery.php');
  }
  if ($action === 'delete') {
    $stmt = $pdo->prepare('SELECT file_path FROM gallery_media WHERE id=:id'); $stmt->execute(['id'=>(int)$_POST['id']]); $row=$stmt->fetch();
    if ($row) { $full=dirname(__DIR__).'/'.$row['file_path']; if (is_file($full)) @unlink($full); $pdo->prepare('DELETE FROM gallery_media WHERE id=:id')->execute(['id'=>(int)$_POST['id']]); }
    set_flash('success','Media deleted.'); redirect('gallery.php');
  }
}
$items = $pdo->query('SELECT * FROM gallery_media ORDER BY created_at DESC')->fetchAll();
require __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
  <h3>Upload Photo / Video</h3>
  <p class="admin-help">Uploaded media appears randomly in the public gallery. No Instagram redirect is used.</p>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="upload">
    <div class="admin-form-grid">
      <div class="admin-form-row"><label>Media Type</label><select name="media_type"><option value="image">Photo</option><option value="video">Video</option></select></div>
      <div class="admin-form-row"><label>File</label><input type="file" name="media" required accept="image/jpeg,image/png,image/webp,video/mp4,video/webm"></div>
      <div class="admin-form-row"><label>Title</label><input name="title" maxlength="160" placeholder="Showroom Shine"></div>
      <div class="admin-form-row"><label>Subtitle</label><input name="subtitle" maxlength="160" placeholder="Exterior Detail"></div>
    </div>
    <button class="admin-btn admin-btn-primary" type="submit">Upload Media</button>
  </form>
</div>
<div class="admin-card"><table class="admin-table"><thead><tr><th>Preview</th><th>Type</th><th>Title</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php foreach($items as $item): ?><tr>
<td><?php if($item['media_type']==='video'): ?><video src="../<?=h($item['file_path'])?>" muted style="width:90px;height:55px;object-fit:cover;border-radius:8px"></video><?php else: ?><img src="../<?=h($item['file_path'])?>" style="width:90px;height:55px;object-fit:cover;border-radius:8px" alt=""><?php endif; ?></td>
<td><?=h(ucfirst($item['media_type']))?></td><td><?=h($item['title'])?><br><small><?=h($item['subtitle'])?></small></td><td><?=((int)$item['is_active']?'Active':'Hidden')?></td>
<td style="display:flex;gap:6px"><form method="post"><input type="hidden" name="csrf_token" value="<?=h(csrf_token())?>"><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?=h((string)$item['id'])?>"><button class="admin-btn admin-btn-sm" type="submit"><?=((int)$item['is_active']?'Hide':'Show')?></button></form><form method="post" onsubmit="return confirm('Delete this media?');"><input type="hidden" name="csrf_token" value="<?=h(csrf_token())?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=h((string)$item['id'])?>"><button class="admin-btn admin-btn-danger admin-btn-sm" type="submit">Delete</button></form></td>
</tr><?php endforeach; ?><?php if(!$items): ?><tr><td colspan="5">No uploads yet. Add photos or videos above.</td></tr><?php endif; ?></tbody></table></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
