<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/Animal.php';

$user   = getCurrentUser();
$animal = new Animal();
$id     = (int)($_GET['id'] ?? 0);

$record = $animal->getById($id);

if (!$record) {
    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Hayvan bulunamadı.'];
    header('Location: ' . SITE_URL . '/pages/animals.php');
    exit;
}

if (!$animal->canEdit($id, $user['id'], $user['role'])) {
    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Bu kaydı silme yetkiniz yok.'];
    header('Location: ' . SITE_URL . '/pages/animals.php');
    exit;
}

$name = $record['name'];
if ($animal->delete($id)) {
    $_SESSION['flash'] = ['type' => 'success', 'msg' => '"' . $name . '" başarıyla silindi.'];
} else {
    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Silme işlemi başarısız.'];
}

header('Location: ' . SITE_URL . '/pages/animals.php');
exit;
