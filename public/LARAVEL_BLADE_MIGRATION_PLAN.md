# Laravel (Blade) ga toʻliq moslashish — Reja va tavsiyalar

Mazkur hujjat loyihani hozirgi holatidan toza Laravel Blade arxitekturasiga moslashtirish bo‘yicha bosqichma-bosqich reja va aniqlangan kamchiliklarni beradi. Maqsad: API-first arxitekturadan (React/Vite reja hujjatlari, backup fayllar) to‘liq Laravel Blade server-rendered ilovaga o‘tish.

## 1. Maqsad
- Barcha frontend funksiyalarni Blade shablonlarga o‘tkazish.
- Kod bazasini tozalash (backup/old fayllarni arxivlash), strukturani soddalashtirish.
- Modular, test qilingan va SEO-friendly server-side render qilingan yondashuv.

## 2. Joriy kamchiliklar (quick audit)
- Ko‘p zaxira (`storage/backups/...`) va frontend plan hujjatlari repo ichida aralash holatda — bu tartibsizlikka olib keladi.
- Baʼzi controller fayllarida takroriy yoki noto‘g‘ri joylashgan kod bloklari (sintaksis xatolari) mavjud.
- Resurslar (ProductResource/ProductCollection) va API response rollari aralashgan; Blade uchun yagona view modelga moslashtirish kerak.
- DB indekslari va sorov optimizatsiyasi baʼzi filtrlarda yo‘q yoki noaniq.
- Tarjima / localization: `language` va `language_id` aralash ishlatilgan — yagona yondashuv kerak.
- Caching kalitlari to‘liq xavfsiz emas (whole request.serialize ishlatilgan).

## 3. Umumiy yondashuv va printsiplar
- Single Responsibility: Controller faqat request → service → view oqimini boshqarsin.
- Viewlar Blade component va partiallarga bo‘linadi (layouts, components, product-card, pagination, filters).
- Eloquent relations va eager loading to‘g‘ri ishlatiladi; join va with aralashmasidan saqlanish.
- Assetlarni Laravel Mix yoki Vite (agar kerak bo‘lsa) orqali kompilyatsiya qiling, lekin Blade uchun minimal JS qoldiring.

## 4. Bosqichma-bosqich reja

### Bosqich A — Tayyorlov (1-2 kun)
- 1.1 Repo tozalash: `storage/backups/` ichidagi kerakli fayllarni arxivlash va kerakmaslarini alohida branch yoki zipga ko‘chirish.
- 1.2 `.env` va konfiguratsiyalarni tekshirish (APP_URL, DB, CACHE, QUEUE).
- 1.3 Composer paketlarini yangilash: `composer install` va autoload tekshiruvi.

Deliverable: Toza master branch yoki yangi `blade-migration` branch.

### Bosqich B — Maʼlumotlar qatlamini tayyorlash (1-2 kun)
- 2.1 Model va translation strukturani tekshirish (`Product`, `ProductTranslation`, `Category`, `CategoryTranslation`).
- 2.2 Migratsiyalarga indeks qoʻshish: `price`, `category_id`, `is_active`, `is_featured`.
- 2.3 Seederlar orqali demo data.

Deliverable: migration + seeder fayllari.

### Bosqich C — Controllerlar va Service qatlam (2-3 kun)
- 3.1 Har bir API controller uchun moslangan Blade controller yarating yoki mavjud controllerlarni refactor qiling (masalan `ProductController`): server-side filtering, pagination va view qaytarishi.
- 3.2 Business logicni service classlarga ajrating (`App\Services\ProductService`) — test yozishni osonlashtirish uchun.
- 3.3 Resource/Transformer o‘rniga view model yoki blade data shape yarating.

Deliverable: refactor qilingan controllerlar va service sinflar.

### Bosqich D — Blade view arxitekturasi (3-5 kun)
- 4.1 Asosiy layout yaratish: `resources/views/layouts/app.blade.php` (meta, header, footer, assets).
- 4.2 Komponentlar: `components/product-card.blade.php`, `components/pagination.blade.php`, `components/filters.blade.php`.
- 4.3 Product listing sahifasi (`resources/views/products/index.blade.php`) — server-side paginated va filterable.
- 4.4 Product detail sahifasi (`resources/views/products/show.blade.php`) — SEO meta, canonical, JSON-LD (agar kerak bo‘lsa).

Deliverable: to‘liq ishlaydigan Blade shablon to‘plami.

### Bosqich E — Routes va Web middleware (1 kun)
- 5.1 `routes/web.php` ichida yangi routes yarating (`/products`, `/products/{slug}`).
- 5.2 Agar API saqlanib qolsa, `routes/api.php` uchun alohida namespace qoldiring.
- 5.3 Localization middleware qo‘shing (`setLocale`), `Accept-Language` boshqaruvi.

Deliverable: web route'lar, localization middleware.

### Bosqich F — Assets, JS, CSS (2 kun)
- 6.1 Tailwind yoki Bootstrap tanlang. Agar frontend reja sifatida Tailwind qo‘llanilgan bo‘lsa, Vite/Mix konfiguratsiyasini sozlang.
- 6.2 Minimal JS (search, filters AJAX optional) — lekin server-rendered variant asosiy bo‘lsin.
- 6.3 Image optimization va storage disk konfiguratsiyasi.

