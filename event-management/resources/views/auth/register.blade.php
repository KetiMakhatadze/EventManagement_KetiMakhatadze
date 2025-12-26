<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>რეგისტრაცია - EventHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .register-card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card register-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="text-primary"><i class="fas fa-calendar-alt"></i> EventHub</h2>
                            <p class="text-muted">შექმენით ახალი ანგარიში</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">სახელი</label>
                                <input type="text" class="form-control form-control-lg" 
                                       id="name" name="name" value="{{ old('name') }}" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">ელ.ფოსტა</label>
                                <input type="email" class="form-control form-control-lg" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">პაროლი</label>
                                <input type="password" class="form-control form-control-lg" 
                                       id="password" name="password" required>
                                <small class="text-muted">მინიმუმ 8 სიმბოლო</small>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">გაიმეორეთ პაროლი</label>
                                <input type="password" class="form-control form-control-lg" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fas fa-user-plus"></i> რეგისტრაცია
                            </button>

                            <div class="text-center">
                                <p class="mb-0">უკვე გაქვთ ანგარიში? <a href="{{ route('login') }}" class="text-primary fw-bold">შესვლა</a></p>
                                <a href="{{ route('home') }}" class="text-muted small">მთავარ გვერდზე დაბრუნება</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>