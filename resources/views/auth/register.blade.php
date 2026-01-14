<x-guest-layout>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #9b7e53 0%, #547792 100%);
        }

        .auth-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 520px;
            margin: auto;
            color: #EAE0CF;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            margin-bottom: 6px;
            display: block;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
        }

        input:focus {
            outline: none;
            border-color: #94B4C1;
        }

        .btn {
            width: 100%;
            background: linear-gradient(to right, #94B4C1, #547792);
            color: #213448;
            padding: 16px;
            border-radius: 12px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
    </style>

    <div class="auth-container">
        <h1>📝 Registrasi Akun</h1>
        <p>Daftarkan akun peserta magang</p>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button class="btn" type="submit">🚀 Buat Akun</button>
        </form>

        <p style="margin-top:20px">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color:#94B4C1">Login di sini</a>
        </p>
    </div>

</x-guest-layout>
