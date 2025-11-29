<header class="site-header">
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
    </div>

    <style>
        /* header with blue background and fixed on top */
        .site-header{
            position:fixed;
            left:0;right:0;top:0;z-index:60;
            display:flex;justify-content:center;align-items:center;
            background:#2563eb; /* blue */
            padding:12px 16px;
            box-shadow:0 2px 8px rgba(0,0,0,0.08);
        }
        .header-inner{width:100%;max-width:1100px;display:flex;align-items:center;gap:12px}
        .brand{display:flex;align-items:center;gap:12px;text-decoration:none}
        .brand img{height:44px;width:auto;display:block;border-radius:6px}
        .school-name{font-size:18px;font-weight:600;color:#ffffff;font-family:'Poppins',system-ui,Segoe UI,Roboto,Helvetica,Arial}

        /* push page content down so header doesn't overlap and prevent scroll on auth pages */
        body{padding-top:76px;overflow:hidden}

        @media (max-width:480px){
            .site-header{padding:10px}
            body{padding-top:72px}
            .school-name{font-size:16px}
        }
    </style>
</header>
