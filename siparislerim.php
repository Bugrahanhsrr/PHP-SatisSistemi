<?php
$page_title = 'Siparişlerim';
require_once 'config.php';
require_once 'db.php';
require_once 'includes/header.php';

// Get phone from session or query parameter or form
$phone = $_GET['phone'] ?? $_POST['phone'] ?? '';

// Fetch user orders
$stmt = $pdo->prepare("
    SELECT o.*, p.name as product_name, p.image_url, u.name, u.surname 
    FROM orders o
    JOIN products p ON o.product_id = p.id
    JOIN users u ON o.user_id = u.id
    WHERE u.phone = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$phone]);
$orders = $stmt->fetchAll();
?>

<div class="glass-card">
    <h1 style="font-size: 2rem; margin-bottom: 0.5rem; color: var(--text-primary);">Siparişlerim</h1>
    
    <?php if (empty($phone)): ?>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">Siparişlerinizi görüntülemek için telefon numaranızı girin.</p>
        <form method="POST" action="" style="max-width: 400px;">
            <div class="form-group">
                <label for="phone">Telefon Numarası</label>
                <input type="tel" id="phone" name="phone" class="form-control" 
                       placeholder="0555 123 45 67" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Siparişlerimi Göster</button>
        </form>
    <?php else: ?>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">Telefon: <?php echo htmlspecialchars($phone); ?></p>
        
        <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            Henüz siparişiniz bulunmamaktadır.
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-item">
                    <div class="order-header">
                        <div class="order-info">
                            <h3 style="color: var(--text-primary); margin-bottom: 0.5rem;">
                                <?php echo htmlspecialchars($order['product_name']); ?>
                            </h3>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">
                                Sipariş No: #<?php echo $order['id']; ?>
                            </p>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">
                                Tarih: <?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?>
                            </p>
                            <p style="color: var(--accent); font-size: 1.2rem; font-weight: 600; margin-top: 0.5rem;">
                                <?php echo number_format($order['amount'], 2); ?> ₺
                            </p>
                        </div>
                        <div>
                            <span class="order-status status-<?php echo strtolower(str_replace(' ', '', $order['status'])); ?>">
                                <?php echo htmlspecialchars($order['status']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <?php if ($order['admin_message']): ?>
                        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                            <button class="btn btn-secondary" onclick="openMessageModal('<?php echo $order['id']; ?>', `<?php echo addslashes($order['admin_message']); ?>`)">
                                Durum Mesajı
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Message Modal -->
<div id="messageModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Durum Mesajı</h2>
            <button class="modal-close" onclick="closeModal('messageModal')">&times;</button>
        </div>
        <div id="messageBody">
            <!-- Message will be loaded here -->
        </div>
    </div>
</div>

<script>
    function openMessageModal(orderId, message) {
        const messageBody = document.getElementById('messageBody');
        messageBody.innerHTML = `
            <div style="background: rgba(255, 255, 255, 0.05); border-radius: var(--border-radius); padding: 1.5rem; margin-bottom: 1rem;">
                <p style="color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem;">Sipariş No: #${orderId}</p>
            </div>
            <p style="color: var(--text-primary); line-height: 1.8; white-space: pre-wrap;">${message}</p>
        `;
        openModal('messageModal');
    }
</script>

<?php require_once 'includes/footer.php'; ?>

