<?php

$page->layout = 'admin';

if (! User::require_admin ()) {
	header ('Location: /admin');
	exit;
}

if (! preg_match ('/^(css|layouts)\/[a-z0-9_-]+\.(css|html)$/i', $_GET['file'])) {
	header ('Location: /designer');
	exit;
}

$lock = new Lock ('Designer', $_GET['file']);
if ($lock->exists ()) {
	$page->title = i18n_get ('Editing Locked');
	echo $tpl->render ('admin/locked', $lock->info ());
	return;
}

if (! @unlink ($_GET['file'])) {
	$page->title = 'Unable to Delete File';
	echo '<p>Check that your permissions are correct and try again.</p>';
	echo '<p><a href="/designer">Continue</a></p>';
	return;
}

$page->title = 'File Deleted';
echo '<p><a href="/designer">Continue</a></p>';

?>