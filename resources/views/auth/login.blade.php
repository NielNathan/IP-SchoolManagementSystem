<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - IP School</title>
    <style>
        :root{--bg:#f3f4f6;--card:#ffffff;--accent:#2563eb;--muted:#6b7280}
        html,body{height:100%;margin:0;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial}
        body{background:linear-gradient(180deg, #eef2ff 0%, var(--bg) 100%);display:flex;align-items:center;justify-content:center}
        .container{width:100%;max-width:420px;padding:24px}
        /* center the form content and constrain width so inputs appear centered */
        .form-inner{width:100%;max-width:340px;margin:0 auto;display:flex;flex-direction:column;gap:12px}
        .card{background:var(--card);border-radius:12px;box-shadow:0 6px 24px rgba(15,23,42,0.08);padding:28px}
        h1{margin:0 0 6px;font-size:20px}
        p.lead{margin:0 0 18px;color:var(--muted);font-size:14px}
        label{display:block;font-size:13px;margin-bottom:6px;color:#111827}
        input[type=text],input[type=password]{width:100%;padding:10px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px}
        .actions{display:flex;align-items:center;justify-content:space-between;margin-top:14px}
        .btn{background:var(--accent);color:white;padding:10px 14px;border-radius:8px;border:0;cursor:pointer}
        .btn.secondary{background:#e5e7eb;color:#111827}
        .helper{font-size:13px;color:var(--muted)}
        .error{color:#b91c1c;font-size:13px;margin-top:8px}
        .footer{margin-top:14px;text-align:center;font-size:13px;color:var(--muted)}
        .remember{display:flex;align-items:center;gap:8px}
        @media (max-width:480px){.container{padding:16px}.card{padding:20px}}
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Masuk ke IP School</h1>
            <p class="lead">Silakan masuk menggunakan nama dan kata sandi Anda.</p>

            @if(session('success'))
                <div style="background:#ecfdf5;color:#065f46;padding:10px;border-radius:8px;margin-bottom:12px">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div style="background:#fff1f2;color:#7f1d1d;padding:10px;border-radius:8px;margin-bottom:12px">
                    <strong>Terdapat kesalahan:</strong>
                    <ul style="margin:6px 0 0;padding-left:18px">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('/login_process') }}" class="form-inner">
                @csrf
                <div style="margin-bottom:12px">
                    <label for="name">Nama</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Masukkan nama" required>
                </div>

                <div style="margin-bottom:6px">
                    <label for="password">Kata Sandi</label>
                    <input id="password" name="password" type="password" placeholder="Masukkan kata sandi" required>
                </div>

                <div class="actions">
                    <label class="remember"><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Ingat saya</label>
                    <button class="btn" type="submit">Masuk</button>
                </div>
            </form>

            <div class="footer">
                <div style="margin-top:12px">Belum punya akun? <a href="{{ url('/register') }}">Daftar</a></div>
            </div>
        </div>
    </div>
</body>
</html>
