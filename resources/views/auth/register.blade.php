<x-guest-layout>
    <style>
        /* Reset dan Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Background dengan gambar dan overlay */
        .background-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(84, 119, 146, 0.85), rgba(21, 34, 47, 0.9));
            z-index: 1;
        }

        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        /* Container utama */
        .auth-container {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(12px);
            padding: 40px 35px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
            max-width: 480px;
            width: 100%;
            margin: 30px auto;
            text-align: center;
            color: #15222F;
            position: relative;
            z-index: 10;
        }

        /* Logo */
        .logo {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        .logo span:nth-child(1) {
            color: #547792;
        }

        .logo span:nth-child(2) {
            color: #15222F;
        }

        /* Subtitle */
        .subtitle {
            font-size: 1rem;
            color: #5a6c7d;
            margin-bottom: 35px;
            font-weight: 400;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #15222F;
            font-size: 0.95rem;
        }

        .input-container {
            position: relative;
        }

        input {
            width: 100%;
            padding: 17px 20px;
            background: rgba(255, 253, 248, 0.95);
            border: 2px solid rgba(21, 34, 47, 0.15);
            border-radius: 12px;
            color: #15222F;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input::placeholder {
            color: #8a9ba8;
        }

        input:focus {
            outline: none;
            border-color: #547792;
            background: rgba(255, 253, 248, 1);
            box-shadow: 0 0 0 3px rgba(84, 119, 146, 0.15);
        }

        /* Error Messages */
        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 6px;
            display: block;
        }

        /* Button */
        .btn {
            width: 100%;
            background: linear-gradient(to right, #15222F, #2d4053);
            color: #eaf0f6;
            padding: 18px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .btn:hover {
            background: linear-gradient(to right, #1e2f3f, #3a5065);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(21, 34, 47, 0.25);
        }

        /* Link */
        .login-link {
            margin-top: 25px;
            color: #5a6c7d;
            font-size: 0.95rem;
        }

        .login-link a {
            color: #547792;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .login-link a:hover {
            color: #15222F;
            text-decoration: underline;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #547792;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #15222F;
            text-decoration: underline;
        }

        .back-link i {
            margin-right: 6px;
        }

        /* Partner Badge */
        .partner-badge {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(21, 34, 47, 0.1);
            font-size: 0.85rem;
            color: #5a6c7d;
        }

        .partner-logo {
            height: 30px;
            margin-top: 8px;
            filter: grayscale(1) opacity(0.7);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .auth-container {
                padding: 35px 30px;
                width: 95%;
                margin: 20px auto;
            }

            .logo {
                font-size: 2.5rem;
            }

            .subtitle {
                font-size: 0.95rem;
                margin-bottom: 30px;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 15px;
                align-items: flex-start;
                padding-top: 40px;
            }

            .auth-container {
                padding: 30px 25px;
                width: 100%;
                border-radius: 20px;
            }

            .logo {
                font-size: 2.2rem;
            }

            input {
                padding: 16px 18px;
            }

            .btn {
                padding: 16px;
            }
        }

        @media (max-width: 400px) {
            .auth-container {
                padding: 25px 20px;
            }

            .logo {
                font-size: 2rem;
            }

            .subtitle {
                font-size: 0.9rem;
            }
        }

        /* Password Toggle */
        .input-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #547792;
            font-size: 1.1rem;
            cursor: pointer;
            user-select: none;
        }

    </style>

    <!-- Background Container -->
    <div class="background-container">
        <div class="background-overlay"></div>
        <!-- Gambar latar dari Unsplash dengan tema magang/kerja/kantor -->
        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
            alt="Background" class="background-image">
    </div>

    <!-- Form Container -->
    <div class="auth-container">
        <div class="logo">
            <span>Intern</span><span>Flow</span>
        </div>

        <p class="subtitle">Daftar Akun - Sistem Monitoring Magang Diskominfo Surakarta</p>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <!-- Nama Lengkap -->
            <div class="form-group">
                <label>Nama Lengkap</label>
                <div class="input-container">
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                        placeholder="Masukkan nama lengkap anda">
                </div>
                @error('name')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label>Email</label>
                <div class="input-container">
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="Masukkan email anda">
                </div>
                @error('email')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label>Password</label>
                <div class="input-container">
                    <input type="password" name="password" id="password" required placeholder="Masukkan password">
                    <span class="input-icon" id="togglePassword">
                        👁
                    </span>
                </div>
                @error('password')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <div class="input-container">
                    <input type="password" name="password_confirmation" id="passwordConfirmation" required 
                        placeholder="Masukkan ulang password">
                    <span class="input-icon" id="togglePasswordConfirmation">
                        👁
                    </span>
                </div>
            </div>

            <button type="submit" class="btn">Daftar Akun</button>
        </form>

        <p class="login-link">
            Sudah punya akun?
            <a href="{{ route('login') }}">Login di sini</a>
        </p>

        <a href="/" class="back-link">
            <i>←</i> Kembali ke Dashboard Utama
        </a>

        <div class="partner-badge">
            Didukung oleh
            <div>
                <!-- Logo Diskominfo Surakarta (placeholder) -->
                <img src="https://diskominfosp.surakarta.go.id/public/displayFileFe/setting/4cdb646e-0344-4342-abed-d19dc8ea2aaf.png"
                    alt="Diskominfo Surakarta" class="partner-logo">
            </div>
        </div>
    </div>

    <!-- Script untuk mengubah background secara acak (opsional) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Array gambar latar yang sesuai dengan tema
            const backgroundImages = [
                'https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80', // Kantor modern
                'https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80', // Team meeting
                'https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80', // Ruang kerja
                'https://images.unsplash.com/photo-1542744095-fcf48d80b0fd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2056&q=80' // Diskusi kerja
            ];

            // Pilih gambar latar secara acak
            const randomImage = backgroundImages[Math.floor(Math.random() * backgroundImages.length)];
            document.querySelector('.background-image').src = randomImage;

            // Efek interaktif pada input
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                // Tambahkan class saat input difokuskan
                input.addEventListener('focus', function () {
                    this.parentElement.classList.add('focused');
                });

                // Hapus class saat input kehilangan fokus
                input.addEventListener('blur', function () {
                    this.parentElement.classList.remove('focused');
                });

                // Efek saat hover
                input.addEventListener('mouseenter', function () {
                    this.style.borderColor = 'rgba(84, 119, 146, 0.4)';
                });

                input.addEventListener('mouseleave', function () {
                    if (!this.matches(':focus')) {
                        this.style.borderColor = 'rgba(21, 34, 47, 0.15)';
                    }
                });
            });

            // Validasi form sederhana
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                const name = form.querySelector('input[name="name"]');
                const email = form.querySelector('input[name="email"]');
                const password = form.querySelector('input[name="password"]');
                const passwordConfirmation = form.querySelector('input[name="password_confirmation"]');

                if (!name.value || !email.value || !password.value || !passwordConfirmation.value) {
                    e.preventDefault();
                    alert('Harap isi semua field yang diperlukan.');
                    return;
                }

                if (password.value !== passwordConfirmation.value) {
                    e.preventDefault();
                    alert('Password dan Konfirmasi Password tidak cocok.');
                    return;
                }

                const hasUppercase = /[A-Z]/.test(password.value);
                const hasLowercase = /[a-z]/.test(password.value);
                const hasNumber = /[0-9]/.test(password.value);
                const hasSymbol = /[^A-Za-z0-9]/.test(password.value);

                if (password.value.length < 8) {
                    e.preventDefault();
                    alert('Password harus minimal 8 karakter.');
                    return;
                }

                if (!hasUppercase) {
                    e.preventDefault();
                    alert('Password harus mengandung huruf Besar.');
                    return;
                }

                if (!hasLowercase) {
                    e.preventDefault();
                    alert('Password harus mengandung huruf Kecil.');
                    return;
                }

                if (!hasNumber) {
                    e.preventDefault();
                    alert('Password harus mengandung Angka.');
                    return;
                }

                  if (!hasSymbol) {
                    e.preventDefault();
                    alert('Password harus mengandung Simbol.');
                    return;
                }
            });

            // Toggle Password Visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁' : '🙈';
            });

            // Toggle Password Confirmation Visibility
            const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
            const passwordConfirmationInput = document.getElementById('passwordConfirmation');
            
            togglePasswordConfirmation.addEventListener('click', function () {
                const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmationInput.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁' : '🙈';
            });
        });
    </script>
</x-guest-layout>