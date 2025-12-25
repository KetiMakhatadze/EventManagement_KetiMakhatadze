@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <div class="card border-0 shadow">
                @if($event->image)
                    <img src="{{ Storage::url($event->image) }}" class="card-img-top" style="height: 400px; object-fit: cover;">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 400px;">
                        <i class="fas fa-image fa-5x text-white"></i>
                    </div>
                @endif
                
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="display-5">{{ $event->title }}</h1>
                        @if($event->isFull())
                            <span class="badge bg-danger fs-5">სავსეა</span>
                        @elseif($event->price > 0)
                            <span class="badge bg-primary fs-5">{{ number_format($event->price, 2) }} ₾</span>
                        @else
                            <span class="badge bg-success fs-5">უფასო</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        @foreach($event->categories as $category)
                            <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                        @endforeach
                    </div>

                    <!-- Event Details -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="text-primary me-3" style="font-size: 2rem;">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <small class="text-muted">თარიღი</small>
                                    <p class="mb-0 fw-bold">{{ $event->start_date->format('d F, Y') }}</p>
                                    <small>{{ $event->start_date->format('H:i') }} - {{ $event->end_date->format('H:i') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="text-primary me-3" style="font-size: 2rem;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <small class="text-muted">ლოკაცია</small>
                                    <p class="mb-0 fw-bold">{{ $event->location }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="text-primary me-3" style="font-size: 2rem;">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <small class="text-muted">ხელმისაწვდომი ადგილები</small>
                                    <p class="mb-0 fw-bold">{{ $event->available_seats }} / {{ $event->total_seats }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="text-primary me-3" style="font-size: 2rem;">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <small class="text-muted">ორგანიზატორი</small>
                                    <p class="mb-0 fw-bold">{{ $event->organizer->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Description -->
                    <div class="mb-4">
                        <h4>ღონისძიების შესახებ</h4>
                        <p class="text-muted" style="white-space: pre-line;">{{ $event->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Related Events -->
            @if($related_events->count() > 0)
            <div class="mt-5">
                <h4 class="mb-4">მსგავსი ღონისძიებები</h4>
                <div class="row">
                    @foreach($related_events as $related)
                    <div class="col-md-6 mb-3">
                        <div class="card event-card">
                            @if($related->image)
                                <img src="{{ Storage::url($related->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h6>{{ Str::limit($related->title, 40) }}</h6>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-calendar"></i> {{ $related->start_date->format('d M, Y') }}
                                </p>
                                <a href="{{ route('events.show', $related) }}" class="btn btn-sm btn-outline-primary">
                                    დეტალურად
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar - Booking -->
        <div class="col-md-4">
            <div class="card border-0 shadow sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    @if($event->isAvailable())
                        <h4 class="mb-4">დაჯავშნა</h4>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>ფასი ბილეთზე:</span>
                                <strong>{{ $event->price > 0 ? number_format($event->price, 2) . ' ₾' : 'უფასო' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>ხელმისაწვდომი:</span>
                                <strong class="text-success">{{ $event->available_seats }} ადგილი</strong>
                            </div>
                        </div>

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 btn-lg mb-2">
                                <i class="fas fa-sign-in-alt"></i> შესვლა დაჯავშნისთვის
                            </a>
                            <p class="text-center text-muted small">ან</p>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                                რეგისტრაცია
                            </a>
                        @else
                            <a href="{{ route('bookings.create', $event) }}" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-ticket-alt"></i> ბილეთის დაჯავშნა
                            </a>
                        @endguest

                        <hr class="my-4">

                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt"></i> უსაფრთხო გადახდა<br>
                                <i class="fas fa-qrcode"></i> მიიღეთ QR კოდი
                            </small>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
                            <h5 class="text-danger">ბილეთები გაყიდულია</h5>
                            <p class="text-muted">სამწუხაროდ, ამ ღონისძიებაზე ბილეთები აღარ არის</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.event-card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}
.event-card:hover {
    transform: translateY(-3px);
}
</style>
@endsection