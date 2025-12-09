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
            padding-top: 80px;
        }
        .app-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        @yield('custom-styles')
    </style>
</head>
<body>
    @include('partials.header-home')
    
    <div class="app-container">
        @yield('content')
    </div>
</body>
</html>
