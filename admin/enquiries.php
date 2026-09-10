<?php
require __DIR__ . '/includes/auth.php';
require_admin();
$pageTitle='Enquiries & Popup'; $active='enquiries';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (!csrf_check()) { set_flash('error','Session expired, please try again.'); redirect('enquiries.php'); }
  $action=$_POST['action']??'';
  if ($action==='settings') {
    $settings=['enquiry_popup_enabled'=>isset($_POST['enabled'])?'1':'0','enquiry_popup_title'=>trim($_POST['popup_title']??''),'enquiry_popup_message'=>trim($_POST['popup_message']??'')];
    $stmt=$pdo->prepare('INSERT INTO site_settings(setting_key,setting_value) VALUES(:k,:v) ON DUPLICATE KEY UPDATE setting_value=:v2');
    foreach($settings as $k=>$v) $stmt->execute(['k'=>$k,'v'=>$v,'v2'=>$v]);
    set_flash('success','Enquiry popup settings saved.'); redirect('enquiries.php');
  }
  if ($action==='status') { $stmt=$pdo->prepare('UPDATE enquiries SET status=:status WHERE id=:id'); $stmt->execute(['status'=>$_POST['status']??'new','id'=>(int)$_POST['id']]); set_flash('success','Enquiry status updated.'); redirect('enquiries.php'); }
  if ($action==='delete') { $pdo->prepare('DELETE FROM enquiries WHERE id=:id')->execute(['id'=>(int)$_POST['id']]); set_flash('success','Enquiry deleted.'); redirect('enquiries.php'); }
}
$settings=$pdo->query('SELECT setting_key,setting_value FROM site_settings')->fetchAll(PDO::FETCH_KEY_PAIR);
$enquiries=$pdo->query('SELECT * FROM enquiries ORDER BY created_at DESC')->fetchAll();
require __DIR__.'/includes/header.php';
?>
<div class="admin-card"><h3>Popup Enquiry Form</h3><p class="admin-help">Control the quick-enquiry popup shown on the website. The floating Quick Enquiry button remains available when enabled.</p>
<form method="post"><input type="hidden" name="csrf_token" value="<?=h(csrf_token())?>"><input type="hidden" name="action" value="settings">
<div class="admin-form-row" style="flex-direction:row;align-items:center;gap:10px"><input type="checkbox" name="enabled" id="enabled" <?=($settings['enquiry_popup_enabled']??'1')==='1'?'checked':''?> style="width:auto"><label for="enabled" style="margin:0">Enable enquiry popup</label></div>
<div class="admin-form-row"><label>Popup title</label><input name="popup_title" maxlength="160" value="<?=h($settings['enquiry_popup_title']??'Need help choosing the right service?')?>"></div>
<div class="admin-form-row"><label>Popup message</label><textarea name="popup_message" rows="3"><?=h($settings['enquiry_popup_message']??'Leave your details and our team will call you with the right recommendation.')?></textarea></div>
<button class="admin-btn admin-btn-primary">Save Popup Settings</button></form></div>
<div class="admin-card"><h3>Customer Enquiries</h3><table class="admin-table"><thead><tr><th>Customer</th><th>Contact</th><th>Service</th><th>Message</th><th>Date</th><th>Status</th><th>Action</th></tr></thead><tbody>
<?php foreach($enquiries as $e): ?><tr><td><?=h($e['name'])?></td><td><?=h($e['phone'])?><br><?=h($e['email']??'')?></td><td><?=h($e['service']??'—')?></td><td><?=h($e['message']??'—')?></td><td><?=h(date('d M Y, h:i A',strtotime($e['created_at'])))?></td><td><form class="admin-inline-form" method="post"><input type="hidden" name="csrf_token" value="<?=h(csrf_token())?>"><input type="hidden" name="action" value="status"><input type="hidden" name="id" value="<?=h((string)$e['id'])?>"><select name="status" onchange="this.form.submit()"><option value="new" <?=$e['status']==='new'?'selected':''?>>New</option><option value="contacted" <?=$e['status']==='contacted'?'selected':''?>>Contacted</option><option value="closed" <?=$e['status']==='closed'?'selected':''?>>Closed</option></select></form></td><td><form method="post" onsubmit="return confirm('Delete enquiry?');"><input type="hidden" name="csrf_token" value="<?=h(csrf_token())?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=h((string)$e['id'])?>"><button class="admin-btn admin-btn-danger admin-btn-sm">Delete</button></form></td></tr><?php endforeach; ?><?php if(!$enquiries): ?><tr><td colspan="7">No enquiries yet.</td></tr><?php endif; ?></tbody></table></div>
<?php require __DIR__.'/includes/footer.php'; ?>
