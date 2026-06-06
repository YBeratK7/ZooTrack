<?php
require_once __DIR__ . '/../includes/auth.php';
startSession();

if (isLoggedIn()) {
    header('Location: ' . SITE_URL . '/pages/dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Kullanıcı adı ve şifre zorunludur.';
    } elseif (!loginUser($username, $password)) {
        $error = 'Geçersiz kullanıcı adı veya şifre.';
    } else {
        header('Location: ' . SITE_URL . '/pages/dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş — ZooTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="<?= SITE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <span class="logo-emoji">🦁</span>
            <h1>ZooTrack</h1>
            <p>Hayvanat Bahçesi Hayvan Takip Sistemi</p>
        </div>

        <?php if ($error): ?>
            <div class="alert zoo-alert alert-danger auto-dismiss mb-3">
                <i class="bi bi-exclamation-circle me-2"></i><?= sanitize($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label" for="username">Kullanıcı Adı veya E-posta</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0"
                           id="username" name="username"
                           value="<?= sanitize($_POST['username'] ?? '') ?>"
                           placeholder="kullanici_adi" autocomplete="username" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label" for="password">Şifre</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" class="form-control border-start-0 ps-0"
                           id="password" name="password"
                           placeholder="••••••••" autocomplete="current-password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-zoo-primary w-100 py-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>Giriş Yap
            </button>
        </form>

        <hr class="my-3">
        <p class="text-center mb-0 small text-muted">
            Hesabınız yok mu?
            <a href="<?= SITE_URL ?>/pages/register.php" class="fw-semibold text-decoration-none" style="color: var(--zoo-amber)">Kayıt Ol</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body>
</html>
