<footer class="footer">
    <!-- Footer Top -->
    <div class="footer-top">
        <div class="container">
            <div class="footer-content">
                <!-- Company Info -->
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="{{ asset('images/logo-light.png') }}" alt="{{ config('app.name') }}">
                    </div>
                    <p class="footer-description">
                        {{ __('O\'zbekistondagi eng yirik onlayn do\'kon. Sifatli mahsulotlar, tez yetkazib berish va professional xizmat.') }}
                    </p>
                    
                    <div class="footer-contacts">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div class="contact-info">
                                <span class="contact-label">{{ __('Qo\'ng\'iroq markazi') }}</span>
                                <a href="tel:+998712000000" class="contact-value">+998 (71) 200-00-00</a>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div class="contact-info">
                                <span class="contact-label">{{ __('Email') }}</span>
                                <a href="mailto:info@domproduct.uz" class="contact-value">info@domproduct.uz</a>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="contact-info">
                                <span class="contact-label">{{ __('Manzil') }}</span>
                                <span class="contact-value">{{ __('Toshkent sh., Amir Temur ko\'chasi, 108') }}</span>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <div class="contact-info">
                                <span class="contact-label">{{ __('Ish vaqti') }}</span>
                                <span class="contact-value">{{ __('Har kuni 9:00 dan 21:00 gacha') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Categories -->
                <div class="footer-section">
                    <h3 class="footer-title">{{ __('Kategoriyalar') }}</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('category', 'smartphones') }}">{{ __('Smartfonlar') }}</a></li>
                        <li><a href="{{ route('category', 'laptops') }}">{{ __('Noutbuklar') }}</a></li>
                        <li><a href="{{ route('category', 'tv') }}">{{ __('Televizorlar') }}</a></li>
                        <li><a href="{{ route('category', 'appliances') }}">{{ __('Maishiy texnika') }}</a></li>
                        <li><a href="{{ route('category', 'fashion') }}">{{ __('Kiyim-kechak') }}</a></li>
                        <li><a href="{{ route('category', 'beauty') }}">{{ __('Go\'zallik') }}</a></li>
                        <li><a href="{{ route('category', 'home') }}">{{ __('Uy va bog\'') }}</a></li>
                        <li><a href="{{ route('category', 'sports') }}">{{ __('Sport va dam olish') }}</a></li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div class="footer-section">
                    <h3 class="footer-title">{{ __('Xizmatlar') }}</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('delivery') }}">{{ __('Yetkazib berish') }}</a></li>
                        <li><a href="{{ route('payment') }}">{{ __('To\'lov usullari') }}</a></li>
                        <li><a href="{{ route('installment') }}">{{ __('Muddatli to\'lov') }}</a></li>
                        <li><a href="{{ route('warranty') }}">{{ __('Kafolat xizmati') }}</a></li>
                        <li><a href="{{ route('trade-in') }}">{{ __('Trade-in') }}</a></li>
                        <li><a href="{{ route('repair') }}">{{ __('Ta\'mirlash') }}</a></li>
                        <li><a href="{{ route('insurance') }}">{{ __('Sug\'urta') }}</a></li>
                        <li><a href="{{ route('corporate') }}">{{ __('Korporativ xizmat') }}</a></li>
                    </ul>
                </div>
                
                <!-- Customer Support -->
                <div class="footer-section">
                    <h3 class="footer-title">{{ __('Mijozlarga yordam') }}</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('help') }}">{{ __('Yordam markazi') }}</a></li>
                        <li><a href="{{ route('faq') }}">{{ __('Tez-tez so\'raladigan savollar') }}</a></li>
                        <li><a href="{{ route('contact') }}">{{ __('Aloqa') }}</a></li>
                        <li><a href="{{ route('track-order') }}">{{ __('Buyurtmani kuzatish') }}</a></li>
                        <li><a href="{{ route('return-policy') }}">{{ __('Qaytarish shartlari') }}</a></li>
                        <li><a href="{{ route('complaint') }}">{{ __('Shikoyat qoldirish') }}</a></li>
                        <li><a href="{{ route('feedback') }}">{{ __('Fikr-mulohaza') }}</a></li>
                        <li><a href="{{ route('become-seller') }}">{{ __('Sotuvchi bo\'lish') }}</a></li>
                    </ul>
                </div>
                
                <!-- Company -->
                <div class="footer-section">
                    <h3 class="footer-title">{{ __('Kompaniya') }}</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('about') }}">{{ __('Biz haqimizda') }}</a></li>
                        <li><a href="{{ route('news') }}">{{ __('Yangiliklar') }}</a></li>
                        <li><a href="{{ route('careers') }}">{{ __('Vakansiyalar') }}</a></li>
                        <li><a href="{{ route('press') }}">{{ __('Matbuot xizmati') }}</a></li>
                        <li><a href="{{ route('investors') }}">{{ __('Investorlar') }}</a></li>
                        <li><a href="{{ route('partners') }}">{{ __('Hamkorlar') }}</a></li>
                        <li><a href="{{ route('affiliate') }}">{{ __('Affiliate dastur') }}</a></li>
                        <li><a href="{{ route('stores') }}">{{ __('Do\'konlar') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Middle -->
    <div class="footer-middle">
        <div class="container">
            <div class="footer-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="feature-content">
                        <h4 class="feature-title">{{ __('Tezkor yetkazib berish') }}</h4>
                        <p class="feature-text">{{ __('Toshkent bo\'ylab 2 soatda') }}</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-check"></i>
                    </div>
                    <div class="feature-content">
                        <h4 class="feature-title">{{ __('Kafolatlangan sifat') }}</h4>
                        <p class="feature-text">{{ __('Faqat asl mahsulotlar') }}</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="feature-content">
                        <h4 class="feature-title">{{ __('Xavfsiz to\'lov') }}</h4>
                        <p class="feature-text">{{ __('SSL shifrlash himoyasi') }}</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <div class="feature-content">
                        <h4 class="feature-title">{{ __('14 kun qaytarish') }}</h4>
                        <p class="feature-text">{{ __('Sababsiz qaytarish') }}</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="feature-content">
                        <h4 class="feature-title">{{ __('24/7 qo\'llab-quvvatlash') }}</h4>
                        <p class="feature-text">{{ __('Professional yordam') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Newsletter Subscription -->
            <div class="footer-newsletter">
                <div class="newsletter-content">
                    <h3 class="newsletter-title">{{ __('Yangiliklar va takliflar') }}</h3>
                    <p class="newsletter-text">{{ __('Maxsus takliflar va chegirmalar haqida birinchi bo\'lib xabar oling') }}</p>
                </div>
                
                <form class="newsletter-form" onsubmit="subscribeFooterNewsletter(event)">
                    <div class="newsletter-input-group">
                        <input type="email" 
                               class="newsletter-input" 
                               placeholder="{{ __('Email manzilingiz') }}"
                               required>
                        <button type="submit" class="newsletter-submit">
                            <i class="fas fa-paper-plane"></i>
                            <span>{{ __('Obuna') }}</span>
                        </button>
                    </div>
                    <label class="newsletter-checkbox">
                        <input type="checkbox" required>
                        <span class="checkmark"></span>
                        {{ __('Men') }} 
                        <a href="{{ route('privacy') }}">{{ __('maxfiylik siyosati') }}</a>{{ __('ga roziman') }}
                    </label>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <div class="footer-left">
                    <div class="footer-copyright">
                        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('Barcha huquqlar himoyalangan.') }}</p>
                    </div>
                    
                    <div class="footer-legal">
                        <a href="{{ route('terms') }}">{{ __('Foydalanish shartlari') }}</a>
                        <span class="separator">|</span>
                        <a href="{{ route('privacy') }}">{{ __('Maxfiylik siyosati') }}</a>
                        <span class="separator">|</span>
                        <a href="{{ route('cookies') }}">{{ __('Cookie siyosati') }}</a>
                        <span class="separator">|</span>
                        <a href="{{ route('sitemap') }}">{{ __('Sayt xaritasi') }}</a>
                    </div>
                </div>
                
                <div class="footer-right">
                    <!-- Social Media -->
                    <div class="footer-social">
                        <span class="social-label">{{ __('Ijtimoiy tarmoqlar:') }}</span>
                        <div class="social-links">
                            <a href="{{ config('social.facebook') }}" class="social-link facebook" target="_blank" rel="noopener">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="{{ config('social.instagram') }}" class="social-link instagram" target="_blank" rel="noopener">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="{{ config('social.telegram') }}" class="social-link telegram" target="_blank" rel="noopener">
                                <i class="fab fa-telegram-plane"></i>
                            </a>
                            <a href="{{ config('social.youtube') }}" class="social-link youtube" target="_blank" rel="noopener">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="{{ config('social.twitter') }}" class="social-link twitter" target="_blank" rel="noopener">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="{{ config('social.linkedin') }}" class="social-link linkedin" target="_blank" rel="noopener">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Payment Methods -->
                    <div class="footer-payments">
                        <span class="payments-label">{{ __('To\'lov usullari:') }}</span>
                        <div class="payment-methods">
                            <img src="{{ asset('images/payments/visa.png') }}" alt="Visa">
                            <img src="{{ asset('images/payments/mastercard.png') }}" alt="MasterCard">
                            <img src="{{ asset('images/payments/uzcard.png') }}" alt="UzCard">
                            <img src="{{ asset('images/payments/humo.png') }}" alt="Humo">
                            <img src="{{ asset('images/payments/payme.png') }}" alt="Payme">
                            <img src="{{ asset('images/payments/click.png') }}" alt="Click">
                            <img src="{{ asset('images/payments/apay.png') }}" alt="Apay">
                        </div>
                    </div>
                    
                    <!-- Mobile Apps -->
                    <div class="footer-apps">
                        <span class="apps-label">{{ __('Mobil ilovalar:') }}</span>
                        <div class="app-links">
                            <a href="{{ config('app.android_url') }}" class="app-link" target="_blank" rel="noopener">
                                <img src="{{ asset('images/apps/google-play.png') }}" alt="Google Play">
                            </a>
                            <a href="{{ config('app.ios_url') }}" class="app-link" target="_blank" rel="noopener">
                                <img src="{{ asset('images/apps/app-store.png') }}" alt="App Store">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Certificates -->
            <div class="footer-certificates">
                <div class="certificates-label">{{ __('Sertifikatlar va a\'zolik:') }}</div>
                <div class="certificates-list">
                    <img src="{{ asset('images/certificates/cert1.png') }}" alt="{{ __('Sertifikat') }}">
                    <img src="{{ asset('images/certificates/cert2.png') }}" alt="{{ __('Sertifikat') }}">
                    <img src="{{ asset('images/certificates/cert3.png') }}" alt="{{ __('Sertifikat') }}">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" onclick="scrollToTop()">
        <i class="fas fa-chevron-up"></i>
    </button>
</footer>

@push('scripts')
<script>
// Newsletter subscription
function subscribeFooterNewsletter(event) {
    event.preventDefault();
    
    const form = event.target;
    const email = form.querySelector('input[type="email"]').value;
    const checkbox = form.querySelector('input[type="checkbox"]').checked;
    
    if (!checkbox) {
        showToast('{{ __("Maxfiylik siyosatiga rozilik bering") }}', 'error');
        return;
    }
    
    const submitBtn = form.querySelector('.newsletter-submit');
    const originalContent = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    submitBtn.disabled = true;
    
    fetch('{{ route("newsletter.subscribe") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        submitBtn.innerHTML = originalContent;
        submitBtn.disabled = false;
        
        if (data.success) {
            showToast(data.message, 'success');
            form.reset();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        submitBtn.innerHTML = originalContent;
        submitBtn.disabled = false;
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
        console.error('Error:', error);
    });
}

// Back to top functionality
document.addEventListener('DOMContentLoaded', function() {
    const backToTop = document.getElementById('backToTop');
    
    // Show/hide back to top button
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 500) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });
});

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Footer animations
if ('IntersectionObserver' in window) {
    const footerObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    document.querySelectorAll('.footer-section, .feature-item').forEach(el => {
        footerObserver.observe(el);
    });
}
</script>
@endpush
