# Otel Personel Takip Sistemi

Laravel 10 kullanılarak geliştirilmiş eksiksiz çalışan bir otel personel takip sistemi.

## 🎯 Özellikler

### Admin Paneli
- **Kullanıcı Yönetimi**: Çalışan ekleme, düzenleme, silme
- **Checklist Oluşturma**: Başlık + birden fazla görev maddesi
- **Görev Atama**: Checklist'leri çalışanlara belirli günler için atama
- **QR Kod Üretimi**: Lokasyon bazlı QR kod oluşturma (entry, exit, task)
- **PNG İndirme**: QR kodları PNG formatında indirme
- **Raporlama**: Tüm kullanıcıların geçmiş görevleri ve QR kayıtları

### Çalışan Paneli
- **QR Kod Okuma**: Mobil tarayıcıdan kamera ile QR kod okutma
- **Günlük Görevler**: Kendisine atanmış günlük checklist'leri görme
- **Görev Tamamlama**: Her maddeyi tek tek işaretleme
- **Geçmiş Görüntüleme**: Görev ve QR tarama geçmişi

## 🗂️ Veritabanı Yapısı

### Tablolar
- **users**: Kullanıcı bilgileri (ad, soyad, e-posta, şifre, telefon, acil durum bilgileri, adres, rol)
- **checklists**: Checklist başlıkları ve oluşturan kişi
- **checklist_items**: Checklist maddeleri
- **assignments**: Kullanıcılara atanan görevler
- **submissions**: Tamamlanan görev kayıtları
- **qr_codes**: QR kod bilgileri
- **qr_scans**: QR kod tarama kayıtları

## 🚀 Kurulum

### Gereksinimler
- PHP 8.1+
- Composer
- Laravel 10

### Adımlar

1. **Projeyi klonlayın**
```bash
git clone <repository-url>
cd otel-takip-sistemi
```

2. **Bağımlılıkları yükleyin**
```bash
composer install
```

3. **Ortam dosyasını kopyalayın**
```bash
cp .env.example .env
```

4. **Uygulama anahtarını oluşturun**
```bash
php artisan key:generate
```

5. **Veritabanını oluşturun**
```bash
php artisan migrate:fresh --seed
```

6. **Storage linkini oluşturun**
```bash
php artisan storage:link
```

7. **Sunucuyu başlatın**
```bash
php artisan serve
```

## 👥 Varsayılan Kullanıcılar

Seeder ile oluşturulan varsayılan kullanıcılar:

### Admin
- **E-posta**: admin@otel.com
- **Şifre**: password
- **Rol**: Admin

### Çalışan
- **E-posta**: employee@otel.com
- **Şifre**: password
- **Rol**: Çalışan

## 🔐 Güvenlik

- **Auth Korumalı Route'lar**: Tüm sayfalar giriş gerektirir
- **CSRF Koruması**: Tüm formlar CSRF token ile korunur
- **Role-Based Access**: Admin ve çalışan alanları ayrılmış
- **Middleware Koruması**: AdminMiddleware ve EmployeeMiddleware
- **Validasyon**: Tüm form girişleri validasyon ile kontrol edilir

## 📱 QR Kod Sistemi

### QR Kod Tipleri
- **Entry**: Giriş kaydı
- **Exit**: Çıkış kaydı  
- **Task**: Görev tamamlama kaydı

### QR Kod Okuma
- HTML5-QRCode kütüphanesi kullanılır
- Mobil tarayıcıdan kamera erişimi
- Otomatik kayıt sistemi
- Günlük tekrar tarama engelleme

## 🎨 Arayüz

- **Bootstrap 5**: Modern ve responsive tasarım
- **Font Awesome**: İkonlar
- **Türkçe Arayüz**: Tam Türkçe dil desteği
- **Mobil Uyumlu**: Tüm cihazlarda çalışır

## 📁 Dizin Yapısı

```
resources/views/
├── layouts/
│   └── app.blade.php          # Ana şablon
├── admin/
│   ├── dashboard.blade.php     # Admin dashboard
│   ├── users/                  # Kullanıcı yönetimi
│   ├── checklists/            # Checklist yönetimi
│   ├── qr-codes/              # QR kod yönetimi
│   └── submissions/           # Görev raporları
└── employee/
    ├── dashboard.blade.php     # Çalışan dashboard
    ├── qr-scanner.blade.php   # QR kod okuyucu
    ├── today-tasks.blade.php  # Bugünkü görevler
    └── task-history.blade.php # Görev geçmişi
```

## 🔧 Kullanım

### Admin İşlemleri

1. **Kullanıcı Ekleme**
   - Admin paneline giriş yapın
   - "Kullanıcılar" menüsünden "Yeni Kullanıcı" seçin
   - Gerekli bilgileri doldurun

2. **Checklist Oluşturma**
   - "Görevler" menüsünden "Yeni Checklist" seçin
   - Başlık ve maddeleri ekleyin

3. **QR Kod Oluşturma**
   - "QR Kodlar" menüsünden "Yeni QR Kod" seçin
   - Lokasyon ve tip belirleyin
   - PNG formatında indirin

4. **Görev Atama**
   - "Görevler" menüsünden "Görev Ata" seçin
   - Checklist ve çalışanları seçin
   - Tarih belirleyin

### Çalışan İşlemleri

1. **QR Kod Okutma**
   - "QR Kod Oku" sayfasına gidin
   - Kamerayı başlatın
   - QR kodu okutun

2. **Görev Tamamlama**
   - "Bugünkü Görevler" sayfasına gidin
   - Maddeleri işaretleyin
   - "Görevleri Kaydet" butonuna basın

## 🛠️ Teknik Detaylar

### Kullanılan Paketler
- **Laravel Breeze**: Kimlik doğrulama
- **Endroid QR Code**: QR kod üretimi
- **HTML5-QRCode**: QR kod okuma

### Middleware'ler
- **AdminMiddleware**: Admin erişim kontrolü
- **EmployeeMiddleware**: Çalışan erişim kontrolü

### Model İlişkileri
- User ↔ Checklist (created_by)
- User ↔ Assignment (user_id)
- User ↔ Submission (user_id)
- User ↔ QrScan (user_id)
- Checklist ↔ ChecklistItem (checklist_id)
- Assignment ↔ Submission (assignment_id)

## 📊 Raporlama

### Admin Raporları
- Toplam kullanıcı sayısı
- Atanmış görev sayısı
- QR tarama sayısı
- Son aktiviteler
- Kullanıcı geçmişleri

### Çalışan Raporları
- Kişisel görev geçmişi
- QR tarama geçmişi
- Günlük görev durumu

## 🔄 Güncellemeler

### v1.0.0
- Temel sistem kurulumu
- Admin ve çalışan panelleri
- QR kod sistemi
- Görev yönetimi
- Raporlama sistemi

## 📞 Destek

Herhangi bir sorun yaşarsanız:
1. GitHub Issues bölümünü kontrol edin
2. Yeni issue oluşturun
3. Detaylı hata açıklaması ekleyin

## 📄 Lisans

Bu proje MIT lisansı altında lisanslanmıştır.

---

**Geliştirici**: Laravel 10 Otel Takip Sistemi  
**Versiyon**: 1.0.0  
**Son Güncelleme**: 2025-07-31
