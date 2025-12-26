@extends('admin.layouts.app')

@section('title', 'დაჯავშნის დეტალები')
@section('page-title', 'დაჯავშნის დეტალები')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">დაჯავშნა: {{ $booking->booking_number }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>მომხმარებელი:</strong> {{ $booking->user->name }}</p>
                        <p><strong>ელ.ფოსტა:</strong> {{ $booking->user->email }}</p>
                        <p><strong>ტელეფონი:</strong> {{ $booking->user->phone ?? 'არ არის მითითებული' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>რაოდენობა:</strong> {{ $booking->quantity }}</p>
                        <p><strong>სულ თანხა:</strong> {{ number_format($booking->total_price, 2) }} ₾</p>
                        <p><strong>სტატუსი:</strong> 
                            @if($booking->status === 'confirmed')
                                <span class="badge bg-success">დადასტურებული</span>
                            @else
                                <span class="badge bg-warning">{{ $booking->status }}</span>
                            @endif
                        </p>
                        <p><strong>თარიღი:</strong> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <h5>ღონისძიება</h5>
                <div class="border rounded p-3 mb-4">
                    <h6>{{ $booking->event->title }}</h6>
                    <p class="mb-1"><i class="fas fa-calendar"></i> {{ $booking->event->start_date->format('d/m/Y H:i') }}</p>
                    <p class="mb-0"><i class="fas fa-map-marker-alt"></i> {{ $booking->event->location }}</p>
                </div>

                <h5>მონაწილეები</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>სახელი</th>
                                <th>გვარი</th>
                                <th>ელ.ფოსტა</th>
                                <th>ტელეფონი</th>
                                <th>შემოსული</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($booking->participants as $participant)
                            <tr>
                                <td>{{ $participant->first_name }}</td>
                                <td>{{ $participant->last_name }}</td>
                                <td>{{ $participant->email }}</td>
                                <td>{{ $participant->phone ?? '-' }}</td>
                                <td>
                                    @if($participant->checked_in)
                                        <span class="badge bg-success"><i class="fas fa-check"></i> კი</span>
                                        <br><small>{{ $participant->checked_in_at->format('d/m/Y H:i') }}</small>
                                    @else
                                        <span class="badge bg-secondary">არა</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">QR კოდი</h5>
            </div>
            <div class="card-body text-center">
                @if($booking->qr_code)
                    <img src="{{ asset('storage/' . $booking->qr_code) }}" class="img-fluid" alt="QR Code" style="max-width: 250px;">
                @else
                    <p class="text-muted">QR კოდი არ არის</p>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">ქმედებები</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> უკან
                    </a>
                    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
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