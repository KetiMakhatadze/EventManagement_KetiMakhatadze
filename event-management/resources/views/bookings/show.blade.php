@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Success Message -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading"><i class="fas fa-check-circle"></i> დაჯავშნა წარმატებულია!</h4>
                <p>თქვენი დაჯავშნა წარმატებით განხორციელდა. QR კოდები გამოგზავნილია თქვენს ელ.ფოსტაზე.</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <!-- Booking Info Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-ticket-alt"></i> დაჯავშნის დეტალები</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>დაჯავშნის ნომერი:</strong></p>
                            <h4 class="text-primary">{{ $booking->booking_number }}</h4>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>სტატუსი:</strong></p>
                            @if($booking->status === 'confirmed')
                                <span class="badge bg-success fs-6">დადასტურებული</span>
                            @elseif($booking->status === 'pending')
                                <span class="badge bg-warning fs-6">მოლოდინში</span>
                            @else
                                <span class="badge bg-info fs-6">{{ $booking->status }}</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Event Details -->
                    <div class="row">
                        <div class="col-md-3">
                            @if($booking->event->image)
                                <img src="{{ Storage::url($booking->event->image) }}" class="img-fluid rounded">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <i class="fas fa-image fa-3x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h3>{{ $booking->event->title }}</h3>
                            <p class="mb-2">
                                <i class="fas fa-calendar text-primary"></i>
                                <strong>თარიღი:</strong> {{ $booking->event->start_date->format('d F, Y - H:i') }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                <strong>ლოკაცია:</strong> {{ $booking->event->location }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-user-tie text-primary"></i>
                                <strong>ორგანიზატორი:</strong> {{ $booking->event->organizer->name }}
                            </p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Booking Summary -->
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>ბილეთების რაოდენობა:</strong> {{ $booking->quantity }}</p>
                            <p class="mb-2"><strong>დაჯავშნის თარიღი:</strong> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h5 class="text-muted mb-2">სულ გადახდილი:</h5>
                            <h2 class="text-primary mb-0">{{ number_format($booking->total_price, 2) }} ₾</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Booking QR Code -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-qrcode"></i> დაჯავშნის QR კოდი</h5>
                </div>
                <div class="card-body text-center p-4">
                    @if($booking->qr_code)
                        <img src="{{ Storage::url($booking->qr_code) }}" alt="Booking QR Code" class="img-fluid mb-3" style="max-width: 300px;">
                        <p class="text-muted">წარმოადგინეთ ეს QR კოდი შესასვლელთან</p>
                    @endif
                </div>
            </div>

            <!-- Participants List with QR Codes -->
            <div class="card border-0 shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-users"></i> მონაწილეები და მათი QR კოდები</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        @foreach($booking->participants as $index => $participant)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="mb-0">
                                            <span class="badge bg-primary">{{ $index + 1 }}</span>
                                            {{ $participant->full_name }}
                                        </h6>
                                        @if($participant->checked_in)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> შემოსული
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-envelope"></i> {{ $participant->email }}
                                    </p>
                                    @if($participant->phone)
                                        <p class="text-muted small mb-3">
                                            <i class="fas fa-phone"></i> {{ $participant->phone }}
                                        </p>
                                    @endif

                                    <!-- Individual QR Code -->
                                    <div class="text-center bg-light rounded p-3">
                                        @if($participant->qr_code)
                                            <img src="{{ Storage::url($participant->qr_code) }}" 
                                                 alt="QR Code for {{ $participant->full_name }}" 
                                                 class="img-fluid mb-2" 
                                                 style="max-width: 200px;">
                                        @endif
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="downloadQR('{{ Storage::url($participant->qr_code) }}', '{{ $participant->full_name }}')">
                                            <i class="fas fa-download"></i> ჩამოტვირთვა
                                        </button>
                                    </div>

                                    @if($participant->checked_in && $participant->checked_in_at)
                                        <p class="text-success small text-center mt-2 mb-0">
                                            შემოსვლა: {{ $participant->checked_in_at->format('d/m/Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="{{ route('bookings.my') }}" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-list"></i> ჩემი ყველა დაჯავშნა
                </a>
                <a href="{{ route('events.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-calendar"></i> სხვა ღონისძიებები
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function downloadQR(url, name) {
    fetch(url)
        .then(response => response.blob())
        .then(blob => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `qr_code_${name.replace(/\s+/g, '_')}.svg`;
            link.click();
        });
}

// Print functionality
function printTickets() {
    window.print();
}
</script>
@endpush

<style>
@media print {
    .btn, nav, footer, .alert {
        display: none !important;
    }
}
</style>
@endsection@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Success Message -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading"><i class="fas fa-check-circle"></i> დაჯავშნა წარმატებულია!</h4>
                <p>თქვენი დაჯავშნა წარმატებით განხორციელდა. QR კოდები გამოგზავნილია თქვენს ელ.ფოსტაზე.</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <!-- Booking Info Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-ticket-alt"></i> დაჯავშნის დეტალები</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>დაჯავშნის ნომერი:</strong></p>
                            <h4 class="text-primary">{{ $booking->booking_number }}</h4>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>სტატუსი:</strong></p>
                            @if($booking->status === 'confirmed')
                                <span class="badge bg-success fs-6">დადასტურებული</span>
                            @elseif($booking->status === 'pending')
                                <span class="badge bg-warning fs-6">მოლოდინში</span>
                            @else
                                <span class="badge bg-info fs-6">{{ $booking->status }}</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Event Details -->
                    <div class="row">
                        <div class="col-md-3">
                            @if($booking->event->image)
                                <img src="{{ Storage::url($booking->event->image) }}" class="img-fluid rounded">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <i class="fas fa-image fa-3x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h3>{{ $booking->event->title }}</h3>
                            <p class="mb-2">
                                <i class="fas fa-calendar text-primary"></i>
                                <strong>თარიღი:</strong> {{ $booking->event->start_date->format('d F, Y - H:i') }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                <strong>ლოკაცია:</strong> {{ $booking->event->location }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-user-tie text-primary"></i>
                                <strong>ორგანიზატორი:</strong> {{ $booking->event->organizer->name }}
                            </p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Booking Summary -->
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>ბილეთების რაოდენობა:</strong> {{ $booking->quantity }}</p>
                            <p class="mb-2"><strong>დაჯავშნის თარიღი:</strong> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h5 class="text-muted mb-2">სულ გადახდილი:</h5>
                            <h2 class="text-primary mb-0">{{ number_format($booking->total_price, 2) }} ₾</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Booking QR Code -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-qrcode"></i> დაჯავშნის QR კოდი</h5>
                </div>
                <div class="card-body text-center p-4">
                    @if($booking->qr_code)
                        <img src="{{ Storage::url($booking->qr_code) }}" alt="Booking QR Code" class="img-fluid mb-3" style="max-width: 300px;">
                        <p class="text-muted">წარმოადგინეთ ეს QR კოდი შესასვლელთან</p>
                    @endif
                </div>
            </div>

            <!-- Participants List with QR Codes -->
            <div class="card border-0 shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-users"></i> მონაწილეები და მათი QR კოდები</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        @foreach($booking->participants as $index => $participant)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="mb-0">
                                            <span class="badge bg-primary">{{ $index + 1 }}</span>
                                            {{ $participant->full_name }}
                                        </h6>
                                        @if($participant->checked_in)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> შემოსული
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-envelope"></i> {{ $participant->email }}
                                    </p>
                                    @if($participant->phone)
                                        <p class="text-muted small mb-3">
                                            <i class="fas fa-phone"></i> {{ $participant->phone }}
                                        </p>
                                    @endif

                                    <!-- Individual QR Code -->
                                    <div class="text-center bg-light rounded p-3">
                                        @if($participant->qr_code)
                                            <img src="{{ Storage::url($participant->qr_code) }}" 
                                                 alt="QR Code for {{ $participant->full_name }}" 
                                                 class="img-fluid mb-2" 
                                                 style="max-width: 200px;">
                                        @endif
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="downloadQR('{{ Storage::url($participant->qr_code) }}', '{{ $participant->full_name }}')">
                                            <i class="fas fa-download"></i> ჩამოტვირთვა
                                        </button>
                                    </div>

                                    @if($participant->checked_in && $participant->checked_in_at)
                                        <p class="text-success small text-center mt-2 mb-0">
                                            შემოსვლა: {{ $participant->checked_in_at->format('d/m/Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="{{ route('bookings.my') }}" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-list"></i> ჩემი ყველა დაჯავშნა
                </a>
                <a href="{{ route('events.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-calendar"></i> სხვა ღონისძიებები
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function downloadQR(url, name) {
    fetch(url)
        .then(response => response.blob())
        .then(blob => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `qr_code_${name.replace(/\s+/g, '_')}.svg`;
            link.click();
        });
}

// Print functionality
function printTickets() {
    window.print();
}
</script>
@endpush

<style>
@media print {
    .btn, nav, footer, .alert {
        display: none !important;
    }
}
</style>
@endsection