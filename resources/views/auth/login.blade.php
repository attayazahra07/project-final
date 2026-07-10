<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Taya Global Chain</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 1rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.4);
        }

        .form-control {
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
        }

        .form-control:focus {
            background-color: rgba(15, 23, 42, 0.8);
            border-color: #3b82f6;
            color: white;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
            padding: 0.6rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <h3 class="text-primary fw-bold"><i class="fa-solid fa-globe"></i> TayaChain</h3>
            <p class="text-light opacity-75 small">Supply Chain Risk Intelligence</p>
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
            
            <button type="submit" class="btn btn-primary w-100">Sign In</button>
            
            <div class="text-center mt-3">
                <small class="text-muted">Use <strong>admin@taya.com</strong> or <strong>user@taya.com</strong><br>(password: password)</small>
            </div>
        </form>
    </div>

</body>
</html>
