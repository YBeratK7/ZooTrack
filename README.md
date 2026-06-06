# ZooTrack Hayvanat Bahçesi Yönetim Sistemi 

Web Tabanlı Programlama dersi dönem projesi için hazırladığımız Hayvanat Bahçesi Takip Sistemi. Projenin arka planında herhangi bir framework veya hazır kütüphane kullanmadık, baştan sona yalın PHP ve MySQL ile geliştirdik.

## 🔗 Proje Linkleri
* **Canlı Site:** [http://95.130.171.20/~st24360859028](http://95.130.171.20/~st24360859028)
* **Tanıtım Videosu:** [Buraya videonun linkini yapıştır]

## 📷 Ekran Görüntüleri



1. Ekran Görüntüsü: 
2. Ekran Görüntüsü: 

## ⚙️ Projede Neler Var? (İstenen Kriterler)
Proje dökümanında belirtilen kurallara uygun olarak sistemi şu şekilde kurguladım:
* **Şifre Güvenliği:** Kullanıcı şifrelerini veritabanına açık bir şekilde kaydetmek yerine `password_hash()` fonksiyonu ile şifreledim.
* **Oturum Yönetimi:** Kullanıcı giriş ve yetki kontrollerini çerezler ile değil, güvenli bir şekilde `Session` kullanarak sağladım.
* **Veritabanı İşlemleri (CRUD):** - Sisteme yeni hayvan/personel bilgisi eklenebiliyor (Create).
  - Eklenen veriler dinamik olarak ekranda listeleniyor.
  - Kayıtlar üzerinde düzenleme yapılabiliyor.
  - İstenen kayıtlar sistemden silinebiliyor.
* **Canlı Sunucu:** Projeyi yerel bilgisayarımızdan canlı hosting alanına taşıdık. GitHub reposuna yüklerken de güvenlik kuralı gereği config dosyamızdaki canlı veritabanı şifrelerini sansürledik.

## 💻 Kullanılan Diller ve Araçlar
* **Backend:** PHP, MySQL (PDO)
* **Frontend:** HTML, CSS, Bootstrap
