<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'IP School')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Poppins', sans-serif;
            background-color: #f0e8c8ff;
            min-height: 100vh;
            padding-top: 88px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            width: 100%;
            max-width: 420px;
            padding: 24px;
        }
        @yield('custom-styles')
    </style>
</head>
<body>
    @include('partials.header-auth')
    
    <div class="auth-container">
        @yield('content')
    </div>
</body>
</html>
