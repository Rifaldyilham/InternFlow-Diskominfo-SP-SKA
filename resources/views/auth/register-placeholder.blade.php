<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - InternFlow</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #9b7e53 0%, #547792 100%);
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
            backdrop-filter: blur(20px);
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 520px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .logo span:nth-child(1) {
            color: #94B4C1;
        }

        .logo span:nth-child(2) {
            color: white;
        }

        h1 {
            font-size: 2rem;
            margin: 20px 0 10px;
            color: #EAE0CF;
        }

        .subtitle {
            font-size: 1.05rem;
            opacity: 0.85;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 22px;
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
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        input:focus {
            outline: none;
            border-color: #94B4C1;
            background: rgba(255, 255, 255, 0.2);
        }

        .btn {
            width: 100%;
            background: linear-gradient(to right, #94B4C1, #547792);
            color: #213448;
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

        .info-box {
            background: rgba(148, 180, 193, 0.2);
            border-left: 4px solid #94B4C1;
            padding: 18px;
            border-radius: 12px;
            text-align: left;
            margin: 25px 0;
            font-size: 0.95rem;
        }

        .password-requirements {
            background: rgba(33, 52, 72, 0.3);
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
            font-size: 0.85rem;
            color: #EAE0CF;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #94B4C1;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            color: #EAE0CF;
            text-decoration: underline;
        }

    </style>
    <script>
        function checkPassword() {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('confirm').value;

            const rules = {
                length: pass.length >= 8,
                upper: /[A-Z]/.test(pass) && /[a-z]/.test(pass),
                number: /\d/.test(pass),
                match: pass === confirm && pass !== ''
            };

            updateRule('length', rules.length);
            updateRule('upper', rules.upper);
            updateRule('number', rules.number);
            updateRule('match', rules.match);

            return Object.values(rules).every(v => v);
        }

        function updateRule(id, valid) {
            const el = document.getElementById(id);
            const text = el.dataset.text;

            el.innerHTML = valid ? `✓ ${text}` : `• ${text}`;
            el.style.color = valid ? '#94B4C1' : '#EAE0CF';
        }

        function handleRegister() {
            if (!checkPassword()) {
                alert('❌ Password belum memenuhi semua syarat!');
                return;
            }

            const btn = document.getElementById('btn');
            btn.innerText = '⏳ Memproses...';
            btn.disabled = true;

            const nama = document.getElementById('nama').value;
            const email = document.getElementById('email').value;

            setTimeout(() => {
                showSuccessPopup(nama, email);
                btn.innerText = '🚀 Buat Akun';
                btn.disabled = false;
                document.querySelector('form').reset();
                resetRules();
            }, 1200);
        }

        function resetRules() {
            ['length', 'upper', 'number', 'match'].forEach(id => {
                const el = document.getElementById(id);
                el.innerHTML = `• ${el.dataset.text}`;
                el.style.color = '#EAE0CF';
            });
        }

        function showSuccessPopup(nama, email) {
            const popup = `
        <div id="successModal" style="
            position:fixed; inset:0;
            background:rgba(0,0,0,.7);
            display:flex;
            align-items:center;
            justify-content:center;
            z-index:9999;">
            
            <div style="
                background:#213448;
                padding:40px;
                border-radius:20px;
                text-align:center;
                max-width:420px;
                width:90%;
                border:2px solid #94B4C1;">
                
                <div style="font-size:3.5rem">🎉</div>
                <h2 style="color:#94B4C1">Registrasi Berhasil</h2>
                <p>Selamat <strong>${nama}</strong></p>
                <p style="opacity:.8">${email}</p>

                <div style="margin-top:25px; display:flex; gap:10px">
                    <button onclick="location.href='/login'"
                        style="flex:1; padding:12px;
                        border:none; background:#94B4C1;
                        color:#213448; font-weight:bold;
                        border-radius:10px">
                        Login
                    </button>

                    <button onclick="document.getElementById('successModal').remove()"
                        style="flex:1; padding:12px;
                        border:2px solid #94B4C1;
                        background:transparent;
                        color:#94B4C1;
                        border-radius:10px">
                        Tutup
                    </button>
                </div>
            </div>
        </div>`;
            document.body.insertAdjacentHTML('beforeend', popup);
        }

    </script>


</head>

<body>
    <div class="auth-container">
        <div class="logo">
            <span>Intern</span><span>Flow</span>
        </div>

        <h1>📝 Registrasi Akun</h1>
        <p class="subtitle">Daftarkan akun peserta magang untuk mengakses sistem InternFlow</p>

        <div class="info-box">
            <strong>ℹ️ Informasi:</strong>
            <ul>
                <li>Akun hanya untuk peserta magang</li>
                <li>Email harus aktif</li>
                <li>Mentor & Admin dibuat oleh sistem</li>
            </ul>
        </div>

        <form onsubmit="event.preventDefault(); handleRegister();">

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" id="nama" placeholder="Nama sesuai identitas" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" id="email" placeholder="email@example.com" required>
            </div>

            <div class="form-group">
                <label>No WhatsApp</label>
                <input type="text" placeholder="08xx-xxxx-xxxx" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" id="password" placeholder="Minimal 8 karakter" onkeyup="checkPassword()"
                    required>
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" id="confirm" placeholder="Ulangi password" onkeyup="checkPassword()" required>
            </div>

            <div class="password-requirements">
                <strong>📋 Syarat Password:</strong>
                <div id="length" data-text="Minimal 8 karakter">• Minimal 8 karakter</div>
                <div id="upper" data-text="Huruf besar & kecil">• Huruf besar & kecil</div>
                <div id="number" data-text="Mengandung angka">• Mengandung angka</div>
                <div id="match" data-text="Password cocok">• Password cocok</div>
            </div>


            <button class="btn" id="btn">🚀 Buat Akun</button>
        </form>

        <p style="margin-top:25px; opacity:.7">
            Sudah punya akun?
            <a href="/login" style="color:#94B4C1;">Login disini</a>
        </p>

        <a href="/" class="back-link">← Kembali ke Dashboard Utama</a>
    </div>
</body>

</html>
