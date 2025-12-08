@extends('layouts.auth')

@section('title', 'Daftar - IP School')

@section('custom-styles')
:root{--bg:#f3f4f6;--card:#ffffff;--accent:#2563eb;--muted:#6b7280}
.card{background:var(--card);border-radius:12px;box-shadow:0 6px 24px rgba(15,23,42,0.08);padding:28px}
h1{margin:0 0 6px;font-size:20px}
p.lead{margin:0 0 18px;color:var(--muted);font-size:14px}
label{display:block;font-size:13px;margin-bottom:6px;color:#111827}
input[type=text],input[type=password],input[type=email],select{width:100%;padding:10px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;box-sizing:border-box}
.form-inner{width:100%;max-width:340px;margin:0 auto;display:flex;flex-direction:column;gap:12px}
.actions{display:flex;align-items:center;justify-content:space-between;margin-top:14px}
.btn{background:var(--accent);color:white;padding:10px 14px;border-radius:8px;border:0;cursor:pointer;width:100%;font-weight:600}
.footer{margin-top:14px;text-align:center;font-size:13px;color:var(--muted)}
@media (max-width:480px){.card{padding:20px}}
@endsection

@section('content')
<div class="card">
    <h1>Buat Akun Siswa</h1>
    <p class="lead">Isi data diri lengkap untuk mendaftar.</p>

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

    <form method="POST" action="{{ route('register.process') }}" class="form-inner">
        @csrf
        
        <div>
            <label for="fullname">Nama Lengkap</label>
            <input id="fullname" name="fullname" type="text" value="{{ old('fullname') }}" required autofocus>
        </div>

        <div>
            <label for="nis">NIS (Nomor Induk Siswa)</label>
            <input id="nis" name="nis" type="text" value="{{ old('nis') }}" required>
        </div>

        <div>
            <label for="class_grade">Kelas</label>
            <select name="class_grade" id="class_grade" required>
                <option value="">-- Pilih Kelas --</option>
                
                <option value="X-1">X-1</option>
                <option value="X-2">X-2</option>
                <option value="X-3">X-3</option>

                <option value="XI-1">XI-1</option>
                <option value="XI-2">XI-2</option>
                <option value="XI-3">XI-3</option>

                <option value="XII-1">XII-1</option>
                <option value="XII-2">XII-2</option>
                <option value="XII-3">XII-3</option>
            </select>
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@school.com" required>
        </div>

        <div>
            <label for="password">Kata Sandi</label>
            <input id="password" name="password" type="password" placeholder="Minimal 6 karakter" required>
        </div>

        <div class="actions">
            <button class="btn" type="submit">Daftar Sekarang</button>
        </div>
    </form>

    <div class="footer">
        <div style="margin-top:12px">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></div>
    </div>
</div>
@endsection