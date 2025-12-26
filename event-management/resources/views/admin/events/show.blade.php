@extends('admin.layouts.app')

@section('title', 'ღონისძიების დეტალები')
@section('page-title', 'ღონისძიების დეტალები')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" class="img-fluid rounded mb-3" alt="{{ $event->title }}">
                @endif

                <h2>{{ $event->title }}</h2>
                
                <div class="mb-3">
                    @foreach($event->categories as $category)
                        <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                    @endforeach
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>ორგანიზატორი:</strong> {{ $event->organizer->name }}</p>
                        <p><strong>ლოკაცია:</strong> {{ $event->location }}</p>
                        <p><strong>დაწყება:</strong> {{ $event->start_date->format('d/m/Y H:i') }}</p>
                        <p><strong>დასრულება:</strong> {{ $event->end_date->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>სულ ადგილები:</strong> {{ $event->total_seats }}</p>
                        <p><strong>ხელმისაწვდომი:</strong> {{ $event->available_seats }}</p>
                        <p><strong>ფასი:</strong> {{ number_format($event->price, 2) }} ₾</p>
                        <p><strong>სტატუსი:</strong> 
                            @if($event->status === 'published')
                                <span class="badge bg-success">გამოქვეყნებული</span>
                            @elseif($event->status === 'draft')
                                <span class="badge bg-warning">მონახაზი</span>
                            @elseif($event->status === 'cancelled')
                                <span class="badge bg-danger">გაუქმებული</span>
                            @else
                                <span class="badge bg-info">დასრულებული</span>
                            @endif
                        </p>
                    </div>
                </div>

                <h5>აღწერა</h5>
                <p style="white-space: pre-line;">{{ $event->description }}</p>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">დაჯავშნები ({{ $event->bookings->count() }})</h5>
            </div>
            <div class="card-body">
                @if($event->bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ნომერი</th>
                                    <th>მომხმარებელი</th>
                                    <th>რაოდენობა</th>
                                    <th>თანხა</th>
                                    <th>სტატუსი</th>
                                    <th>თარიღი</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($event->bookings as $booking)
                                <tr>
                                    <td>{{ $booking->booking_number }}</td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->quantity }}</td>
                                    <td>{{ number_format($booking->total_price, 2) }} ₾</td>
                                    <td>
                                        @if($booking->status === 'confirmed')
                                            <span class="badge bg-success">დადასტურებული</span>
                                        @else
                                            <span class="badge bg-warning">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted">დაჯავშნები არ არის</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">ქმედებები</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> რედაქტირება
                    </a>
                    <a href="{{ route('events.show', $event) }}" class="btn btn-info" target="_blank">
                        <i class="fas fa-eye"></i> გვერდის ნახვა
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> უკან
                    </a>
                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                          onsubmit="return confirm('დარწმუნებული ხართ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> წაშლა
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection