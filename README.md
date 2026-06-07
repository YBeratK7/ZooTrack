# ZooTrack Hayvanat Bahçesi Yönetim Sistemi 

Web Tabanlı Programlama dersi dönem projesi için hazırladığımız Hayvanat Bahçesi Takip Sistemi. Projenin arka planında herhangi bir framework veya hazır kütüphane kullanmadık, baştan sona yalın PHP ve MySQL ile geliştirdik.

##  Proje Linkleri
* **Canlı Site:** [http://95.130.171.20/~st24360859028](http://95.130.171.20/~st24360859028)
* **Tanıtım Videosu:** [(https://www.youtube.com/watch?v=GbaSw7hOhxk)]

##  Ekran Görüntüleri



<img width="1862" height="921" alt="image" src="https://github.com/user-attachments/assets/11d19739-aa9b-4bb6-abc4-bf67a9cd38ee" />

<img width="1842" height="862" alt="image" src="https://github.com/user-attachments/assets/b44e8937-0077-4406-8983-b365a9795c39" />


##  Projede Neler Var? (İstenen Kriterler)
Proje dökümanında belirtilen kurallara uygun olarak sistemi şu şekilde kurguladım:
* **Şifre Güvenliği:** Kullanıcı şifrelerini veritabanına açık bir şekilde kaydetmek yerine `password_hash()` fonksiyonu ile şifreledik.
* **Oturum Yönetimi:** Kullanıcı giriş ve yetki kontrollerini çerezler ile değil, güvenli bir şekilde `Session` kullanarak sağladık.
* **Veritabanı İşlemleri (CRUD):** - Sisteme yeni hayvan/personel bilgisi eklenebiliyor.
  - Eklenen veriler dinamik olarak ekranda listeleniyor.
  - Kayıtlar üzerinde düzenleme yapılabiliyor.
  - İstenen kayıtlar sistemden silinebiliyor.
* **Canlı Sunucu:** Projeyi yerel bilgisayarımızdan canlı hosting alanına taşıdık. GitHub reposuna yüklerken de  güvenlik kuralı gereği config dosyamızdaki canlı veritabanı şifrelerini sansürledik

##  Kullanılan Diller ve Araçlar
* **Backend:** PHP, MySQL (PDO)
* **Frontend:** HTML, CSS, Bootstrap
