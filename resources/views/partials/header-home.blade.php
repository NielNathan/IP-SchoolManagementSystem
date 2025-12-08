<header class="site-header-home">
    <div class="header-inner">
        <a class="brand" href="{{ url('/') }}">
            @if (file_exists(public_path('images/logo.svg')))
                {{-- inline SVG may already include the school name, so do not duplicate text --}}
                {!! file_get_contents(public_path('images/logo.svg')) !!}
            @elseif (file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'IP School') }} logo">
                <span class="school-name">{{ config('app.name', 'IP School') }}</span>
            @else
                <span class="school-name">{{ config('app.name', 'IP School') }}</span>
            @endif
        </a>
        
        <!-- Navigation Menu for Students -->
        @auth
            @if(Auth::user()->role === 'student' || Auth::user()->role === null)
                <nav class="nav-menu">
                    <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">📋 Profil</a>
                    <a href="{{ route('student.pilih') }}" class="nav-item {{ request()->routeIs('student.pilih') ? 'active' : '' }}">📚 Pilih Mapel</a>
                    <a href="{{ route('student.materi') }}" class="nav-item {{ request()->routeIs('student.materi') ? 'active' : '' }}">📖 Materi</a>
                    <a href="{{ route('student.edit-profile') }}" class="nav-item {{ request()->routeIs('student.edit-profile') ? 'active' : '' }}">✏️ Edit Profile</a>
                </nav>
            @endif
        @endauth
        
        <!-- User Menu -->
        @auth
            <div class="user-section">
                <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        @endauth
    </div>

    <style>
        /* header with blue background and fixed on top - allows scrolling */
        .site-header-home{
            position:fixed;
            left:0;right:0;top:0;z-index:60;
            display:flex;justify-content:center;align-items:center;
            background:#2563eb;
            padding:8px 16px;
            box-shadow:0 2px 8px rgba(0,0,0,0.08);
        }
        .header-inner{
            width:100%;
            max-width:1200px;
            display:flex;
            align-items:center;
            gap:20px;
            justify-content:space-between;
        }
        .brand{
            display:flex;
            align-items:center;
            gap:12px;
            text-decoration:none;
            flex-shrink:0;
        }
        .brand img{
            height:64px;
            width:auto;
            display:block;
            border-radius:6px;
        }
        .brand svg{
            width:auto;
            height:64px;
            display:block;
        }
        .school-name{
            font-size:18px;
            font-weight:600;
            color:#ffffff;
            font-family:'Poppins',system-ui,Segoe UI,Roboto,Helvetica,Arial;
        }
        
        /* Navigation Menu */
        .nav-menu{
            display:flex;
            gap:8px;
            flex:1;
            margin:0 20px;
        }
        .nav-item{
            color:rgba(255,255,255,0.8);
            text-decoration:none;
            padding:8px 16px;
            border-radius:6px;
            font-size:14px;
            font-weight:500;
            transition:all 0.3s ease;
            white-space:nowrap;
            font-family:'Poppins',system-ui,Segoe UI,Roboto,Helvetica,Arial;
        }
        .nav-item:hover{
            background:rgba(255,255,255,0.15);
            color:white;
        }
        .nav-item.active{
            background:rgba(255,255,255,0.25);
            color:white;
            font-weight:600;
        }
        
        /* User Section */
        .user-section{
            display:flex;
            gap:12px;
            align-items:center;
            flex-shrink:0;
        }
        .user-name{
            color:white;
            font-size:14px;
            font-weight:500;
            font-family:'Poppins',system-ui,Segoe UI,Roboto,Helvetica,Arial;
        }
        .logout-form{
            margin:0;
        }
        .logout-btn{
            background:#ef4444;
            color:white;
            border:none;
            padding:8px 16px;
            border-radius:6px;
            cursor:pointer;
            font-weight:600;
            font-size:13px;
            transition:background 0.3s ease;
            font-family:'Poppins',system-ui,Segoe UI,Roboto,Helvetica,Arial;
        }
        .logout-btn:hover{
            background:#dc2626;
        }

        /* push page content down so header doesn't overlap - allow scroll */
        body{padding-top:88px}

        @media (max-width:768px){
            .header-inner{
                flex-wrap:wrap;
                gap:12px;
            }
            .nav-menu{
                flex:1 1 100%;
                margin:12px 0 0 0;
                order:3;
            }
            .nav-item{
                padding:8px 12px;
                font-size:13px;
            }
            .user-section{
                order:2;
            }
        }
        
        @media (max-width:480px){
            .site-header-home{padding:10px}
            body{padding-top:72px}
            .school-name{font-size:16px}
            .nav-menu{
                gap:4px;
            }
            .nav-item{
                padding:6px 10px;
                font-size:12px;
            }
            .logout-btn{
                padding:6px 12px;
                font-size:12px;
            }
            .user-name{
                display:none;
            }
        }
    </style>
</header>
