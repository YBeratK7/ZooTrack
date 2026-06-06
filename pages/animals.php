<?php
$pageTitle = 'Hayvanlar';
require_once __DIR__ . '/../includes/header.php';
requireLogin();
require_once __DIR__ . '/../includes/Animal.php';

$user    = getCurrentUser();
$animal  = new Animal();
$animals = $animal->getAll($user['id'], $user['role']);

// Filtreleme
$filterStatus  = $_GET['status'] ?? '';
$filterSearch  = trim($_GET['q'] ?? '');

if ($filterStatus) {
    $animals = array_filter($animals, fn($a) => $a['status'] === $filterStatus);
}
if ($filterSearch) {
    $animals = array_filter($animals, fn($a) =>
        stripos($a['name'], $filterSearch) !== false ||
        stripos($a['species'], $filterSearch) !== false
    );
}

$statusMap = [
    'Sağlıklı'    => ['class'=>'status-healthy',    'icon'=>'✅'],
    'Tedavide'    => ['class'=>'status-treatment',  'icon'=>'🏥'],
    'Karantinada' => ['class'=>'status-quarantine', 'icon'=>'⚠️'],
    'Vefat Etti'  => ['class'=>'status-deceased',   'icon'=>'🪦'],
];

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<div class="page-header">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="bi bi-grid-3x3-gap me-2"></i>Hayvanlar</h1>
                <p><?= count($animals) ?> kayıt listeleniyor</p>
            </div>
            <a href="<?= SITE_URL ?>/pages/add_animal.php" class="btn btn-zoo-primary">
                <i class="bi bi-plus-circle me-1"></i>Hayvan Ekle
            </a>
        </div>
    </div>
</div>

<div class="container-fluid px-4 pb-4">

    <?php if ($flash): ?>
        <div class="alert zoo-alert alert-<?= $flash['type'] ?> auto-dismiss mb-3">
            <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-2"></i>
            <?= sanitize($flash['msg']) ?>
        </div>
    <?php endif; ?>

    <!-- Filtreler -->
    <div class="zoo-card mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-sm-5">
                <label class="form-label">Hayvan Ara</label>
                <input type="text" class="form-control" name="q"
                       placeholder="İsim veya tür..." value="<?= sanitize($filterSearch) ?>">
            </div>
            <div class="col-sm-4">
                <label class="form-label">Duruma Göre Filtrele</label>
                <select class="form-select" name="status">
                    <option value="">Tüm Durumlar</option>
                    <?php foreach (array_keys($statusMap) as $s): ?>
                        <option value="<?= $s ?>" <?= $filterStatus === $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-3 d-flex gap-2">
                <button type="submit" class="btn btn-zoo-primary">
                    <i class="bi bi-search me-1"></i>Filtrele
                </button>
                <a href="<?= SITE_URL ?>/pages/animals.php" class="btn btn-zoo-outline">Temizle</a>
            </div>
        </form>
    </div>

    <!-- Hayvan Kartları -->
    <?php if (empty($animals)): ?>
        <div class="empty-state zoo-card">
            <span class="empty-icon">🦒</span>
            <h5>Hayvan bulunamadı</h5>
            <p class="text-muted mb-3">Arama kriterlerini değiştirin veya yeni hayvan ekleyin.</p>
            <a href="<?= SITE_URL ?>/pages/add_animal.php" class="btn btn-zoo-primary">
                <i class="bi bi-plus-circle me-2"></i>Hayvan Ekle
            </a>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($animals as $a):
                $st = $statusMap[$a['status']] ?? $statusMap['Sağlıklı'];
            ?>
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="animal-card h-100">
                    <div class="animal-card-header">
                        <div class="animal-emoji" id="emoji-<?= $a['id'] ?>"
                             data-species="<?= htmlspecialchars($a['species']) ?>">🐾</div>
                        <div class="flex-grow-1 min-w-0">
                            <h6 class="mb-0 fw-bold text-truncate"><?= sanitize($a['name']) ?></h6>
                            <small class="text-muted"><?= sanitize($a['species']) ?></small>
                            <div class="mt-1">
                                <span class="status-badge <?= $st['class'] ?>">
                                    <?= $st['icon'] ?> <?= $a['status'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="animal-card-body">
                        <div class="row g-1 small text-muted">
                            <div class="col-6">
                                <i class="bi bi-gender-ambiguous"></i> <?= sanitize($a['gender']) ?>
                            </div>
                            <?php if ($a['enclosure']): ?>
                            <div class="col-6">
                                <i class="bi bi-house"></i> <?= sanitize($a['enclosure']) ?>
                            </div>
                            <?php endif; ?>
                            <?php if ($a['weight_kg']): ?>
                            <div class="col-6">
                                <i class="bi bi-speedometer"></i> <?= number_format($a['weight_kg'], 1) ?> kg
                            </div>
                            <?php endif; ?>
                            <?php if ($a['birth_date']): ?>
                            <div class="col-6">
                                <i class="bi bi-calendar3"></i> <?= date('d.m.Y', strtotime($a['birth_date'])) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="animal-card-footer">
                        <a href="<?= SITE_URL ?>/pages/view_animal.php?id=<?= $a['id'] ?>"
                           class="btn btn-sm btn-zoo-outline flex-fill text-center">
                            <i class="bi bi-eye"></i>
                        </a>
                        <?php if ($animal->canEdit($a['id'], $user['id'], $user['role'])): ?>
                        <a href="<?= SITE_URL ?>/pages/edit_animal.php?id=<?= $a['id'] ?>"
                           class="btn btn-sm btn-warning flex-fill text-center">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= SITE_URL ?>/pages/delete_animal.php?id=<?= $a['id'] ?>"
                           class="btn btn-sm btn-danger flex-fill text-center btn-delete"
                           data-name="<?= htmlspecialchars($a['name']) ?>">
                            <i class="bi bi-trash"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
// Kartlardaki emojileri tür adına göre doldur
document.querySelectorAll('[data-species]').forEach(el => {
    el.textContent = getAnimalEmoji(el.dataset.species);
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
