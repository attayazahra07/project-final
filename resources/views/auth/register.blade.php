<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Global Chain</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #0b5394;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 1rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            color: #1e293b;
        }

        .form-control, .form-select {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
        }

        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: #3b82f6;
            color: #0f172a;
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
            <h3 class="text-primary fw-bold"><i class="fa-solid fa-globe"></i> Global Chain</h3>
            <p class="text-muted small">Create Your Account</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 small border-danger bg-danger bg-opacity-10 text-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/register') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label text-muted small">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label text-muted small">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="role" class="form-label text-muted small">Account Type (Role)</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User (Observer)</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Manager)</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label text-muted small">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label text-muted small">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 mb-3">Sign Up</button>
            
            <div class="text-center">
                <span class="text-muted small">Already have an account? <a href="{{ url('/login') }}" class="text-primary text-decoration-none">Sign In</a></span>
            </div>
        </form>
    </div>

</body>
</html>
