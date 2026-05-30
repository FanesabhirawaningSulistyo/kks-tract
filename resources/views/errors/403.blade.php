<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .error-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px;
            text-align: center;
            max-width: 500px;
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .error-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .error-code {
            font-size: 72px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 10px;
        }
        .error-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }
        .error-message {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .btn-custom {
            padding: 10px 25px;
            margin: 5px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        /* Button Kembali - warna abu-abu */
        .btn-secondary-custom {
            background: #6c757d;
            color: white;
            border: none;
        }
        .btn-secondary-custom:hover {
            background: #5a6268;
            color: white;
        }
        /* Button Dashboard - warna ungu gradient */
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class='bx bx-shield-x'></i>
        </div>
        <div class="error-code">403</div>
        <div class="error-title">Akses Ditolak!</div>
        <div class="error-message">
            @if(isset($exception) && $exception->getMessage())
                {{ $exception->getMessage() }}
            @else
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. 
                Silakan hubungi administrator jika Anda memerlukan akses.
            @endif
        </div>
        <div>
            <a href="{{ url()->previous() }}" class="btn-custom btn-secondary-custom">
                <i class='bx bx-arrow-back'></i> Kembali
            </a>
            <a href="{{ route('dashboard') }}" class="btn-custom btn-primary-custom">
                <i class='bx bx-home'></i> Dashboard
            </a>
        </div>
    </div>
</body>
</html>