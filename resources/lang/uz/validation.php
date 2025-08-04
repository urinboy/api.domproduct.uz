<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute maydoni qabul qilinishi kerak.',
    'accepted_if' => ':other :value bo\'lganda :attribute qabul qilinishi kerak.',
    'active_url' => ':attribute yaroqli URL emas.',
    'after' => ':attribute :date dan keyingi sana bo\'lishi kerak.',
    'after_or_equal' => ':attribute :date dan keyingi yoki teng sana bo\'lishi kerak.',
    'alpha' => ':attribute faqat harflardan iborat bo\'lishi kerak.',
    'alpha_dash' => ':attribute faqat harflar, raqamlar, tire va pastki chiziqlardan iborat bo\'lishi kerak.',
    'alpha_num' => ':attribute faqat harflar va raqamlardan iborat bo\'lishi kerak.',
    'array' => ':attribute massiv bo\'lishi kerak.',
    'before' => ':attribute :date dan oldingi sana bo\'lishi kerak.',
    'before_or_equal' => ':attribute :date dan oldingi yoki teng sana bo\'lishi kerak.',
    'between' => [
        'numeric' => ':attribute :min va :max orasida bo\'lishi kerak.',
        'file' => ':attribute :min va :max kilobayt orasida bo\'lishi kerak.',
        'string' => ':attribute :min va :max belgi orasida bo\'lishi kerak.',
        'array' => ':attribute :min va :max element orasida bo\'lishi kerak.',
    ],
    'boolean' => ':attribute maydoni true yoki false bo\'lishi kerak.',
    'confirmed' => ':attribute tasdiqlash mos kelmaydi.',
    'current_password' => 'Parol noto\'g\'ri.',
    'date' => ':attribute yaroqli sana emas.',
    'date_equals' => ':attribute :date ga teng sana bo\'lishi kerak.',
    'date_format' => ':attribute :format formatiga mos kelmaydi.',
    'declined' => ':attribute rad etilishi kerak.',
    'declined_if' => ':other :value bo\'lganda :attribute rad etilishi kerak.',
    'different' => ':attribute va :other har xil bo\'lishi kerak.',
    'digits' => ':attribute :digits raqamdan iborat bo\'lishi kerak.',
    'digits_between' => ':attribute :min va :max raqam orasida bo\'lishi kerak.',
    'dimensions' => ':attribute noto\'g\'ri rasm o\'lchamlari.',
    'distinct' => ':attribute maydoni takrorlanuvchi qiymatga ega.',
    'email' => ':attribute yaroqli email manzil bo\'lishi kerak.',
    'ends_with' => ':attribute quyidagilardan biri bilan tugashi kerak: :values.',
    'enum' => 'Tanlangan :attribute yaroqsiz.',
    'exists' => 'Tanlangan :attribute yaroqsiz.',
    'file' => ':attribute fayl bo\'lishi kerak.',
    'filled' => ':attribute maydoni qiymatga ega bo\'lishi kerak.',
    'gt' => [
        'numeric' => ':attribute :value dan katta bo\'lishi kerak.',
        'file' => ':attribute :value kilobaytdan katta bo\'lishi kerak.',
        'string' => ':attribute :value belgidan ko\'p bo\'lishi kerak.',
        'array' => ':attribute :value elementdan ko\'p bo\'lishi kerak.',
    ],
    'gte' => [
        'numeric' => ':attribute :value dan katta yoki teng bo\'lishi kerak.',
        'file' => ':attribute :value kilobaytdan katta yoki teng bo\'lishi kerak.',
        'string' => ':attribute :value belgidan ko\'p yoki teng bo\'lishi kerak.',
        'array' => ':attribute :value yoki undan ko\'p elementga ega bo\'lishi kerak.',
    ],
    'image' => ':attribute rasm bo\'lishi kerak.',
    'in' => 'Tanlangan :attribute yaroqsiz.',
    'in_array' => ':attribute maydoni :other da mavjud emas.',
    'integer' => ':attribute butun son bo\'lishi kerak.',
    'ip' => ':attribute yaroqli IP manzil bo\'lishi kerak.',
    'ipv4' => ':attribute yaroqli IPv4 manzil bo\'lishi kerak.',
    'ipv6' => ':attribute yaroqli IPv6 manzil bo\'lishi kerak.',
    'json' => ':attribute yaroqli JSON satr bo\'lishi kerak.',
    'lt' => [
        'numeric' => ':attribute :value dan kichik bo\'lishi kerak.',
        'file' => ':attribute :value kilobaytdan kichik bo\'lishi kerak.',
        'string' => ':attribute :value belgidan kam bo\'lishi kerak.',
        'array' => ':attribute :value elementdan kam bo\'lishi kerak.',
    ],
    'lte' => [
        'numeric' => ':attribute :value dan kichik yoki teng bo\'lishi kerak.',
        'file' => ':attribute :value kilobaytdan kichik yoki teng bo\'lishi kerak.',
        'string' => ':attribute :value belgidan kam yoki teng bo\'lishi kerak.',
        'array' => ':attribute :value yoki undan kam elementga ega bo\'lishi kerak.',
    ],
    'mac_address' => ':attribute yaroqli MAC manzil bo\'lishi kerak.',
    'max' => [
        'numeric' => ':attribute :max dan katta bo\'lmasligi kerak.',
        'file' => ':attribute :max kilobaytdan katta bo\'lmasligi kerak.',
        'string' => ':attribute :max belgidan ko\'p bo\'lmasligi kerak.',
        'array' => ':attribute :max elementdan ko\'p bo\'lmasligi kerak.',
    ],
    'mimes' => ':attribute quyidagi turdagi fayl bo\'lishi kerak: :values.',
    'mimetypes' => ':attribute quyidagi turdagi fayl bo\'lishi kerak: :values.',
    'min' => [
        'numeric' => ':attribute kamida :min bo\'lishi kerak.',
        'file' => ':attribute kamida :min kilobayt bo\'lishi kerak.',
        'string' => ':attribute kamida :min belgi bo\'lishi kerak.',
        'array' => ':attribute kamida :min elementga ega bo\'lishi kerak.',
    ],
    'multiple_of' => ':attribute :value ning karralisi bo\'lishi kerak.',
    'not_in' => 'Tanlangan :attribute yaroqsiz.',
    'not_regex' => ':attribute formati yaroqsiz.',
    'numeric' => ':attribute raqam bo\'lishi kerak.',
    'password' => 'Parol noto\'g\'ri.',
    'present' => ':attribute maydoni mavjud bo\'lishi kerak.',
    'prohibited' => ':attribute maydoni taqiqlangan.',
    'prohibited_if' => ':other :value bo\'lganda :attribute maydoni taqiqlangan.',
    'prohibited_unless' => ':other :values da bo\'lmasa, :attribute maydoni taqiqlangan.',
    'prohibits' => ':attribute maydoni :other mavjudligini taqiqlaydi.',
    'regex' => ':attribute formati yaroqsiz.',
    'required' => ':attribute maydoni talab qilinadi.',
    'required_array_keys' => ':attribute maydoni quyidagi kalitlarga ega bo\'lishi kerak: :values.',
    'required_if' => ':other :value bo\'lganda :attribute maydoni talab qilinadi.',
    'required_unless' => ':other :values da bo\'lmasa, :attribute maydoni talab qilinadi.',
    'required_with' => ':values mavjud bo\'lganda :attribute maydoni talab qilinadi.',
    'required_with_all' => ':values mavjud bo\'lganda :attribute maydoni talab qilinadi.',
    'required_without' => ':values mavjud bo\'lmaganda :attribute maydoni talab qilinadi.',
    'required_without_all' => ':values larning hech biri mavjud bo\'lmaganda :attribute maydoni talab qilinadi.',
    'same' => ':attribute va :other mos kelishi kerak.',
    'size' => [
        'numeric' => ':attribute :size bo\'lishi kerak.',
        'file' => ':attribute :size kilobayt bo\'lishi kerak.',
        'string' => ':attribute :size belgi bo\'lishi kerak.',
        'array' => ':attribute :size elementga ega bo\'lishi kerak.',
    ],
    'starts_with' => ':attribute quyidagilardan biri bilan boshlanishi kerak: :values.',
    'string' => ':attribute satr bo\'lishi kerak.',
    'timezone' => ':attribute yaroqli mintaqa bo\'lishi kerak.',
    'unique' => ':attribute allaqachon band.',
    'uploaded' => ':attribute yuklashda xatolik yuz berdi.',
    'url' => ':attribute yaroqli URL bo\'lishi kerak.',
    'uuid' => ':attribute yaroqli UUID bo\'lishi kerak.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'nom',
        'username' => 'foydalanuvchi nomi',
        'email' => 'email',
        'password' => 'parol',
        'password_confirmation' => 'parol tasdiqlash',
        'phone' => 'telefon raqam',
        'address' => 'manzil',
        'city' => 'shahar',
        'country' => 'mamlakat',
        'first_name' => 'ism',
        'last_name' => 'familiya',
        'title' => 'sarlavha',
        'description' => 'tavsif',
        'excerpt' => 'qisqacha',
        'date' => 'sana',
        'time' => 'vaqt',
        'available' => 'mavjud',
        'size' => 'hajm',
        'price' => 'narx',
        'content' => 'kontent',
        'summary' => 'xulosa',
        'image' => 'rasm',
        'file' => 'fayl',
    ],

];