Deliverable: assets pipeline va kompilyatsiya.

### Bosqich G — Caching, Performance (1-2 kun)
- 7.1 Query optimizatsiyasi: `with()` + indexlar; katta listinglar uchun `simplePaginate()` yoki cursor paginate.
- 7.2 View caching (fragment caching) va response cache kerak bo‘lsa.
- 7.3 Redis kesh konfiguratsiyasi va cache taglar.

Deliverable: caching strategiyasi qo‘llanilgan.

### Bosqich H — Testlar va CI (2-3 kun)
- 8.1 Feature testlar: product list, product detail, filters, pagination.
- 8.2 Unit testlar: service sinflar.
- 8.3 GitHub Actions workflow: `composer install`, `phpunit`, `php -l` va static analyzer (PHPStan/psalm) ishga tushishi.

Deliverable: testi o‘tadi va CI integratsiya.

## 5. Fayl va task checklist (amaliy)
- [ ] Branch `blade-migration` ochish.
- [ ] `public/LARAVEL_BLADE_MIGRATION_PLAN.md` — (hozir yaratildi) hujjat.
- [ ] `resources/views/layouts/app.blade.php` yaratish.
- [ ] `resources/views/products/index.blade.php`, `products/show.blade.php` va components.
- [ ] `App/Services/ProductService.php` — filtering, sorting, pagination logikasini joylashtirish.
- [ ] `App/Http/Controllers/ProductController.php` — Blade uchun mos controller.
- [ ] Migrationlar: indekslar.
- [ ] Seederlar: demo maʼlumot.
- [ ] Asset pipeline: Vite/Mix + Tailwind.
- [ ] Testlar va CI pipeline.

## 6. Timeline taklif (minimum)
- Umumiy baho: 2-3 hafta (1 developer, full-time) — loyihaning murakkabligiga qarab o‘zgaradi.
  - Preparation & DB — 3 kun
  - Controllers & Services — 3 kun
  - Blade views — 4-6 kun
  - Assets & Caching — 3 kun
  - Testlar & CI — 3 kun

## 7. Qabul mezonlari (Acceptance criteria)
- Barcha asosiy sahifalar (listing, detail, category) server-side render orqali ishlaydi.
- Filtr va sort funksiyalari to‘g‘ri ishlaydi va pagination maʼlumotlari to‘g‘ri ko‘rsatiladi.
- Performance: listing sahifa 2s dan tez yuklanadi lokal muhitda (profiling bilan tekshirish).
- Unit/feature testlar o‘tadi va CI workflow mavjud.

## 8. Qoʻshimcha tavsiyalar
- Agar frontend React ilovasi kerak bo‘lsa, uni alohida `frontend/` repoda saqlash va API bilan bog‘lash foydali.
- Monitoring (Sentry) va logging darajasini sozlash.
- SEO uchun meta va OpenGraph teglarini Blade layoutga kiritish.

---

## Hisobot — Bosqich 1: ProductService va Web controller refactor (2025-08-19)

Qisqacha: loyihaning Blade migratsiyasi doirasida birinchi amaliy ishlar bajarildi — backendda web view uchun kerakli servis sinfi yaratildi va mavjud web controller shu servis orqali refactor qilindi. Bu ish admin va API kodlariga taʼsir qilmadi.

Qilingan ishlar:
- Yaratildi `app/Services/ProductService.php` — product filtr, sort va pagination logikasi shu yerga ko‘chirildi.
- `app/Http/Controllers/Web/ProductController.php` refactor qilindi: index() va show() metodlari `ProductService` orqali ishlaydi.

Qo`shilgan / o`zgartirilgan fayllar:
- + `app/Services/ProductService.php` (yangi)
- M: `app/Http/Controllers/Web/ProductController.php` (refactor)
- MD hisobotga yozildi: `public/LARAVEL_BLADE_MIGRATION_PLAN.md` (joriy fayl)

Tekshiruv va eslatmalar:
- Localda `php artisan serve` va `php artisan migrate --seed` orqali funksionallikni sinab ko‘ring.
- `ProductService::getListing()` tomonidan ishlatiladigan maydonlar uchun migrationlarda indeks qo‘shish tavsiya etiladi (price, category_id, is_active, is_featured).
- QuickView metodidagi formatlashda null qiymatlar bo‘lishi mumkin — kerak bo‘lsa number_format ga cast qilish tavsiya qilinadi.

Keyingi qadamlar (navbatdagi ishlar):
1. `resources/views/web/layouts/app.blade.php` va `resources/views/web/products/index.blade.php` bazaviy shablonlarini yaratish.
2. Blade komponentlar: `web/components/product-card`, `web/components/pagination`, `web/components/filters` yaratish.
3. Routes va localization middleware tekshiruvi (web.php).

Agar tasdiqlasangiz, keyingi bosqichni boshlayman va har bosqichdan so‘ng shu formatda hisobot qoldiraman.
