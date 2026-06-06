<?php
$pageTitle = 'Hayvan Düzenle';
require_once __DIR__ . '/../includes/header.php';
requireLogin();
require_once __DIR__ . '/../includes/Animal.php';

$user   = getCurrentUser();
$animal = new Animal();
$id     = (int)($_GET['id'] ?? 0);

$record = $animal->getById($id);
if (!$record) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Hayvan bulunamadı.</div></div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

if (!$animal->canEdit($id, $user['id'], $user['role'])) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Bu kaydı düzenleme yetkiniz yok.</div></div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name'       => trim($_POST['name'] ?? ''),
        'species'    => trim($_POST['species'] ?? ''),
        'gender'     => $_POST['gender'] ?? 'Bilinmiyor',
        'birth_date' => $_POST['birth_date'] ?? '',
        'enclosure'  => trim($_POST['enclosure'] ?? ''),
        'diet'       => trim($_POST['diet'] ?? ''),
        'status'     => $_POST['status'] ?? 'Sağlıklı',
        'weight_kg'  => $_POST['weight_kg'] ?? '',
        'notes'      => trim($_POST['notes'] ?? ''),
    ];

    if (empty($data['name']) || empty($data['species'])) {
        $error = 'Hayvan adı ve türü zorunludur.';
    } else {
        if ($animal->update($id, $data)) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => '"' . $data['name'] . '" başarıyla güncellendi.'];
            header('Location: ' . SITE_URL . '/pages/animals.php');
            exit;
        } else {
            $error = 'Güncelleme sırasında bir hata oluştu.';
        }
    }
    // Form yeniden gösterilecekse POST değerlerini kullan
    $record = array_merge($record, $data);
}
?>

<div class="page-header">
    <div class="container-fluid px-4">
        <h1><i class="bi bi-pencil-square me-2"></i>Hayvan Düzenle</h1>
        <p><?= sanitize($record['name']) ?> — <?= sanitize($record['species']) ?></p>
    </div>
</div>

<div class="container-fluid px-4 pb-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="zoo-card">

                <?php if ($error): ?>
                    <div class="alert zoo-alert alert-danger auto-dismiss mb-4">
                        <i class="bi bi-exclamation-circle me-2"></i><?= sanitize($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" novalidate>
                    <div class="text-center mb-4">
                        <div style="font-size:4rem;line-height:1;" id="emoji-preview">🐾</div>
                        <small class="text-muted">Tür girilince otomatik güncellenir</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3" style="color: var(--zoo-amber)">
                                <i class="bi bi-info-circle me-1"></i>Temel Bilgiler
                            </h6>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Hayvan Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="<?= sanitize($record['name']) ?>" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="species">Türü <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="species" name="species"
                                   value="<?= sanitize($record['species']) ?>" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Cinsiyet</label>
                            <select class="form-select" name="gender">
                                <?php foreach (['Erkek','Dişi','Bilinmiyor'] as $g): ?>
                                    <option value="<?= $g ?>" <?= $record['gender'] === $g ? 'selected' : '' ?>><?= $g ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Doğum Tarihi</label>
                            <input type="date" class="form-control" name="birth_date"
                                   value="<?= sanitize($record['birth_date'] ?? '') ?>">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Ağırlık (kg)</label>
                            <input type="number" step="0.01" min="0" class="form-control"
                                   name="weight_kg" value="<?= sanitize($record['weight_kg'] ?? '') ?>">
                        </div>

                        <div class="col-12 mt-2">
                            <h6 class="fw-bold mb-3" style="color: var(--zoo-amber)">
                                <i class="bi bi-house me-1"></i>Yerleşim & Bakım
                            </h6>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Barınak / Bölge</label>
                            <input type="text" class="form-control" name="enclosure"
                                   value="<?= sanitize($record['enclosure'] ?? '') ?>">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Sağlık Durumu</label>
                            <select class="form-select" name="status">
                                <?php foreach (['Sağlıklı','Tedavide','Karantinada','Vefat Etti'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $record['status'] === $s ? 'selected' : '' ?>>
                                        <?= $s === 'Sağlıklı' ? '✅' : ($s === 'Tedavide' ? '🏥' : ($s === 'Karantinada' ? '⚠️' : '🪦')) ?>
                                        <?= $s ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Beslenme / Diyet</label>
                            <input type="text" class="form-control" name="diet"
                                   value="<?= sanitize($record['diet'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notlar</label>
                            <textarea class="form-control" name="notes" rows="3"><?= sanitize($record['notes'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                            <a href="<?= SITE_URL ?>/pages/animals.php" class="btn btn-zoo-outline">
                                <i class="bi bi-x me-1"></i>İptal
                            </a>
                            <button type="submit" class="btn btn-zoo-primary">
                                <i class="bi bi-check-circle me-1"></i>Kaydet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Mevcut türe göre emoji'yi başlangıçta ayarla
document.addEventListener('DOMContentLoaded', () => {
    const sp = document.getElementById('species');
    const pr = document.getElementById('emoji-preview');
    if (sp && pr) {
        pr.textContent = getAnimalEmoji(sp.value);
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
