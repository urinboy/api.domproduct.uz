@extends('web.layouts.app')

@section('title', 'Maxfiylik siyosati - Dom Product')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Maxfiylik siyosati</h1>
                <p class="text-lg text-gray-600">Shaxsiy ma'lumotlaringizni himoya qilish bizning ustuvor vazifamiz</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-sm p-8">

            <!-- Kirish -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">1. Umumiy ma'lumotlar</h2>
                <div class="prose prose-lg text-gray-700">
                    <p class="mb-4">
                        Dom Product sizning maxfiyligingizni hurmat qiladi va shaxsiy ma'lumotlaringizni himoya qilishga sodiq.
                        Ushbu maxfiylik siyosati biz qanday ma'lumotlarni to'playmiz, ulardan qanday foydalanamiz va
                        qanday himoya qilamiz haqida batafsil ma'lumot beradi.
                    </p>
                    <p class="mb-4">
                        Bizning xizmatlarimizdan foydalanish orqali siz ushbu maxfiylik siyosatiga rozilik bildirasiz.
                    </p>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 my-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Muhim:</strong> Ushbu siyosat oxirgi marta {{ date('d.m.Y') }} sanasida yangilanган.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- To'playdigan ma'lumotlar -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">2. To'playdigan ma'lumotlar</h2>
                <div class="prose prose-lg text-gray-700">

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">2.1. Shaxsiy ma'lumotlar</h3>
                        <p class="mb-4">Quyidagi shaxsiy ma'lumotlarni to'playmiz:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Ism va familiya</strong> - ro'yxatdan o'tish va buyurtmalar uchun</li>
                            <li><strong>Email manzil</strong> - aloqa va xabarnomalar uchun</li>
                            <li><strong>Telefon raqam</strong> - buyurtmalarni tasdiqlash uchun</li>
                            <li><strong>Yetkazib berish manzili</strong> - mahsulotlarni yetkazib berish uchun</li>
                            <li><strong>Tug'ilgan sana</strong> - yosh chegaralarini tekshirish uchun</li>
                            <li><strong>Jins</strong> - moslashtirilgan tavsiyalar uchun</li>
                        </ul>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">2.2. To'lov ma'lumotlari</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Karta oxirgi 4 raqami (xavfsizlik uchun)</li>
                            <li>To'lov tarixi va summasi</li>
                            <li>To'lov usuli (naqd, karta, bank o'tkazmasi)</li>
                            <li>Buyurtma va faktura ma'lumotlari</li>
                        </ul>
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mt-4">
                            <p class="text-sm text-green-700">
                                <strong>Xavfsizlik:</strong> To'liq karta ma'lumotlari hech qachon saqlanmaydi.
                                Barcha to'lov ma'lumotlari shifrlangan holda ishlov beriladi.
                            </p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">2.3. Texnik ma'lumotlar</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>IP manzil va geografik joylashuv</li>
                            <li>Brauzer turi va versiyasi</li>
                            <li>Qurilma turi (kompyuter, telefon, planshet)</li>
                            <li>Saytda o'tkazilgan vaqt va ko'rilgan sahifalar</li>
                            <li>Qidiruv so'rovlari va harakatlar</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Ma'lumotlardan foydalanish -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">3. Ma'lumotlardan foydalanish maqsadlari</h2>
                <div class="prose prose-lg text-gray-700">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Asosiy xizmatlar</h3>
                            <ul class="text-sm space-y-2">
                                <li>• Buyurtmalarni qayta ishlash</li>
                                <li>• Mahsulotlarni yetkazib berish</li>
                                <li>• Mijozlarga yordam berish</li>
                                <li>• Hisob qaydnomasini boshqarish</li>
                                <li>• To'lovlarni qayta ishlash</li>
                            </ul>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Qo'shimcha xizmatlar</h3>
                            <ul class="text-sm space-y-2">
                                <li>• Moslashtirilgan tavsiyalar</li>
                                <li>• Marketing kampaniyalari</li>
                                <li>• Xizmat sifatini yaxshilash</li>
                                <li>• Xavfsizlik va firibgarlikni oldini olish</li>
                                <li>• Qonuniy majburiyatlarni bajarish</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Cookies -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">4. Cookie va kuzatuv texnologiyalari</h2>
                <div class="prose prose-lg text-gray-700">

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Cookie turlari</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cookie turi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maqsad</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Muddati</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Zaruriy</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sayt ishlashi uchun</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sessiya</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Analitik</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sayt statistikasi</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2 yil</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Marketing</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Moslashtirilgan reklama</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1 yil</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Imkoniyatlar</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sayt sozlamalari</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6 oy</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <p class="text-sm text-yellow-700">
                            Cookie sozlamalarini brauzer orqali boshqarishingiz mumkin.
                            Biroq, ba'zi cookie'larni o'chirish sayt funksionalligiga ta'sir qilishi mumkin.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Ma'lumotlarni baham ko'rish -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">5. Ma'lumotlarni baham ko'rish</h2>
                <div class="prose prose-lg text-gray-700">
                    <p class="mb-6">
                        Sizning shaxsiy ma'lumotlaringizni quyidagi hollarda uchinchi tomonlar bilan baham ko'rishimiz mumkin:
                    </p>

                    <div class="space-y-6">
                        <div class="border-l-4 border-blue-400 pl-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Xizmat ko'rsatuvchi hamkorlar</h3>
                            <ul class="list-disc pl-6 space-y-1">
                                <li>Yetkazib berish xizmatlari (DHL, Uzpost)</li>
                                <li>To'lov tizimlari (Click, Payme, UzCard)</li>
                                <li>Analitik xizmatlar (Google Analytics)</li>
                                <li>Bulutli xizmatlar (Amazon AWS)</li>
                            </ul>
                        </div>

                        <div class="border-l-4 border-green-400 pl-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Qonuniy talablar</h3>
                            <ul class="list-disc pl-6 space-y-1">
                                <li>Sud qarori bo'yicha</li>
                                <li>Soliq organlari talabi</li>
                                <li>Huquqni muhofaza qilish organlari</li>
                                <li>Firibgarlikni oldini olish</li>
                            </ul>
                        </div>

                        <div class="border-l-4 border-red-400 pl-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Hech qachon baham ko'rilmaydi</h3>
                            <ul class="list-disc pl-6 space-y-1">
                                <li>Uchinchi tomon reklama kompaniyalari</li>
                                <li>Ma'lumot brokerlari</li>
                                <li>Raqobatchi kompaniyalar</li>
                                <li>Spam yuboruvchilar</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Xavfsizlik -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">6. Ma'lumotlar xavfsizligi</h2>
                <div class="prose prose-lg text-gray-700">
                    <p class="mb-6">
                        Sizning ma'lumotlaringizni himoya qilish uchun quyidagi xavfsizlik choralarini qo'llaymiz:
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Texnik himoya
                            </h3>
                            <ul class="text-sm space-y-2">
                                <li>• SSL shifrlash (256-bit)</li>
                                <li>• Firewall himoyasi</li>
                                <li>• Antivirus monitoring</li>
                                <li>• DDoS himoyasi</li>
                                <li>• Muntazam xavfsizlik yangilanishlari</li>
                            </ul>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Ma'muriy himoya
                            </h3>
                            <ul class="text-sm space-y-2">
                                <li>• Cheklangan kirish huquqlari</li>
                                <li>• Xodimlar uchun maxfiylik shartnomasi</li>
                                <li>• Muntazam xavfsizlik treninglari</li>
                                <li>• Ma'lumotlar zaxira nusxalari</li>
                                <li>• Incidentlarni monitoring qilish</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mt-6">
                        <p class="text-sm text-red-700">
                            <strong>Muhim eslatma:</strong> Internet orqali ma'lumotlar uzatish 100% xavfsiz emas.
                            Maxfiy ma'lumotlarni yuborishda ehtiyot bo'ling.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Huquqlar -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">7. Sizning huquqlaringiz</h2>
                <div class="prose prose-lg text-gray-700">
                    <p class="mb-6">Sizning shaxsiy ma'lumotlaringiz bo'yicha quyidagi huquqlarga egasiz:</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                        <span class="text-sm font-medium text-blue-600">1</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Ma'lumotlarga kirish huquqi</h3>
                                    <p class="text-gray-600">Biz sizning qaysi ma'lumotlaringizni saqlab turganligimizni bilish</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                        <span class="text-sm font-medium text-blue-600">2</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Tuzatish huquqi</h3>
                                    <p class="text-gray-600">Noto'g'ri yoki eskirgan ma'lumotlarni yangilash</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                        <span class="text-sm font-medium text-blue-600">3</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">O'chirish huquqi</h3>
                                    <p class="text-gray-600">Ma'lumotlaringizni o'chirishni so'rash</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                        <span class="text-sm font-medium text-blue-600">4</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Cheklash huquqi</h3>
                                    <p class="text-gray-600">Ma'lumotlarni ishlov berishni cheklash</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                        <span class="text-sm font-medium text-blue-600">5</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Ko'chirish huquqi</h3>
                                    <p class="text-gray-600">Ma'lumotlaringizni boshqa xizmatga ko'chirish</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                        <span class="text-sm font-medium text-blue-600">6</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">E'tiroz huquqi</h3>
                                    <p class="text-gray-600">Marketing maqsadlarda ishlov berishga qarshi chiqish</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-lg mt-8">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3">Huquqlaringizni amalga oshirish</h3>
                        <p class="text-blue-800 mb-3">
                            Yuqoridagi huquqlarningizdan foydalanish uchun biz bilan bog'laning:
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="mailto:privacy@domproduct.uz" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email yuborish
                            </a>
                            <a href="tel:+998901234567" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Qo'ng'iroq qilish
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Ma'lumotlarni saqlash muddati -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">8. Ma'lumotlarni saqlash muddati</h2>
                <div class="prose prose-lg text-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ma'lumot turi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saqlash muddati</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sabab</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Hisob ma'lumotlari</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Hisob faol bo'lguncha</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Xizmat ko'rsatish uchun</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Buyurtma tarixi</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">7 yil</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Qonuniy majburiyat</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">To'lov ma'lumotlari</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5 yil</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Moliyaviy hisobot</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Marketing ma'lumotlari</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3 yil yoki obunani bekor qilish</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Marketing kampaniyalari</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Texnik loglar</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1 yil</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Xavfsizlik va tahlil</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Bolalar maxfiylik -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">9. Bolalar maxfiyligi</h2>
                <div class="prose prose-lg text-gray-700">
                    <div class="bg-red-50 border-l-4 border-red-400 p-6">
                        <h3 class="text-lg font-semibold text-red-800 mb-3">16 yoshgacha bo'lgan bolalar</h3>
                        <p class="text-red-700 mb-4">
                            Bizning xizmatlarimiz 16 yoshdan kichik bolalar uchun mo'ljallanmagan.
                            Agar 16 yoshdan kichik bolaning ma'lumotlarini olganligimizni bilsak,
                            darhol ularni o'chiramiz.
                        </p>
                        <p class="text-red-700">
                            Agar ota-onalar o'z farzandining ma'lumotlari bizda saqlanayotganini bilsalar,
                            iltimos darhol biz bilan bog'laning.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Bog'lanish -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">10. Bog'lanish ma'lumotlari</h2>
                <div class="prose prose-lg text-gray-700">
                    <p class="mb-6">
                        Maxfiylik siyosati yoki ma'lumotlar himoyasi haqida savollaringiz bo'lsa, biz bilan bog'laning:
                    </p>

                    <div class="bg-gray-50 p-8 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Maxfiylik bo'yicha masalalar
                                </h4>
                                <p class="text-gray-700 mb-2">Email: privacy@domproduct.uz</p>
                                <p class="text-gray-700">Javob muddati: 3 ish kuni ichida</p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    Tezkor yordam
                                </h4>
                                <p class="text-gray-700 mb-2">Telefon: +998 (90) 123-45-67</p>
                                <p class="text-gray-700">Ish vaqti: Dush-Juma 9:00-18:00</p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ofis manzili
                                </h4>
                                <p class="text-gray-700 mb-2">Toshkent shahar, Yunusobod tumani</p>
                                <p class="text-gray-700">Amir Temur ko'chasi, 123-uy</p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Favqulodda holat
                                </h4>
                                <p class="text-gray-700 mb-2">Ma'lumotlar buzilishi: emergency@domproduct.uz</p>
                                <p class="text-gray-700">24/7 javob berish</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer info -->
            <div class="border-t pt-8">
                <div class="text-center text-gray-500">
                    <p class="mb-4">
                        Ushbu maxfiylik siyosati {{ date('d.m.Y') }} sanasida oxirgi marta yangilangan.
                    </p>
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('web.home') }}" class="text-blue-600 hover:text-blue-800 underline">
                            Bosh sahifa
                        </a>
                        <span>|</span>
                        <a href="{{ route('web.terms') }}" class="text-blue-600 hover:text-blue-800 underline">
                            Foydalanish shartlari
                        </a>
                        <span>|</span>
                        <a href="{{ route('web.contact') }}" class="text-blue-600 hover:text-blue-800 underline">
                            Bog'lanish
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
