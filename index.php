<?php
$page_title = 'Ürünler';
require_once 'config.php';
require_once 'db.php';
require_once 'includes/header.php';

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>

<div class="glass-card">
    <h1 style="font-size: 2rem; margin-bottom: 0.5rem; color: var(--text-primary);">Ürünlerimiz</h1>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">Tüm ürünlerimizi keşfedin</p>

    <?php if (empty($products)): ?>
        <p style="color: var(--text-muted); text-align: center; padding: 2rem;">Henüz ürün bulunmamaktadır.</p>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         class="product-image"
                         onerror="this.src='https://via.placeholder.com/400x300/101826/57F287?text=No+Image'">
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="product-price"><?php echo number_format($product['price'], 2); ?> ₺</div>
                        <button class="btn btn-primary btn-block" onclick="openProductModal(<?php echo $product['id']; ?>)">
                            Satın Al
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Product Detail Modal -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Ürün Detayları</h2>
            <button class="modal-close" onclick="closeModal('productModal')">&times;</button>
        </div>
        <div id="modalBody">
            <!-- Product details will be loaded here -->
        </div>
    </div>
</div>

<script>
    // Products data for modal
    const products = <?php echo json_encode($products, JSON_UNESCAPED_UNICODE); ?>;

    function openProductModal(productId) {
        const product = products.find(p => p.id == productId);
        if (!product) return;

        const modalBody = document.getElementById('modalBody');
        modalBody.innerHTML = `
            <img src="${product.image_url}" 
                 alt="${product.name}" 
                 style="width: 100%; height: 250px; object-fit: cover; border-radius: var(--border-radius); margin-bottom: 1.5rem;"
                 onerror="this.src='https://via.placeholder.com/400x300/101826/57F287?text=No+Image'">
            <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: var(--text-primary);">${product.name}</h3>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.8;">${product.description}</p>
            <div style="font-size: 2rem; font-weight: 700; color: var(--accent); margin-bottom: 1.5rem; text-shadow: 0 0 10px rgba(87, 242, 135, 0.3);">
                ${parseFloat(product.price).toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2})} ₺
            </div>
            <a href="<?php echo BASE_URL; ?>/pay.php?id=${product.id}" class="btn btn-primary btn-block" style="text-decoration: none;">
                Onayla
            </a>
        `;
        openModal('productModal');
    }
</script>

<?php require_once 'includes/footer.php'; ?>

