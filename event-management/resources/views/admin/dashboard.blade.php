@extends('admin.layouts.app')

@section('title', 'დაშბორდი')
@section('page-title', 'დაშბორდი')

@section('content')
<div class="row mb-4">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">სულ ღონისძიებები</h6>
                        <h2 class="card-title mb-0">{{ $stats['total_events'] }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-calendar fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">აქტიური ღონისძიებები</h6>
                        <h2 class="card-title mb-0">{{ $stats['active_events'] }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">სულ დაჯავშნები</h6>
                        <h2 class="card-title mb-0">{{ $stats['total_bookings'] }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-ticket-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">სულ შემოსავალი</h6>
                        <h2 class="card-title mb-0">{{ number_format($stats['total_revenue'], 2) }} ₾</h2>
                    </div>
                    <div>
                        <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Bookings -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-clock"></i> ბოლო დაჯავშნები</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>დაჯავშნის #</th>
                                <th>მომხმარებელი</th>
                                <th>ღონისძიება</th>
                                <th>რაოდენობა</th>
                                <th>თანხა</th>
                                <th>სტატუსი</th>
                                <th>თარიღი</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_bookings as $booking)
                            <tr>
                                <td><strong>{{ $booking->booking_number }}</strong></td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ Str::limit($booking->event->title, 30) }}</td>
                                <td>{{ $booking->quantity }}</td>
                                <td>{{ number_format($booking->total_price, 2) }} ₾</td>
                                <td>
                                    @if($booking->status === 'confirmed')
                                        <span class="badge bg-success">დადასტურებული</span>
                                    @elseif($booking->status === 'pending')
                                        <span class="badge bg-warning">მოლოდინში</span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="badge bg-danger">გაუქმებული</span>
                                    @else
                                        <span class="badge bg-info">დასრულებული</span>
                                    @endif
                                </td>
                                <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>დაჯავშნები არ არის</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Events -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-fire"></i> პოპულარული ღონისძიებები</h5>
            </div>
            <div class="card-body">
                @forelse($popular_events as $event)
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    @if($event->image)
                        <img src="{{ Storage::url($event->image) }}" class="rounded" width="60" height="60" style="object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-image text-white"></i>
                        </div>
                    @endif
                    <div class="ms-3 flex-grow-1">
                        <h6 class="mb-1">{{ Str::limit($event->title, 25) }}</h6>
                        <small class="text-muted">
                            <i class="fas fa-ticket-alt"></i> {{ $event->bookings_count }} დაჯავშნა
                        </small>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted">მონაცემები არ არის</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-users"></i> მომხმარებლების სტატისტიკა</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <h3 class="text-primary">{{ $stats['total_users'] }}</h3>
                        <p class="text-muted mb-0">რეგისტრირებული მომხმარებლები</p>
                    </div>
                    <div class="col-6">
                        <h3 class="text-success">{{ $stats['total_organizers'] }}</h3>
                        <p class="text-muted mb-0">ორგანიზატორები</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-line"></i> სწრაფი ქმედებები</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> ახალი ღონისძიების დამატება
                    </a>
                    <a href="{{ route('admin.participants.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> მონაწილეთა სია
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection