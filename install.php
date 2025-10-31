<?php
/**
 * Installation Script
 * Run this once to set up the admin password
 */
require_once 'config.php';
require_once 'db.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $message = 'Lütfen kullanıcı adı ve şifre girin.';
        $message_type = 'error';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("UPDATE admin SET username = ?, password = ? WHERE id = 1");
            $stmt->execute([$username, $hashed_password]);
            $message = 'Admin hesabı başarıyla oluşturuldu! Artık giriş yapabilirsiniz.';
            $message_type = 'success';
        } catch (Exception $e) {
            $message = 'Hata: ' . $e->getMessage();
            $message_type = 'error';
        }
    }
}

// Check if admin already exists
$stmt = $pdo->query("SELECT username FROM admin LIMIT 1");
$existing_admin = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurulum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 500px; margin-top: 10vh;">
        <div class="glass-card">
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem; color: var(--text-primary); text-align: center;">Kurulum</h1>
            <p style="color: var(--text-muted); text-align: center; margin-bottom: 2rem;">Admin hesabını oluşturun</p>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></div>
                
                <?php if ($message_type === 'success'): ?>
                    <div style="text-align: center; margin-top: 1.5rem;">
                        <a href="admin/login.php" class="btn btn-primary">Admin Paneline Git</a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <?php if ($existing_admin): ?>
                    <div class="alert alert-info">
                        Admin hesabı zaten mevcut. Yeni bir admin hesabı oluşturmak için formu doldurun.
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Kullanıcı Adı</label>
                        <input type="text" id="username" name="username" class="form-control" 
                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                               required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Şifre</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Oluştur</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

