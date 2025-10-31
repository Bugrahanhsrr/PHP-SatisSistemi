
Buğrahan Hasarı

## Özellikler

### Önyüz
- **Ürün Listeleme** (`index.php`) - Glassmorphic kartlarla ürünlere göz atın
- **Ödeme Sayfası** (`pay.php`) - Siparişi IBAN/Papara ödeme bilgileriyle tamamlayın
- **Siparişlerim** (`siparislerim.php`) - Sipariş durumunu ve yönetici mesajlarını görüntüleyin

### Yönetici Paneli
- **Kontrol Paneli** - İstatistikler ve son siparişlere genel bakış
- **Ürün Yönetimi** - Ürün ekleme, düzenleme, silme
- **Sipariş Yönetimi** - Sipariş durumunu güncelleme ve müşterilere mesaj gönderme
- **Ayarlar** - Ödeme bilgilerini (IBAN, Papara) ve Telegram botunu yapılandırma

### Telegram Entegrasyonu
- Telegram'a otomatik sipariş bildirimleri
- Hızlı sipariş onayı/reddi için satır içi düğmeler
- Geri arama işlemi için webhook işleyicisi

## Kurulum

### 1. Veritabanı Kurulumu
1. `database.sql` dosyasını MySQL veritabanınıza aktarın
2. Veritabanı kimlik bilgilerini güncelleyin `db.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'product_system');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 2. Yapılandırma
- Gerekirse `config.php` dosyasındaki `BASE_URL` dosyasını güncelleyin
- Yönetici kimlik bilgilerini ayarlamak için `install.php` dosyasını çalıştırın
- Varsayılan yönetici kimlik bilgileri (hemen değiştirin):
- Kullanıcı adı: `admin`
- Şifre: `password` (değiştirmek için install.php dosyasını kullanın)

### 3. Telegram Bot Kurulumu (İsteğe bağlı)
1. Telegram'da [@BotFather](https://t.me/BotFather) kullanarak bir bot oluşturun
2. Bot token'ınızı alın
3. [@userinfobot](https://t.me/userinfobot) kullanarak Sohbet Kimliğinizi alın
4. Yönetici Paneli → Ayarlar'da yapılandırın
5. Webhook URL'sini ayarlayın: `https://yourdomain.com/admin/webhook.php`

## Dosya Yapısı
```
/
├── index.php                 # Product listing (frontend)
├── pay.php                   # Payment page
├── siparislerim.php          # User orders page
├── config.php                # Configuration
├── db.php                    # Database connection
├── database.sql              # Database schema
├── assets/
│   └── css/
│       └── style.css         # Main stylesheet
├── includes/
│   ├── header.php            # Frontend header
│   └── footer.php            # Frontend footer
├── functions/
│   └── telegram.php          # Telegram integration
└── admin/
    ├── login.php             # Admin login
    ├── dashboard.php         # Admin dashboard
    ├── products.php          # Product management
    ├── orders.php            # Order management
    ├── settings.php          # System settings
    ├── webhook.php           # Telegram webhook handler
    ├── logout.php            # Logout
    └── includes/
        ├── header.php        # Admin header
        └── footer.php        # Admin footer
```

## Kullanım

### Ürün Ekleme
1. Yönetici paneline giriş yapın (`/admin/login.php`)
2. Ürünler'e gidin
3. "Yeni Ürün" butonuna tıklayın
4. Ürün bilgilerini doldurun ve kaydedin

### Siparişler İşleniyor
1. Yeni siparişler Kontrol Paneli ve Siparişler sayfasında görünür
2. Durumu güncellemek veya mesaj göndermek için "Yönetim" butonuna tıklayın
3. Siparişler Telegram botu aracılığıyla onaylanabilir/reddedilebilir (yapılandırılmışsa)

### Telegram Bildirimleri
Yeni bir sipariş verildiğinde:
- Bot, yapılandırılmış Telegram sohbetine bildirim gönderir
- Yönetici "Onayla ✅" veya "Reddet ❌" butonlarına tıklayabilir
- Sipariş durumu veritabanında otomatik olarak güncellenir

## Tasarım Sistemi

- **Tema**: Neo Glass Koyu Tema
- **Arka Plan**: `#0f1720`
- **Kart**: `#101826`
- **Vurgu**: `#57F287`
- **Metin**: `#e6eef6`
- **Sessiz**: `#9aa6b2`
- **Yazı Tipi**: Inter, sans-serif
- **Kenarlık Yarıçapı**: 14px

## Güvenlik Notları

- Parolalar `password_hash()` kullanılarak şifrelenir
- PDO ile hazırlanan ifadeler SQL enjeksiyonunu engeller
- Yönetici paneli için oturum tabanlı kimlik doğrulama
- Giriş doğrulama ve temizleme

## Gereksinimler

- PHP 7.4+
- MySQL 5.7+
- cURL uzantısı (Telegram için)
- Apache/Nginx web sunucusu

## Lisans

Bu proje açık kaynaklıdır ve kullanıma açıktır.


