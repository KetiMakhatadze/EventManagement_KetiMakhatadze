@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-ticket-alt"></i> ბილეთის დაჯავშნა</h4>
                </div>
                <div class="card-body p-4">
                    <!-- Event Info -->
                    <div class="row mb-4 pb-4 border-bottom">
                        <div class="col-md-3">
                            @if($event->image)
                                <img src="{{ Storage::url($event->image) }}" class="img-fluid rounded" alt="{{ $event->title }}">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <i class="fas fa-image fa-3x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h3>{{ $event->title }}</h3>
                            <p class="text-muted mb-2">
                                <i class="fas fa-calendar"></i> {{ $event->start_date->format('d F, Y - H:i') }}
                            </p>
                            <p class="text-muted mb-2">
                                <i class="fas fa-map-marker-alt"></i> {{ $event->location }}
                            </p>
                            <p class="mb-0">
                                <strong>ფასი:</strong> 
                                @if($event->price > 0)
                                    <span class="badge bg-primary">{{ number_format($event->price, 2) }} ₾</span>
                                @else
                                    <span class="badge bg-success">უფასო</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <!-- Quantity Selection -->
                        <div class="mb-4">
                            <label for="quantity" class="form-label h5">რამდენი ბილეთი გსურთ?</label>
                            <select class="form-select form-select-lg @error('quantity') is-invalid @enderror" 
                                    id="quantity" name="quantity" required>
                                <option value="">აირჩიეთ რაოდენობა</option>
                                @for($i = 1; $i <= min(10, $event->available_seats); $i++)
                                    <option value="{{ $i }}" {{ old('quantity') == $i ? 'selected' : '' }}>
                                        {{ $i }} ბილეთი
                                        @if($event->price > 0)
                                            ({{ number_format($event->price * $i, 2) }} ₾)
                                        @endif
                                    </option>
                                @endfor
                            </select>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Participants Info -->
                        <div id="participantsContainer" style="display: none;">
                            <h5 class="mb-3">მონაწილეთა ინფორმაცია</h5>
                            <div id="participantForms"></div>
                        </div>

                        <!-- Total Price -->
                        @if($event->price > 0)
                        <div class="bg-light p-4 rounded mb-4" id="totalSection" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">სულ გადასახდელი:</h5>
                                <h3 class="mb-0 text-primary" id="totalPrice">0 ₾</h3>
                            </div>
                        </div>
                        @endif

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i> უკან
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-check"></i> დაჯავშნის დადასტურება
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.participant-card {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    background: #f8f9fa;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantitySelect = document.getElementById('quantity');
    const participantsContainer = document.getElementById('participantsContainer');
    const participantForms = document.getElementById('participantForms');
    const submitBtn = document.getElementById('submitBtn');
    const totalSection = document.getElementById('totalSection');
    const totalPriceEl = document.getElementById('totalPrice');
    const eventPrice = {{ $event->price }};

    quantitySelect.addEventListener('change', function() {
        const quantity = parseInt(this.value);
        
        if (quantity > 0) {
            generateParticipantForms(quantity);
            participantsContainer.style.display = 'block';
            submitBtn.disabled = false;

            if (eventPrice > 0) {
                totalSection.style.display = 'block';
                totalPriceEl.textContent = (eventPrice * quantity).toFixed(2) + ' ₾';
            }
        } else {
            participantsContainer.style.display = 'none';
            submitBtn.disabled = true;
            totalSection.style.display = 'none';
        }
    });

    function generateParticipantForms(quantity) {
        participantForms.innerHTML = '';
        
        for (let i = 0; i < quantity; i++) {
            const participantCard = `
                <div class="participant-card">
                    <h6 class="mb-3"><i class="fas fa-user"></i> მონაწილე #${i + 1}</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">სახელი *</label>
                            <input type="text" class="form-control" 
                                   name="participants[${i}][first_name]" 
                                   value="${i === 0 ? '{{ auth()->user()->name }}' : ''}"
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">გვარი *</label>
                            <input type="text" class="form-control" 
                                   name="participants[${i}][last_name]" 
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ელ.ფოსტა *</label>
                            <input type="email" class="form-control" 
                                   name="participants[${i}][email]" 
                                   value="${i === 0 ? '{{ auth()->user()->email }}' : ''}"
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ტელეფონი</label>
                            <input type="tel" class="form-control" 
                                   name="participants[${i}][phone]" 
                                   value="${i === 0 ? '{{ auth()->user()->phone ?? '' }}' : ''}">
                        </div>
                    </div>
                </div>
            `;
            participantForms.insertAdjacentHTML('beforeend', participantCard);
        }
    }
});
</script>
@endpush
@endsection