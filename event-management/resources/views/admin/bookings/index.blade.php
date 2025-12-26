@extends('admin.layouts.app')

@section('title', 'დაჯავშნები')
@section('page-title', 'დაჯავშნების მართვა')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">ყველა დაჯავშნა</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>დაჯავშნის #</th>
                        <th>მომხმარებელი</th>
                        <th>ღონისძიება</th>
                        <th>რაოდენობა</th>
                        <th>თანხა</th>
                        <th>სტატუსი</th>
                        <th>თარიღი</th>
                        <th>ქმედებები</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
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
                        <td>
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-ticket-alt fa-3x mb-3"></i>
                            <p>დაჯავშნები არ არის</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="mt-3">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection