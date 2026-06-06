<?php
$pageTitle = 'Hayvan Ekle';
require_once __DIR__ . '/../includes/header.php';
requireLogin();
require_once __DIR__ . '/../includes/Animal.php';

$user   = getCurrentUser();
$animal = new Animal();
$error  = '';

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
        'added_by'   => $user['id'],
    ];

    if (empty($data['name']) || empty($data['species'])) {
        $error = 'Hayvan adı ve türü zorunludur.';
    } else {
        if ($animal->create($data)) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => '"' . $data['name'] . '" başarıyla eklendi.'];
            header('Location: ' . SITE_URL . '/pages/animals.php');
            exit;
        } else {
            $error = 'Hayvan eklenirken bir hata oluştu.';
        }
    }
}
?>

<div class="page-header">
    <div class="container-fluid px-4">
        <h1><i class="bi bi-plus-circle me-2"></i>Yeni Hayvan Ekle</h1>
        <p>Hayvanat bahçesine yeni bir hayvan kaydı oluştur</p>
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
                    <!-- Emoji önizlemesi -->
                    <div class="text-center mb-4">
                        <div style="font-size:4rem;line-height:1;" id="emoji-preview">🐾</div>
                        <small class="text-muted">Tür girilince otomatik güncellenir</small>
                    </div>

                    <div class="row g-3">
                        <!-- Temel Bilgiler -->
                        <div class="col-12">
                            <h6 class="fw-bold mb-3" style="color: var(--zoo-amber)">
                                <i class="bi bi-info-circle me-1"></i>Temel Bilgiler
                            </h6>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Hayvan Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="<?= sanitize($_POST['name'] ?? '') ?>"
                                   placeholder="örn. Simba" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="species">Türü <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="species" name="species"
                                   value="<?= sanitize($_POST['species'] ?? '') ?>"
                                   placeholder="örn. Aslan" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="gender">Cinsiyet</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="Erkek" <?= ($_POST['gender'] ?? '') === 'Erkek' ? 'selected' : '' ?>>Erkek</option>
                                <option value="Dişi"  <?= ($_POST['gender'] ?? '') === 'Dişi'  ? 'selected' : '' ?>>Dişi</option>
                                <option value="Bilinmiyor" <?= ($_POST['gender'] ?? 'Bilinmiyor') === 'Bilinmiyor' ? 'selected' : '' ?>>Bilinmiyor</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="birth_date">Doğum Tarihi</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date"
                                   value="<?= sanitize($_POST['birth_date'] ?? '') ?>">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="weight_kg">Ağırlık (kg)</label>
                            <input type="number" step="0.01" min="0" class="form-control"
                                   id="weight_kg" name="weight_kg"
                                   value="<?= sanitize($_POST['weight_kg'] ?? '') ?>"
                                   placeholder="örn. 180.5">
                        </div>

                        <!-- Yerleşim & Bakım -->
                        <div class="col-12 mt-2">
                            <h6 class="fw-bold mb-3" style="color: var(--zoo-amber)">
                                <i class="bi bi-house me-1"></i>Yerleşim & Bakım
                            </h6>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="enclosure">Barınak / Bölge</label>
                            <input type="text" class="form-control" id="enclosure" name="enclosure"
                                   value="<?= sanitize($_POST['enclosure'] ?? '') ?>"
                                   placeholder="örn. A2 — Afrika Bölgesi">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="status">Sağlık Durumu</label>
                            <select class="form-select" id="status" name="status">
                                <option value="Sağlıklı"    <?= ($_POST['status'] ?? 'Sağlıklı') === 'Sağlıklı'    ? 'selected' : '' ?>>✅ Sağlıklı</option>
                                <option value="Tedavide"    <?= ($_POST['status'] ?? '') === 'Tedavide'    ? 'selected' : '' ?>>🏥 Tedavide</option>
                                <option value="Karantinada" <?= ($_POST['status'] ?? '') === 'Karantinada' ? 'selected' : '' ?>>⚠️ Karantinada</option>
                                <option value="Vefat Etti"  <?= ($_POST['status'] ?? '') === 'Vefat Etti'  ? 'selected' : '' ?>>🪦 Vefat Etti</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="diet">Beslenme / Diyet</label>
                            <input type="text" class="form-control" id="diet" name="diet"
                                   value="<?= sanitize($_POST['diet'] ?? '') ?>"
                                   placeholder="örn. Et, günde 2 kez — 5 kg">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="notes">Notlar</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                      placeholder="Ek bilgiler, davranış notları..."><?= sanitize($_POST['notes'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                            <a href="<?= SITE_URL ?>/pages/animals.php" class="btn btn-zoo-outline">
                                <i class="bi bi-x me-1"></i>İptal
                            </a>
                            <button type="submit" class="btn btn-zoo-primary">
                                <i class="bi bi-plus-circle me-1"></i>Hayvan Ekle
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
