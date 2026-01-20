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

        /* Validasi password error messages */
        .validation-message {
            color: #e53e3e;
            font-size: 0.8rem;
            margin-top: 6px;
            display: block;
            text-align: left;
            animation: fadeIn 0.3s ease;
        }

        .validation-success {
            color: #38a169;
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

        /* Input Error State */
        .input-error {
            border-color: #e53e3e !important;
        }

        .input-success {
            border-color: #38a169 !important;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Password Requirements List */
        .password-requirements {
            margin-top: 10px;
            padding: 12px;
            background: rgba(245, 248, 250, 0.8);
            border-radius: 8px;
            text-align: left;
            font-size: 0.85rem;
            color: #5a6c7d;
            border-left: 3px solid #547792;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .requirement-item:last-child {
            margin-bottom: 0;
        }

        .requirement-check {
            margin-right: 8px;
            font-size: 0.9rem;
            width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .requirement-met {
            color: #38a169;
        }

        .requirement-not-met {
            color: #e53e3e;
        }

        .requirement-text {
            flex: 1;
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

        <form method="POST" action="{{ route('register.store') }}" id="registerForm">
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
                <!-- Container untuk pesan validasi password -->
                <div id="passwordValidationMessages"></div>
                
                <!-- Daftar persyaratan password -->
                <div class="password-requirements">
                    <div class="requirement-item">
                        <span class="requirement-check" id="reqLength">❌</span>
                        <span class="requirement-text">Minimal 8 karakter</span>
                    </div>
                    <div class="requirement-item">
                        <span class="requirement-check" id="reqUppercase">❌</span>
                        <span class="requirement-text">Mengandung huruf besar</span>
                    </div>
                    <div class="requirement-item">
                        <span class="requirement-check" id="reqLowercase">❌</span>
                        <span class="requirement-text">Mengandung huruf kecil</span>
                    </div>
                    <div class="requirement-item">
                        <span class="requirement-check" id="reqNumber">❌</span>
                        <span class="requirement-text">Mengandung angka</span>
                    </div>
                    <div class="requirement-item">
                        <span class="requirement-check" id="reqSymbol">❌</span>
                        <span class="requirement-text">Mengandung simbol</span>
                    </div>
                </div>
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
                <!-- Container untuk pesan validasi konfirmasi password -->
                <div id="passwordConfirmationMessages"></div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Array gambar latar yang sesuai dengan tema
            const backgroundImages = [
                'https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80',
                'https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80',
                'https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80',
                'https://images.unsplash.com/photo-1542744095-fcf48d80b0fd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2056&q=80'
            ];

            // Pilih gambar latar secara acak
            const randomImage = backgroundImages[Math.floor(Math.random() * backgroundImages.length)];
            document.querySelector('.background-image').src = randomImage;

            // Elemen yang diperlukan
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('passwordConfirmation');
            const passwordValidationMessages = document.getElementById('passwordValidationMessages');
            const passwordConfirmationMessages = document.getElementById('passwordConfirmationMessages');
            const registerForm = document.getElementById('registerForm');

            // Toggle Password Visibility
            const togglePassword = document.getElementById('togglePassword');
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁' : '🔒';
            });

            // Toggle Password Confirmation Visibility
            const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
            togglePasswordConfirmation.addEventListener('click', function () {
                const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmationInput.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁' : '🔒';
            });

            // Fungsi untuk menampilkan pesan validasi
            function showValidationMessage(container, message, isError = true) {
                container.innerHTML = '';
                const messageElement = document.createElement('span');
                messageElement.className = isError ? 'validation-message' : 'validation-message validation-success';
                messageElement.textContent = message;
                container.appendChild(messageElement);
                
                // Hapus pesan setelah 5 detik
                setTimeout(() => {
                    if (container.contains(messageElement)) {
                        container.innerHTML = '';
                    }
                }, 5000);
            }

            // Fungsi untuk memeriksa kekuatan password
            function checkPasswordStrength(password) {
                const requirements = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    symbol: /[^A-Za-z0-9]/.test(password)
                };

                // Update tampilan checklist
                document.getElementById('reqLength').textContent = requirements.length ? '✅' : '❌';
                document.getElementById('reqUppercase').textContent = requirements.uppercase ? '✅' : '❌';
                document.getElementById('reqLowercase').textContent = requirements.lowercase ? '✅' : '❌';
                document.getElementById('reqNumber').textContent = requirements.number ? '✅' : '❌';
                document.getElementById('reqSymbol').textContent = requirements.symbol ? '✅' : '❌';

                // Update kelas untuk styling
                document.getElementById('reqLength').className = requirements.length ? 'requirement-check requirement-met' : 'requirement-check requirement-not-met';
                document.getElementById('reqUppercase').className = requirements.uppercase ? 'requirement-check requirement-met' : 'requirement-check requirement-not-met';
                document.getElementById('reqLowercase').className = requirements.lowercase ? 'requirement-check requirement-met' : 'requirement-check requirement-not-met';
                document.getElementById('reqNumber').className = requirements.number ? 'requirement-check requirement-met' : 'requirement-check requirement-not-met';
                document.getElementById('reqSymbol').className = requirements.symbol ? 'requirement-check requirement-met' : 'requirement-check requirement-not-met';

                // Validasi real-time
                passwordValidationMessages.innerHTML = '';
                
                if (password.length > 0) {
                    if (!requirements.length) {
                        showValidationMessage(passwordValidationMessages, 'Password harus minimal 8 karakter');
                        passwordInput.classList.add('input-error');
                        passwordInput.classList.remove('input-success');
                    } else if (!requirements.uppercase) {
                        showValidationMessage(passwordValidationMessages, 'Password harus mengandung huruf besar');
                        passwordInput.classList.add('input-error');
                        passwordInput.classList.remove('input-success');
                    } else if (!requirements.lowercase) {
                        showValidationMessage(passwordValidationMessages, 'Password harus mengandung huruf kecil');
                        passwordInput.classList.add('input-error');
                        passwordInput.classList.remove('input-success');
                    } else if (!requirements.number) {
                        showValidationMessage(passwordValidationMessages, 'Password harus mengandung angka');
                        passwordInput.classList.add('input-error');
                        passwordInput.classList.remove('input-success');
                    } else if (!requirements.symbol) {
                        showValidationMessage(passwordValidationMessages, 'Password harus mengandung simbol');
                        passwordInput.classList.add('input-error');
                        passwordInput.classList.remove('input-success');
                    } else {
                        // Semua persyaratan terpenuhi
                        showValidationMessage(passwordValidationMessages, 'Password kuat dan memenuhi semua persyaratan', false);
                        passwordInput.classList.remove('input-error');
                        passwordInput.classList.add('input-success');
                    }
                } else {
                    passwordInput.classList.remove('input-error', 'input-success');
                    passwordValidationMessages.innerHTML = '';
                }

                return Object.values(requirements).every(req => req);
            }

            // Fungsi untuk memeriksa konfirmasi password
            function checkPasswordConfirmation(password, confirmation) {
                passwordConfirmationMessages.innerHTML = '';
                
                if (confirmation.length === 0) {
                    passwordConfirmationInput.classList.remove('input-error', 'input-success');
                    return false;
                }
                
                if (password !== confirmation) {
                    showValidationMessage(passwordConfirmationMessages, 'Password tidak cocok');
                    passwordConfirmationInput.classList.add('input-error');
                    passwordConfirmationInput.classList.remove('input-success');
                    return false;
                } else {
                    if (password.length > 0) {
                        showValidationMessage(passwordConfirmationMessages, 'Password cocok', false);
                        passwordConfirmationInput.classList.remove('input-error');
                        passwordConfirmationInput.classList.add('input-success');
                    }
                    return true;
                }
            }

            // Event listener untuk validasi real-time password
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                checkPasswordConfirmation(this.value, passwordConfirmationInput.value);
            });

            // Event listener untuk validasi real-time konfirmasi password
            passwordConfirmationInput.addEventListener('input', function() {
                checkPasswordConfirmation(passwordInput.value, this.value);
            });

            // Validasi form sebelum submit
            registerForm.addEventListener('submit', function(e) {
                let isValid = true;
                let errorMessages = [];
                
                // Validasi nama
                const nameInput = this.querySelector('input[name="name"]');
                if (!nameInput.value.trim()) {
                    isValid = false;
                    showValidationMessage(nameInput.parentElement.parentElement, 'Nama lengkap harus diisi');
                    nameInput.classList.add('input-error');
                } else {
                    nameInput.classList.remove('input-error');
                }
                
                // Validasi email
                const emailInput = this.querySelector('input[name="email"]');
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailInput.value.trim()) {
                    isValid = false;
                    showValidationMessage(emailInput.parentElement.parentElement, 'Email harus diisi');
                    emailInput.classList.add('input-error');
                } else if (!emailPattern.test(emailInput.value)) {
                    isValid = false;
                    showValidationMessage(emailInput.parentElement.parentElement, 'Format email tidak valid');
                    emailInput.classList.add('input-error');
                } else {
                    emailInput.classList.remove('input-error');
                }
                
                // Validasi password
                const passwordIsValid = checkPasswordStrength(passwordInput.value);
                if (!passwordIsValid) {
                    isValid = false;
                    showValidationMessage(passwordValidationMessages, 'Password belum memenuhi semua persyaratan');
                    passwordInput.classList.add('input-error');
                }
                
                // Validasi konfirmasi password
                const passwordConfirmationIsValid = checkPasswordConfirmation(passwordInput.value, passwordConfirmationInput.value);
                if (!passwordConfirmationIsValid) {
                    isValid = false;
                    showValidationMessage(passwordConfirmationMessages, 'Password tidak cocok');
                    passwordConfirmationInput.classList.add('input-error');
                }
                
                // Jika tidak valid, cegah submit
                if (!isValid) {
                    e.preventDefault();
                    
                    // Scroll ke field pertama yang error
                    const firstError = document.querySelector('.input-error');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });

            // Hapus pesan error saat input difokuskan
            const allInputs = document.querySelectorAll('input');
            allInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.classList.remove('input-error');
                    
                    // Hapus pesan error di container yang sesuai
                    if (this.id === 'password') {
                        passwordValidationMessages.innerHTML = '';
                    } else if (this.id === 'passwordConfirmation') {
                        passwordConfirmationMessages.innerHTML = '';
                    } else {
                        const parentFormGroup = this.closest('.form-group');
                        const existingMessages = parentFormGroup.querySelectorAll('.validation-message');
                        existingMessages.forEach(msg => msg.remove());
                    }
                });
                
                // Efek hover
                input.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('input-error') && !this.classList.contains('input-success')) {
                        this.style.borderColor = 'rgba(84, 119, 146, 0.4)';
                    }
                });
                
                input.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('input-error') && !this.classList.contains('input-success') && !this.matches(':focus')) {
                        this.style.borderColor = 'rgba(21, 34, 47, 0.15)';
                    }
                });
            });
        });
    </script>
</x-guest-layout>