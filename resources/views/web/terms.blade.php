@extends('web.layouts.app')

@section('title', 'Foydalanish shartlari - Dom Product')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Foydalanish shartlari</h1>
                <p class="text-lg text-gray-600">Dom Product xizmatlaridan foydalanish qoidalari</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-sm p-8">

            <!-- Kirish -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">1. Umumiy qoidalar</h2>
                <div class="prose prose-lg text-gray-700">
                    <p class="mb-4">
                        Dom Product platformasiga xush kelibsiz! Ushbu foydalanish shartlari sizning bizning veb-sayt va xizmatlarimizdan foydalanishingizni tartibga soladi.
                    </p>
                    <p class="mb-4">
                        Platformamizdan foydalanish orqali siz ushbu shartlarni to'liq qabul qilasiz va ulariga rioya qilishga majbursiz.
                    </p>
                    <p class="mb-4">
                        Agar ushbu shartlarning birortasiga rozi bo'lmasangiz, iltimos, xizmatlarimizdan foydalanmang.
                    </p>
                </div>
            </section>

            <!-- Ro'yxatdan o'tish -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">2. Ro'yxatdan o'tish va hisob</h2>
                <div class="prose prose-lg text-gray-700">
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Platformada ro'yxatdan o'tish uchun haqiqiy ma'lumotlarni taqdim etishingiz kerak</li>
                        <li>Har bir foydalanuvchi faqat bitta hisob ochishi mumkin</li>
                        <li>Parolingizni maxfiy saqlash va boshqalar bilan baham ko'rmaslik sizning mas'uliyatingizdir</li>
                        <li>Hisobingizda sodir bo'lgan barcha harakatlar uchun siz javobgarsiz</li>
                        <li>Agar hisobingizga ruxsatsiz kirish holatlari bo'lsa, darhol bizga xabar bering</li>
                    </ul>
                </div>
            </section>

            <!-- Xaridlar -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">3. Xaridlar va to'lovlar</h2>
                <div class="prose prose-lg text-gray-700">
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Buyurtma berish</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Barcha narxlar O'zbekiston so'mida ko'rsatilgan</li>
                            <li>Mahsulot narxlari va mavjudligi o'zgarishi mumkin</li>
                            <li>Buyurtma tasdiqlangach, shartnoma kuchga kiradi</li>
                            <li>Noto'g'ri ma'lumotlar sabab buyurtma bekor qilinishi mumkin</li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">To'lov usullari</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Naqd pul (yetkazib berish paytida)</li>
                            <li>Bank kartalari (Uzcard, Humo, Visa, Mastercard)</li>
                            <li>Elektron to'lov tizimlari</li>
                            <li>Bank o'tkazmalari</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Yetkazib berish -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">4. Yetkazib berish</h2>
                <div class="prose prose-lg text-gray-700">
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Yetkazib berish muddati 1-7 ish kuni ichida</li>
                        <li>Yetkazib berish narxi buyurtma miqdoriga bog'liq</li>
                        <li>100,000 so'mdan yuqori buyurtmalar uchun bepul yetkazib berish</li>
                        <li>Mijoz ko'rsatgan manzilga yetkazib beriladi</li>
                        <li>Mahsulotni qabul qilishda tekshirib olish mumkin</li>
                    </ul>
                </div>
            </section>

            <!-- Qaytarish -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">5. Qaytarish va almashtirish</h2>
                <div class="prose prose-lg text-gray-700">
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Qaytarish shartlari</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Mahsulotni 14 kun ichida qaytarish mumkin</li>
                            <li>Mahsulot asl holatida va qadoqda bo'lishi kerak</li>
                            <li>Qaytarish sababi sifatli bo'lishi kerak</li>
                            <li>Pul 3-5 ish kuni ichida qaytariladi</li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Almashtirish</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Noto'g'ri o'lcham yoki rang uchun almashtirish</li>
                            <li>Nuqsonli mahsulotlar darhol almashtiriladi</li>
                            <li>Almashtirish uchun qo'shimcha to'lov talab qilinishi mumkin</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Mas'uliyat -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">6. Mas'uliyat va kafolat</h2>
                <div class="prose prose-lg text-gray-700">
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Biz mahsulotlar sifati uchun kafolat beramiz</li>
                        <li>Texnik nosozliklar tufayli zarar uchun mas'uliyat cheklanadi</li>
                        <li>Noto'g'ri foydalanish natijasida yuzaga kelgan muammolar uchun javobgar emasmiz</li>
                        <li>Uchinchi tomon xizmatlari uchun to'liq mas'uliyat olmaymiz</li>
                    </ul>
                </div>
            </section>

            <!-- Maxfiylik -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">7. Maxfiylik va ma'lumotlar</h2>
                <div class="prose prose-lg text-gray-700">
                    <p class="mb-4">
                        Sizning shaxsiy ma'lumotlaringiz bizning
                        <a href="{{ route('web.privacy') }}" class="text-blue-600 hover:text-blue-800 underline">
                            Maxfiylik siyosati
                        </a>ga muvofiq ishlov beriladi.
                    </p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Ma'lumotlarni himoya qilish uchun zamonaviy texnologiyalardan foydalanamiz</li>
                        <li>Shaxsiy ma'lumotlar uchinchi tomonlarga berilmaydi</li>
                        <li>Marketing maqsadida ma'lumotlardan foydalanish mumkin</li>
                        <li>Har qachon ham obunani bekor qilish imkoniyati mavjud</li>
                    </ul>
                </div>
            </section>

            <!-- Intellektual mulk -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">8. Intellektual mulk huquqlari</h2>
                <div class="prose prose-lg text-gray-700">
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Saytdagi barcha kontent bizning mulkimizdir</li>
                        <li>Logo, dizayn va matnlarni nusxalash taqiqlanadi</li>
                        <li>Ruxsatsiz foydalanish qonuniy javobgarlikka tortadi</li>
                        <li>Mahsulot tasvirlari va ma'lumotlari himoyalangan</li>
                    </ul>
                </div>
            </section>

            <!-- O'zgartirishlar -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">9. Shartlarni o'zgartirish</h2>
                <div class="prose prose-lg text-gray-700">
                    <p class="mb-4">
                        Dom Product ushbu foydalanish shartlarini istalgan vaqtda o'zgartirish huquqini o'zida saqlab qoladi.
                    </p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>O'zgarishlar saytda e'lon qilinadi</li>
                        <li>Muhim o'zgarishlar haqida email orqali xabar beriladi</li>
                        <li>Yangi shartlar e'lon qilingan kundan kuchga kiradi</li>
                        <li>Davom etgan foydalanish yangi shartlarni qabul qilishni anglatadi</li>
                    </ul>
                </div>
            </section>

            <!-- Bog'lanish -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">10. Bog'lanish</h2>
                <div class="prose prose-lg text-gray-700">
                    <p class="mb-4">
                        Ushbu shartlar haqida savollaringiz bo'lsa, biz bilan bog'laning:
                    </p>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Telefon:</h4>
                                <p class="text-gray-700">+998 (90) 123-45-67</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Email:</h4>
                                <p class="text-gray-700">info@domproduct.uz</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Manzil:</h4>
                                <p class="text-gray-700">Toshkent shahar, Yunusobod tumani</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Ish vaqti:</h4>
                                <p class="text-gray-700">Dush-Juma: 9:00-18:00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer info -->
            <div class="border-t pt-8">
                <div class="text-center text-gray-500">
                    <p class="mb-2">Oxirgi yangilanish: {{ date('d.m.Y') }}</p>
                    <p>
                        <a href="{{ route('web.home') }}" class="text-blue-600 hover:text-blue-800 underline">
                            Bosh sahifaga qaytish
                        </a>
                        |
                        <a href="{{ route('web.contact') }}" class="text-blue-600 hover:text-blue-800 underline ml-2">
                            Bog'lanish
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
