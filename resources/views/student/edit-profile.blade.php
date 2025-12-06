@extends('layouts.app')

@section('title', 'Edit Profil')

@section('custom-styles')
h1 {
    color: #111827;
    font-size: 28px;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 24px;
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #111827;
    font-size: 14px;
}

input[type="text"],
input[type="email"],
input[type="tel"],
input[type="file"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="tel"]:focus,
input[type="file"]:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.photo-section {
    display: flex;
    gap: 24px;
    align-items: flex-start;
    margin-bottom: 24px;
    padding: 20px;
    background: #f9fafb;
    border-radius: 12px;
}

.photo-preview {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
    font-size: 48px;
}

.photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-upload {
    flex: 1;
}

.photo-upload label {
    margin-bottom: 8px;
}

.button-group {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 40px;
}

.btn {
    padding: 12px 28px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: #2563eb;
    color: white;
}

.btn-primary:hover {
    background: #1e40af;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-secondary {
    background: white;
    color: #2563eb;
    border: 2px solid #2563eb;
}

.btn-secondary:hover {
    background: #eff6ff;
}

.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    font-size: 14px;
}

.alert-success {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #d1fae5;
}

.alert-error {
    background: #fff1f2;
    color: #7f1d1d;
    border: 1px solid #fecaca;
}

.error-text {
    color: #b91c1c;
    font-size: 13px;
    margin-top: 6px;
}

.divider {
    border: 0;
    border-top: 2px solid #f0f0f0;
    margin: 30px 0;
}

.card { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); max-width: 700px; margin: 0 auto; }

@media (max-width: 600px) {
    .card { padding: 20px; }
    .photo-section { flex-direction: column; align-items: center; }
    .button-group { flex-direction: column; }
    .btn { width: 100%; }
}
@endsection

@section('content')
<div class="card">
    <h1>✏️ Edit Profil</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <strong>Terdapat kesalahan:</strong>
            <ul style="margin-top: 8px; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('student.update-profile') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Photo Section -->
        <div class="photo-section">
            <div class="photo-preview">
                @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" alt="Foto {{ $student->fullname }}">
                @else
                    <span>👤</span>
                @endif
            </div>
            <div class="photo-upload">
                <label for="photo">📸 Foto Profil</label>
                <input type="file" id="photo" name="photo" accept="image/*">
                <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                    Maksimal 2MB. Format: JPEG, PNG, JPG, GIF
                </p>
                @error('photo')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <hr class="divider">

        <!-- Personal Information -->
        <div class="form-group">
            <label for="fullname">📝 Nama Lengkap</label>
            <input type="text" id="fullname" name="fullname" value="{{ old('fullname', $student->fullname) }}" required>
            @error('fullname')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">📧 Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">📞 Nomor Telepon</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Opsional">
            @error('phone')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <hr class="divider">

        <!-- Read-only Information -->
        <div style="background: #f9fafb; padding: 20px; border-radius: 12px; margin-bottom: 24px;">
            <h3 style="font-size: 14px; font-weight: 600; margin-bottom: 16px; color: #111827;">📌 Informasi Tidak Dapat Diubah</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <p style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">NIS</p>
                    <p style="font-size: 14px; font-weight: 600; color: #111827;">{{ $student->nis }}</p>
                </div>
                <div>
                    <p style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Kelas</p>
                    <p style="font-size: 14px; font-weight: 600; color: #111827;">{{ $student->class_grade }}</p>
                </div>
            </div>
        </div>

        <div class="button-group">
            <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                ❌ Batal
            </a>
            <button type="submit" class="btn btn-primary">
                ✅ Simpan Perubahan
            </button>
        </div>
        </form>
    </div>
@endsection
