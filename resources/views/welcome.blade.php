<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>School Management System - Welcome</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,600,700&display=swap" rel="stylesheet" />
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: 'Poppins', sans-serif; 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .container { 
                padding: 20px; 
                width: 100%;
            }
            .card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.15);
                max-width: 1200px;
                margin: 0 auto;
                overflow: hidden;
            }
            .content { 
                display: grid; 
                grid-template-columns: 1fr 1fr; 
                gap: 40px;
                padding: 60px 40px;
                align-items: center;
            }
            .image-section { 
                border-radius: 16px; 
                aspect-ratio: 4/3; 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                color: rgba(0, 0, 0, 0.4); 
                font-size: 18px;
                position: relative;
                overflow: hidden;
            }
            .image-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                border-radius: 20px;
                padding: 6px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                mask-composite: exclude;
                pointer-events: none;
            }
            .text-section { }
            .text-section h1 { 
                font-size: 32px; 
                font-weight: 700; 
                color: #111827; 
                margin-bottom: 20px; 
                line-height: 1.2;
            }
            .text-section p { 
                font-size: 16px; 
                color: #6b7280; 
                line-height: 1.6; 
                margin-bottom: 32px; 
            }
            .buttons { 
                display: flex; 
                gap: 16px;
            }
            .btn { 
                padding: 12px 32px; 
                border-radius: 8px; 
                text-decoration: none; 
                font-weight: 600; 
                font-size: 14px;
                transition: all 0.3s ease; 
                border: none;
                cursor: pointer;
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
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }
            @media (max-width: 768px) {
                .content { 
                    grid-template-columns: 1fr; 
                    gap: 30px;
                    padding: 30px 20px;
                }
                .text-section h1 { 
                    font-size: 24px; 
                }
                .buttons { 
                    flex-direction: column; 
                }
                .btn {
                    width: 100%;
                    text-align: center;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="content">
                    <div class="image-section">
                        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'IP School') }} logo" style="max-width: 80%; max-height: 80%;">
                    </div>
                    <div class="text-section" style="text-align: center;">
                        <h1>School Management System<br>SMA IP Laravel</h1>
    
                        <div class="buttons" style="justify-content: center;">
                            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
