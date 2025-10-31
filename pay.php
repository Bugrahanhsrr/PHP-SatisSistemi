<?php
$page_title = 'Ödeme';
require_once 'config.php';
require_once 'db.php';

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$product_id = intval($_GET['id']);

// Fetch product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit;
}

// Fetch payment info from admin settings
$stmt = $pdo->query("SELECT iban, papara FROM admin LIMIT 1");
$payment_info = $stmt->fetch();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    if (empty($name) || empty($surname) || empty($phone)) {
        $error = 'Lütfen tüm alanları doldurun.';
    } else {
        try {
            $pdo->beginTransaction();
            
            // Create or get user
            $stmt = $pdo->prepare("SELECT id FROM users WHERE phone = ?");
            $stmt->execute([$phone]);
            $user = $stmt->fetch();
            
            if ($user) {
                $user_id = $user['id'];
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (name, surname, phone) VALUES (?, ?, ?)");
                $stmt->execute([$name, $surname, $phone]);
                $user_id = $pdo->lastInsertId();
            }
            
            // Create order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_id, amount, status) VALUES (?, ?, ?, 'Bekliyor')");
            $stmt->execute([$user_id, $product_id, $product['price']]);
            $order_id = $pdo->lastInsertId();
            
            // Send Telegram notification
            require_once 'functions/telegram.php';
            sendOrderNotification($pdo, $order_id);
            
            $pdo->commit();
            
            // Store order ID in session for confirmation
            $_SESSION['order_id'] = $order_id;
            header('Location: siparislerim.php');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Sipariş oluşturulurken bir hata oluştu: ' . $e->getMessage();
        }
    }
}

require_once 'includes/header.php';
?>

<div class="glass-card" style="max-width: 600px; margin: 2rem auto;">
    <h1 style="font-size: 2rem; margin-bottom: 1rem; color: var(--text-primary);">Ödeme Bilgileri</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <div style="background: rgba(87, 242, 135, 0.1); border: 1px solid rgba(87, 242, 135, 0.3); border-radius: var(--border-radius); padding: 1.5rem; margin-bottom: 2rem;">
        <h3 style="color: var(--accent); margin-bottom: 1rem; font-size: 1.2rem;">Ödenecek Tutar</h3>
        <div style="font-size: 2rem; font-weight: 700; color: var(--accent); margin-bottom: 1rem;">
            <?php echo number_format($product['price'], 2); ?> ₺
        </div>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Ürün: <?php echo htmlspecialchars($product['name']); ?></p>
    </div>
    
    <?php if ($payment_info && ($payment_info['iban'] || $payment_info['papara'])): ?>
        <div style="background: rgba(255, 255, 255, 0.05); border-radius: var(--border-radius); padding: 1.5rem; margin-bottom: 2rem;">
            <h3 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.1rem;">Ödeme Bilgileri</h3>
            <?php if ($payment_info['iban']): ?>
                <p style="color: var(--text-muted); margin-bottom: 0.5rem;">
                    <strong style="color: var(--text-primary);">IBAN:</strong> 
                    <span style="font-family: monospace;"><?php echo htmlspecialchars($payment_info['iban']); ?></span>
                </p>
            <?php endif; ?>
            <?php if ($payment_info['papara']): ?>
                <p style="color: var(--text-muted);">
                    <strong style="color: var(--text-primary);">Papara:</strong> 
                    <span style="font-family: monospace;"><?php echo htmlspecialchars($payment_info['papara']); ?></span>
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Ad</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                   required>
        </div>
        
        <div class="form-group">
            <label for="surname">Soyad</label>
            <input type="text" id="surname" name="surname" class="form-control" 
                   value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>" 
                   required>
        </div>
        
        <div class="form-group">
            <label for="phone">Telefon</label>
            <input type="tel" id="phone" name="phone" class="form-control" 
                   placeholder="0555 123 45 67"
                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" 
                   required>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">
            Ödemeyi Tamamladım
        </button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>

