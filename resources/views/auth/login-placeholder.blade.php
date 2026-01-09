<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - InternFlow</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #547792 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgb(33, 27, 27);
            margin: 0;
            padding: 20px;
        }
        .auth-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .logo {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .logo span:nth-child(1) { color: #94B4C1; }
        .logo span:nth-child(2) { color: white; }
        h1 {
            font-size: 1.5rem;
            margin: 20px 0 10px;
            color: #EAE0CF;
        }
        .subtitle {
            font-size: 1.1rem;
            opacity: 0.85;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #EAE0CF;
        }
        input {
            width: 100%;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.388);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
            box-sizing: border-box;
        }
        input:focus {
            outline: none;
            border-color: #ffffff;
            background: rgba(255, 255, 255, 0.2);
        }
        input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        .btn {
            display: inline-block;
            width: 100%;
            background: linear-gradient(to right,#15222F);
            color: #eaf0f6;
            text-decoration: none;
            padding: 18px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(148, 180, 193, 0.4);
        }
        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #15222F;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        .back-link:hover {
            color: #EAE0CF;
            text-decoration: underline;
        }
        .alert {
            background: rgba(234, 224, 207, 0.1);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 1px solid rgba(234, 224, 207, 0.3);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="logo">
            <span>Intern</span><span>Flow</span>
        </div>
        <h1>Halaman Login</h1>
    
        
        <!-- Form Simulasi Login -->
        <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Sistem login penuh akan segera diimplementasikan!');">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="your@example.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn">Login ke Sistem</button>
        </form>
        
        <p style="margin-top: 25px; opacity: 0.7;">Belum punya akun? <a href="/register" style="color: #180065; text-decoration: none;">Daftar disini</a></p>
        
        <a href="/" class="back-link">← Kembali ke Dashboard Utama</a>
    </div>
    
    <script>
        // Animasi sederhana untuk input focus
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Auto-fill demo credentials pada klik
        document.getElementById('email').addEventListener('click', function() {
            if(this.value === '') {
                this.value = 'admin@diskominfo.surakarta.go.id';
            }
        });
    </script>
</body>
</html>