<?php
$pageTitle = 'Hayvan Detayı';
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

$statusMap = [
    'Sağlıklı'    => ['class'=>'status-healthy',    'icon'=>'✅', 'bg'=>'#d4edda'],
    'Tedavide'    => ['class'=>'status-treatment',  'icon'=>'🏥', 'bg'=>'#fff3cd'],
    'Karantinada' => ['class'=>'status-quarantine', 'icon'=>'⚠️', 'bg'=>'#f8d7da'],
    'Vefat Etti'  => ['class'=>'status-deceased',   'icon'=>'🪦', 'bg'=>'#e2e3e5'],
];
$st = $statusMap[$record['status']] ?? $statusMap['Sağlıklı'];

// Yaş hesapla
$age = '';
if (!empty($record['birth_date'])) {
    $born = new DateTime($record['birth_date']);
    $now  = new DateTime();
    $diff = $now->diff($born);
    $age  = $diff->y > 0 ? $diff->y . ' yıl' : $diff->m . ' ay';
}
?>

<div class="page-header">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= SITE_URL ?>/pages/animals.php" class="btn btn-sm btn-outline-light">
                <i class="bi bi-arrow-left me-1"></i>Geri
            </a>
            <div>
                <h1 id="page-title-emoji">🐾 <?= sanitize($record['name']) ?></h1>
                <p><?= sanitize($record['species']) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 pb-4">
    <div class="row g-4">

        <!-- Sol: Detaylar -->
        <div class="col-lg-8">
            <div class="zoo-card">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div style="font-size:5rem;line-height:1;" id="detail-emoji">🐾</div>
                    <div>
                        <h2 class="mb-1" style="font-family:'Syne',sans-serif"><?= sanitize($record['name']) ?></h2>
                        <p class="text-muted mb-1"><?= sanitize($record['species']) ?></p>
                        <span class="status-badge <?= $st['class'] ?>" style="font-size:0.9rem;padding:0.35rem 1rem;">
                            <?= $st['icon'] ?> <?= $record['status'] ?>
                        </span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3" style="background:var(--zoo-cream)">
                            <div class="small text-muted mb-1"><i class="bi bi-gender-ambiguous me-1"></i>Cinsiyet</div>
                            <div class="fw-semibold"><?= sanitize($record['gender']) ?></div>
                        </div>
                    </div>
                    <?php if ($record['birth_date']): ?>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3" style="background:var(--zoo-cream)">
                            <div class="small text-muted mb-1"><i class="bi bi-calendar3 me-1"></i>Doğum Tarihi</div>
                            <div class="fw-semibold"><?= date('d.m.Y', strtotime($record['birth_date'])) ?>
                                <?php if ($age): ?><span class="text-muted small">(<?= $age ?>)</span><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($record['weight_kg']): ?>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3" style="background:var(--zoo-cream)">
                            <div class="small text-muted mb-1"><i class="bi bi-speedometer me-1"></i>Ağırlık</div>
                            <div class="fw-semibold"><?= number_format($record['weight_kg'], 1) ?> kg</div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($record['enclosure']): ?>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3" style="background:var(--zoo-cream)">
                            <div class="small text-muted mb-1"><i class="bi bi-house me-1"></i>Barınak</div>
                            <div class="fw-semibold"><?= sanitize($record['enclosure']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($record['diet']): ?>
                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:var(--zoo-cream)">
                            <div class="small text-muted mb-1"><i class="bi bi-egg-fried me-1"></i>Beslenme</div>
                            <div class="fw-semibold"><?= sanitize($record['diet']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($record['notes']): ?>
                    <div class="col-12">
                        <div class="p-3 rounded-3 border">
                            <div class="small text-muted mb-1"><i class="bi bi-journal-text me-1"></i>Notlar</div>
                            <div><?= nl2br(sanitize($record['notes'])) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sağ: Meta & Eylemler -->
        <div class="col-lg-4">
            <div class="zoo-card mb-3">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-1"></i>Kayıt Bilgisi</h6>
                <div class="small text-muted">
                    <p class="mb-1"><i class="bi bi-calendar-plus me-1"></i>
                        Eklendi: <strong><?= date('d.m.Y H:i', strtotime($record['created_at'])) ?></strong>
                    </p>
                    <p class="mb-0"><i class="bi bi-pencil me-1"></i>
                        Güncellendi: <strong><?= date('d.m.Y H:i', strtotime($record['updated_at'])) ?></strong>
                    </p>
                </div>
            </div>

            <?php if ($animal->canEdit($id, $user['id'], $user['role'])): ?>
            <div class="zoo-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-gear me-1"></i>Eylemler</h6>
                <div class="d-grid gap-2">
                    <a href="<?= SITE_URL ?>/pages/edit_animal.php?id=<?= $id ?>"
                       class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Düzenle
                    </a>
                    <a href="<?= SITE_URL ?>/pages/delete_animal.php?id=<?= $id ?>"
                       class="btn btn-danger btn-delete" data-name="<?= htmlspecialchars($record['name']) ?>">
                        <i class="bi bi-trash me-2"></i>Sil
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const species = <?= json_encode($record['species']) ?>;
    const emoji = getAnimalEmoji(species);
    document.getElementById('detail-emoji').textContent = emoji;
    const titleEl = document.getElementById('page-title-emoji');
    if (titleEl) {
        titleEl.innerHTML = emoji + ' <?= addslashes(sanitize($record['name'])) ?>';
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
