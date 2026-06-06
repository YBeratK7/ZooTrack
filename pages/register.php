<?php
require_once __DIR__ . '/../includes/auth.php';
startSession();

if (isLoggedIn()) {
    header('Location: ' . SITE_URL . '/pages/dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $fullName  = trim($_POST['full_name'] ?? '');
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (empty($username) || empty($email) || empty($fullName) || empty($password)) {
        $error = 'Tüm alanlar zorunludur.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Geçerli bir e-posta adresi girin.';
    } elseif (strlen($password) < 6) {
        $error = 'Şifre en az 6 karakter olmalıdır.';
    } elseif ($password !== $password2) {
        $error = 'Şifreler eşleşmiyor.';
    } else {
        $result = registerUser($username, $email, $password, $fullName);
        if ($result === true) {
            $success = 'Kayıt başarılı! Şimdi giriş yapabilirsiniz.';
        } else {
            $error = $result;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol — ZooTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="<?= SITE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card" style="max-width:500px">
        <div class="auth-logo">
            <span class="logo-emoji">🦁</span>
            <h1>ZooTrack</h1>
            <p>Yeni hesap oluştur</p>
        </div>

        <?php if ($error): ?>
            <div class="alert zoo-alert alert-danger auto-dismiss mb-3">
                <i class="bi bi-exclamation-circle me-2"></i><?= sanitize($error) ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert zoo-alert alert-success mb-3">
                <i class="bi bi-check-circle me-2"></i><?= sanitize($success) ?>
                <div class="mt-2">
                    <a href="<?= SITE_URL ?>/pages/login.php" class="btn btn-sm btn-success">Giriş Yap</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label" for="full_name">Ad Soyad</label>
                <input type="text" class="form-control" id="full_name" name="full_name"
                       value="<?= sanitize($_POST['full_name'] ?? '') ?>"
                       placeholder="Ahmet Yılmaz" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label" for="username">Kullanıcı Adı</label>
                    <input type="text" class="form-control" id="username" name="username"
                           value="<?= sanitize($_POST['username'] ?? '') ?>"
                           placeholder="ahmet_yl" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="email">E-posta</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?= sanitize($_POST['email'] ?? '') ?>"
                           placeholder="ahmet@zoo.com" required>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <label class="form-label" for="password">Şifre</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Min. 6 karakter" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="password2">Şifre Tekrar</label>
                    <input type="password" class="form-control" id="password2" name="password2"
                           placeholder="Tekrar girin" required>
                </div>
            </div>
            <button type="submit" class="btn btn-zoo-primary w-100 py-2">
                <i class="bi bi-person-plus me-2"></i>Kayıt Ol
            </button>
        </form>
        <?php endif; ?>

        <hr class="my-3">
        <p class="text-center mb-0 small text-muted">
            Zaten hesabınız var mı?
            <a href="<?= SITE_URL ?>/pages/login.php" class="fw-semibold text-decoration-none" style="color: var(--zoo-amber)">Giriş Yap</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body>
</html>
