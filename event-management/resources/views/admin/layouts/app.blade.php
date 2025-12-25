<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ადმინ პანელი') - Event Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stat-card {
            border-radius: 15px;
            border: none;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .table-actions {
            white-space: nowrap;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 px-0 sidebar">
                <div class="p-3 text-white">
                    <h4 class="mb-4"><i class="fas fa-calendar-alt"></i> ადმინ პანელი</h4>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> დაშბორდი
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}" href="{{ route('admin.events.index') }}">
                        <i class="fas fa-calendar"></i> ღონისძიებები
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-tags"></i> კატეგორიები
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                        <i class="fas fa-ticket-alt"></i> დაჯავშნები
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.participants.*') ? 'active' : '' }}" href="{{ route('admin.participants.index') }}">
                        <i class="fas fa-users"></i> მონაწილეები
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-user-shield"></i> მომხმარებლები
                    </a>
                    <hr class="text-white my-3">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fas fa-home"></i> მთავარი გვერდი
                    </a>
                    <a class="nav-link" href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> გასვლა
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg sticky-top mb-4">
                    <div class="container-fluid">
                        <span class="navbar-brand">@yield('page-title', 'დაშბორდი')</span>
                        <div class="ms-auto d-flex align-items-center">
                            <span class="me-3">
                                <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                            </span>
                            <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                    </div>
                </nav>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>