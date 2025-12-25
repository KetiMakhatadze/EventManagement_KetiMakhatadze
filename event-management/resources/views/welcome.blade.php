<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>მთავარი - Event Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }
        .event-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .event-image {
            height: 200px;
            object-fit: cover;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-calendar-alt"></i> EventHub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">მთავარი</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">ღონისძიებები</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="/">შესვლა</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white px-3" href="/">რეგისტრაცია</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="/">ჩემი დაჯავშნები</a>
                        </li>
                        @if(auth()->user()->isAdmin() || auth()->user()->isOrganizer())
                            <li class="nav-item">
                                <a class="nav-link" href="/">ადმინ პანელი</a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> გასვლა
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4">მოძებნეთ თქვენი იდეალური ღონისძიება</h1>
            <p class="lead mb-5">აღმოაჩინეთ სხვადასხვა ღონისძიებები და დაჯავშნეთ ბილეთები მარტივად</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="{{ route('events.index') }}" method="GET">
                        <div class="input-group input-group-lg shadow">
                            <input type="text" class="form-control" name="search" placeholder="ძებნა ღონისძიებებში...">
                            <button class="btn btn-warning px-5" type="submit">
                                <i class="fas fa-search"></i> ძებნა
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="container mb-5">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h4>მარტივი დაჯავშნა</h4>
                <p class="text-muted">დააჯავშნეთ ბილეთები რამდენიმე კლიკით</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h4>QR კოდები</h4>
                <p class="text-muted">მიიღეთ უნიკალური QR კოდი თითოეული ბილეთისთვის</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4>უსაფრთხო</h4>
                <p class="text-muted">თქვენი მონაცემები დაცულია</p>
            </div>
        </div>
    </section>

    <!-- Featured Events -->
    @if($featured_events->count() > 0)
    <section class="container mb-5">
        <h2 class="text-center mb-5">რჩეული ღონისძიებები</h2>
        <div class="row">
            @foreach($featured_events as $event)
            <div class="col-md-4 mb-4">
                <div class="card event-card">
                    @if($event->image)
                        <img src="{{ Storage::url($event->image) }}" class="card-img-top event-image" alt="{{ $event->title }}">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center event-image">
                            <i class="fas fa-image fa-3x text-white"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ Str::limit($event->title, 40) }}</h5>
                            @if($event->price > 0)
                                <span class="badge bg-primary">{{ number_format($event->price, 2) }} ₾</span>
                            @else
                                <span class="badge bg-success">უფასო</span>
                            @endif
                        </div>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-map-marker-alt"></i> {{ $event->location }}
                        </p>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-calendar"></i> {{ $event->start_date->format('d M, Y - H:i') }}
                        </p>
                        <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-users"></i> {{ $event->available_seats }} ადგილი
                            </small>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-primary">
                                დეტალურად <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('events.index') }}" class="btn btn-lg btn-outline-primary">
                ყველა ღონისძიება <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 EventHub. ყველა უფლება დაცულია.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>