<header class="site-header-auth">
    <div class="header-inner">
        <a class="brand" href="{{ url('/') }}">
            @if (file_exists(public_path('images/logo.svg')))
                {!! file_get_contents(public_path('images/logo.svg')) !!}
            @elseif (file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'IP School') }} logo">
                <span class="school-name">{{ config('app.name', 'IP School') }}</span>
            @else
                <span class="school-name">{{ config('app.name', 'IP School') }}</span>
            @endif
        </a>
    </div>

    <style>
        /* header with blue background and fixed on top - allows scrolling */
        .site-header-auth{
            position:fixed;
            left:0;right:0;top:0;z-index:60;
            display:flex;justify-content:center;align-items:center;
            background:#2563eb;
            padding:22px 30px;
            box-shadow:0 2px 8px rgba(0,0,0,0.08);
        }
        .header-inner{
            width:100%;
            max-width:1200px;
            display:flex;
            align-items:center;
            gap:20px;
        }
        .school-name{
            font-size:18px;
            font-weight:600;
            color:#ffffff;
            font-family:'Poppins',system-ui,Segoe UI,Roboto,Helvetica,Arial;
        }

        /* push page content down so header doesn't overlap - allow scroll */
        body{padding-top:88px}

        @media (max-width:768px){
            .header-inner{
                flex-wrap:wrap;
                gap:12px;
            }
        }
        
        @media (max-width:480px){
            .site-header-auth{padding:10px}
            body{padding-top:72px}
            .school-name{font-size:16px}
        }
    </style>
</header>
