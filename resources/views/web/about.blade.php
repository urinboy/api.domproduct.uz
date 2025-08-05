@extends('web.layouts.app')

@section('title', 'Biz haqimizda - DOM PRODUCT')
@section('description', 'DOM PRODUCT kompaniyasi haqida ma\'lumot. Bizning missiyamiz, ko\'rsatilayotgan xizmatlar va kompaniya tarixi.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary to-primary-dark text-white py-16">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">Biz haqimizda</h1>
                <p class="text-xl md:text-2xl max-w-3xl mx-auto leading-relaxed">
                    DOM PRODUCT - O'zbekistondagi yetakchi onlayn do'kon
                </p>
            </div>
        </div>
    </section>

    <!-- Company Info -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
                    <div>
                        <h2 class="text-3xl font-bold mb-6 text-gray-800">Bizning missiyamiz</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            DOM PRODUCT O'zbekiston bo'ylab mijozlarimizga eng sifatli mahsulotlarni qulay narxlarda taqdim etishga bag'ishlangan.
                            Bizning maqsadimiz - har bir uyni zamonaviy va qulay qilish.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            2020-yildan beri faoliyat yuritib kelayotgan kompaniyamiz minglab mijozlarning ishonchini qozongan.
                        </p>
                    </div>
                    <div class="bg-primary/10 p-8 rounded-2xl">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-primary mb-2">5000+</div>
                            <div class="text-gray-600">Mamnun mijozlar</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-8">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary">1000+</div>
                                <div class="text-sm text-gray-600">Mahsulotlar</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary">50+</div>
                                <div class="text-sm text-gray-600">Kategoriyalar</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Nima uchun aynan biz?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Bizning afzalliklarimiz va mijozlarimizga taqdim etayotgan xizmatlarimiz
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Tez yetkazib berish</h3>
                    <p class="text-gray-600">Toshkent bo'ylab 1-2 kun ichida, viloyatlarga 3-5 kun ichida yetkazib beramiz</p>
                </div>

                <div class="text-center p-6 rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Sifat kafolati</h3>
                    <p class="text-gray-600">Barcha mahsulotlarimiz sertifikatlangan va yuqori sifatga ega</p>
                </div>

                <div class="text-center p-6 rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Qulay narxlar</h3>
                    <p class="text-gray-600">Raqobatbardosh narxlar va muntazam chegirmalar</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Bizning jamoa</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Professional va tajribali mutaxassislardan iborat jamoamiz
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-primary to-primary-dark rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl font-bold">AM</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Abdulloh Muhammadov</h3>
                    <p class="text-gray-600 mb-2">Bosh direktor</p>
                    <p class="text-sm text-gray-500">10 yillik tajriba</p>
                </div>

                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-primary to-primary-dark rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl font-bold">FO</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Fotima Oripova</h3>
                    <p class="text-gray-600 mb-2">Marketing menejeri</p>
                    <p class="text-sm text-gray-500">7 yillik tajriba</p>
                </div>

                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-primary to-primary-dark rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl font-bold">SA</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Sardor Aliyev</h3>
                    <p class="text-gray-600 mb-2">Texnik rahbar</p>
                    <p class="text-sm text-gray-500">8 yillik tajriba</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Biz bilan bog'lanish</h2>
                <p class="text-primary-light max-w-2xl mx-auto">
                    Savol va takliflaringiz bo'lsa, biz bilan bog'laning
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <div class="text-center">
                    <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Telefon</h3>
                    <p class="text-primary-light">+998 71 123 45 67</p>
                    <p class="text-primary-light">+998 90 123 45 67</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Email</h3>
                    <p class="text-primary-light">info@domproduct.uz</p>
                    <p class="text-primary-light">support@domproduct.uz</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Manzil</h3>
                    <p class="text-primary-light">Toshkent shahar,</p>
                    <p class="text-primary-light">Mirzo Ulug'bek tumani</p>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('web.contact') }}" class="btn bg-white text-primary hover:bg-gray-100 font-semibold px-8 py-3 rounded-lg transition-colors">
                    Bog'lanish sahifasiga o'tish
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
