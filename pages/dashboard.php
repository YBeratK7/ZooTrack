<?php
$pageTitle = 'Panel';
require_once __DIR__ . '/../includes/header.php';
requireLogin();
require_once __DIR__ . '/../includes/Animal.php';

$animal = new Animal();
$stats  = $animal->getStats();
$user   = getCurrentUser();
$recent = $animal->getAll($user['id'], $user['role']);
$recent = array_slice($recent, 0, 5); // Son 5 kayıt
?>

<div class="page-header">
    <div class="container-fluid px-4">
        <h1><i class="bi bi-speedometer2 me-2"></i>Hoş Geldiniz, <?= sanitize($user['full_name']) ?>!</h1>
        <p>Hayvanat bahçesi genel durumuna genel bakış</p>
    </div>
</div>

<div class="container-fluid px-4 pb-4">

    <!-- İstatistik Kartları -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="--accent: #c8860a">
                <span class="stat-icon">🐾</span>
                <div class="stat-value"><?= (int)$stats['total'] ?></div>
                <div class="stat-label">Toplam Hayvan</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="--accent: #28a745">
                <span class="stat-icon">💚</span>
                <div class="stat-value"><?= (int)$stats['healthy'] ?></div>
                <div class="stat-label">Sağlıklı</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="--accent: #dc3545">
                <span class="stat-icon">🏥</span>
                <div class="stat-value"><?= (int)$stats['treatment'] ?></div>
                <div class="stat-label">Tedavide / Karantina</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="--accent: #6b7c5c">
                <span class="stat-icon">🔬</span>
                <div class="stat-value"><?= (int)$stats['species'] ?></div>
                <div class="stat-label">Farklı Tür</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Son Eklenen Hayvanlar -->
        <div class="col-lg-8">
            <div class="zoo-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>Son Eklenen Hayvanlar</h5>
                    <a href="<?= SITE_URL ?>/pages/animals.php" class="btn btn-sm btn-zoo-outline">Tümünü Gör</a>
                </div>

                <?php if (empty($recent)): ?>
                    <div class="empty-state py-3">
                        <span class="empty-icon">🦒</span>
                        <p class="mb-2">Henüz hayvan eklenmemiş.</p>
                        <a href="<?= SITE_URL ?>/pages/add_animal.php" class="btn btn-zoo-primary btn-sm">İlk Hayvanı Ekle</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table zoo-table mb-0">
                            <thead>
                                <tr>
                                    <th>Hayvan</th>
                                    <th>Tür</th>
                                    <th>Durum</th>
                                    <th>Barınak</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($recent as $a): ?>
                                <?php
                                $statusMap = [
                                    'Sağlıklı'    => ['class'=>'status-healthy',    'icon'=>'✅'],
                                    'Tedavide'    => ['class'=>'status-treatment',  'icon'=>'🏥'],
                                    'Karantinada' => ['class'=>'status-quarantine', 'icon'=>'⚠️'],
                                    'Vefat Etti'  => ['class'=>'status-deceased',   'icon'=>'🪦'],
                                ];
                                $st = $statusMap[$a['status']] ?? ['class'=>'status-healthy','icon'=>'✅'];
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?= SITE_URL ?>/pages/view_animal.php?id=<?= $a['id'] ?>"
                                           class="fw-semibold text-decoration-none" style="color: var(--zoo-dark)">
                                            <?= sanitize($a['name']) ?>
                                        </a>
                                    </td>
                                    <td class="text-muted"><?= sanitize($a['species']) ?></td>
                                    <td>
                                        <span class="status-badge <?= $st['class'] ?>">
                                            <?= $st['icon'] ?> <?= sanitize($a['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= sanitize($a['enclosure'] ?: '—') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Hızlı Eylemler -->
        <div class="col-lg-4">
            <div class="zoo-card h-100">
                <h5 class="mb-3"><i class="bi bi-lightning me-2 text-warning"></i>Hızlı Eylemler</h5>
                <div class="d-grid gap-2">
                    <a href="<?= SITE_URL ?>/pages/add_animal.php" class="btn btn-zoo-primary">
                        <i class="bi bi-plus-circle me-2"></i>Yeni Hayvan Ekle
                    </a>
                    <a href="<?= SITE_URL ?>/pages/animals.php" class="btn btn-zoo-outline">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Tüm Hayvanları Listele
                    </a>
                </div>

                <hr class="my-3">
                <div class="small text-muted">
                    <p class="mb-1"><i class="bi bi-info-circle me-1"></i> <strong>Sistem Durumu</strong></p>
                    <p class="mb-0">Tüm sistemler normal çalışıyor.</p>
                    <p class="mb-0 mt-1">Tarih: <strong><?= date('d.m.Y') ?></strong></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
