<!DOCTYPE html>
<html>
<head>
    <title>Auth Test</title>
</head>
<body>
    <h1>Authentication Test</h1>

    @if(Auth::check())
        <div style="background: green; color: white; padding: 10px;">
            <h2>Foydalanuvchi tizimga kirgan</h2>
            <p><strong>ID:</strong> {{ Auth::user()->id }}</p>
            <p><strong>Ism:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Telefon:</strong> {{ Auth::user()->phone ?? 'Yo\'q' }}</p>

            @php
                $adminEmails = [
                    'admin@domproduct.uz',
                    'urinboydev@gmail.com',
                    'admin@gmail.com'
                ];

                $adminPhones = [
                    '+998901234567',
                    '+998991234567'
                ];

                $isAdmin = in_array(Auth::user()->email, $adminEmails) ||
                          in_array(Auth::user()->phone, $adminPhones);
            @endphp

            @if($isAdmin)
                <div style="background: orange; padding: 5px; margin-top: 10px;">
                    <strong>Bu foydalanuvchi ADMIN</strong>
                </div>
            @else
                <div style="background: red; padding: 5px; margin-top: 10px;">
                    <strong>Bu foydalanuvchi ADMIN EMAS</strong>
                </div>
            @endif
        </div>

        <div style="margin-top: 20px;">
            <a href="/admin/login" style="background: blue; color: white; padding: 10px; text-decoration: none;">Admin Login ga boring</a>
            <form action="/logout" method="POST" style="display: inline-block; margin-left: 10px;">
                @csrf
                <button type="submit" style="background: red; color: white; padding: 10px; border: none;">Logout</button>
            </form>
        </div>
    @else
        <div style="background: red; color: white; padding: 10px;">
            <h2>Foydalanuvchi tizimga kirmagan</h2>
        </div>

        <div style="margin-top: 20px;">
            <a href="/admin/login" style="background: blue; color: white; padding: 10px; text-decoration: none;">Admin Login ga boring</a>
        </div>
    @endif
</body>
</html>
