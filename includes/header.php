<?php
require_once __DIR__ . '/../includes/auth.php';
startSession();
$user = getCurrentUser();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? sanitize($pageTitle) . ' — ' : '' ?>ZooTrack</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= SITE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php if ($user): ?>
<nav class="navbar navbar-expand-lg navbar-dark zoo-navbar">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= SITE_URL ?>/pages/dashboard.php">
            <span class="brand-icon">🦁</span>
            <span class="brand-text">ZooTrack</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>" href="<?= SITE_URL ?>/pages/dashboard.php">
                        <i class="bi bi-speedometer2 me-1"></i>Panel
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'animals.php' ? 'active' : '' ?>" href="<?= SITE_URL ?>/pages/animals.php">
                        <i class="bi bi-grid-3x3-gap me-1"></i>Hayvanlar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'add_animal.php' ? 'active' : '' ?>" href="<?= SITE_URL ?>/pages/add_animal.php">
                        <i class="bi bi-plus-circle me-1"></i>Hayvan Ekle
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <div class="user-pill">
                    <i class="bi bi-person-circle"></i>
                    <span><?= sanitize($user['full_name']) ?></span>
                    <?php if ($user['role'] === 'admin'): ?>
                        <span class="badge bg-warning text-dark ms-1">Admin</span>
                    <?php endif; ?>
                </div>
                <a href="<?= SITE_URL ?>/pages/logout.php" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-box-arrow-right me-1"></i>Çıkış
                </a>
            </div>
        </div>
    </div>
</nav>
<?php endif; ?>

<main class="<?= $user ? 'with-nav' : '' ?>">
