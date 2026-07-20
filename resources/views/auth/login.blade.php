<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Taya Global Chain</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: #0b5394;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid #cbd5e1;
            border-radius: 1rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.15), 0 8px 10px -6px rgba(37, 99, 235, 0.1);
        }

        .form-control {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            color: #0f172a;
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
        }

        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
            padding: 0.6rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <h3 class="text-primary fw-bold"><i class="fa-solid fa-globe"></i> Global Chain</h3>
            <p class="text-secondary small">Supply Chain Risk Intelligence</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 small border-danger bg-danger bg-opacity-10 text-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label text-muted small">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label text-muted small">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 mb-3">Sign In</button>
            
            <div class="text-center mb-3">
                <span class="text-muted small">Don't have an account? <a href="{{ url('/register') }}" class="text-primary text-decoration-none">Sign Up</a></span>
            </div>

            <div class="text-center">
                <small class="text-muted">Use <strong>admin@taya.com</strong> or <strong>user@taya.com</strong><br>(password: password)</small>
            </div>
        </form>
    </div>

</body>
</html>
