<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Profil Siswa - IP School</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            background: white; 
            padding: 40px; 
            border-radius: 16px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
        }
        
        .header-section h1 {
            color: #111827;
            font-size: 28px;
            margin: 0;
        }
        
        .logout-btn {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        
        .logout-btn:hover {
            background-color: #dc2626;
        }
        
        .profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 40px;
            color: white;
            text-align: center;
            margin-bottom: 40px;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
        }
        
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background: rgba(255, 255, 255, 0.2);
            border: 4px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            font-size: 50px;
        }
        
        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile-card h2 {
            font-size: 24px;
            margin: 0 0 10px 0;
            font-weight: 700;
        }
        
        .profile-card p {
            font-size: 14px;
            opacity: 0.9;
            margin: 5px 0;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .info-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.08);
        }
        
        .info-card-label {
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }
        
        .info-card-value {
            color: #111827;
            font-size: 20px;
            font-weight: 700;
        }
        
        .action-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background-color: #2563eb;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.3);
        }
        
        .btn-secondary {
            background-color: #e5e7eb;
            color: #111827;
        }
        
        .btn-secondary:hover {
            background-color: #d1d5db;
            transform: translateY(-2px);
        }
        
        .divider {
            border: 0;
            border-top: 2px solid #f0f0f0;
            margin: 30px 0;
        }
        
        @media (max-width: 600px) {
            .container { padding: 20px; }
            .profile-card { padding: 20px; }
            .profile-image { width: 100px; height: 100px; font-size: 40px; }
            .header-section { flex-direction: column; gap: 15px; align-items: flex-start; }
            .header-section h1 { font-size: 22px; }
            .info-grid { grid-template-columns: 1fr; }
            .action-buttons { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
    @include('partials.header-home')
    
    <div class="container">
        <div class="header-section">
            <h1>📋 Profil Saya</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-image">
                @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" alt="Foto {{ $student->fullname }}">
                @else
                    <span>👤</span>
                @endif
            </div>
            <h2>{{ $student->fullname }}</h2>
            <p>Siswa Aktif</p>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-card">
                <span class="info-card-label">📚 NIS</span>
                <div class="info-card-value">{{ $student->nis }}</div>
            </div>
            
            <div class="info-card">
                <span class="info-card-label">🏫 Kelas</span>
                <div class="info-card-value">{{ $student->class_grade }}</div>
            </div>
            
            <div class="info-card">
                <span class="info-card-label">✉️ Email</span>
                <div class="info-card-value" style="font-size: 14px; word-break: break-all;">
                    {{ $student->email ?? '-' }}
                </div>
            </div>
        </div>

        <hr class="divider">

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('student.index') }}" class="btn btn-primary">
                📚 Pilih Mata Pelajaran
            </a>
            <button class="btn btn-secondary" onclick="alert('Fitur edit profil akan segera hadir')">
                ✏️ Edit Profil
            </button>
        </div>
    </div>
</body>
</html>
