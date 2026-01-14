<x-guest-layout>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #547792 100%);
        }

        .auth-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 500px;
            margin: auto;
            text-align: center;
            color: #EAE0CF;
        }

        .logo {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .logo span:nth-child(1) { color: #94B4C1; }
        .logo span:nth-child(2) { color: white; }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.38);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
        }

        input:focus {
            outline: none;
            border-color: #ffffff;
            background: rgba(255, 255, 255, 0.2);
        }

        .btn {
            width: 100%;
            background: linear-gradient(to right, #15222F);
            color: #eaf0f6;
            padding: 18px;
            border-radius: 12px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>

    <div class="auth-container">
        <div class="logo">
            <span>Intern</span><span>Flow</span>
        </div>

        <h1>Halaman Login</h1>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <button type="submit" class="btn">Login ke Sistem</button>
        </form>

        <p style="margin-top:25px; opacity:.7">
            Belum punya akun?
            <a href="{{ route('register') }}" style="color:#94B4C1">Daftar di sini</a>
        </p>

        <a href="/" style="color:#15222F; display:inline-block; margin-top:20px">
            ← Kembali ke Dashboard Utama
        </a>
    </div>

</x-guest-layout>
