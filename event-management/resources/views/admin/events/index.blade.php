@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar - Filters -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> ფილტრები</h5>
                </div>
                <div class="card-body">
                    <!-- Search -->
                    <form action="{{ route('events.index') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">ძებნა</label>
                            <input type="text" class="form-control" name="search" 
                                   value="{{ request('search') }}" placeholder="ძებნა...">
                        </div>

                        <!-- Categories -->
                        <div class="mb-3">
                            <label class="form-label">კატეგორია</label>
                            <div class="list-group">
                                <a href="{{ route('events.index') }}" 
                                   class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                                    ყველა
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('events.index', ['category' => $category->slug]) }}" 
                                       class="list-group-item list-group-item-action {{ request('category') == $category->slug ? 'active' : '' }}">
                                        {{ $category->name }}
                                        <span class="badge bg-secondary float-end">{{ $category->events_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> ძებნა
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>
                    @if(request('category'))
                        კატეგორია: {{ $categories->firstWhere('slug', request('category'))->name }}
                    @elseif(request('search'))
                        ძებნის შედეგები: "{{ request('search') }}"
                    @else
                        ყველა ღონისძიება
                    @endif
                </h3>
                <small class="text-muted">{{ $events->total() }} ღონისძიება</small>
            </div>

            <div class="row">
                @forelse($events as $event)
                <div class="col-md-4 mb-4">
                    <div class="card event-card h-100">
                        @if($event->image)
                            <img src="{{ Storage::url($event->image) }}" class="card-img-top event-image" alt="{{ $event->title }}">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center event-image">
                                <i class="fas fa-image fa-3x text-white"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ Str::limit($event->title, 35) }}</h5>
                                @if($event->isFull())
                                    <span class="badge bg-danger">სავსეა</span>
                                @elseif($event->price > 0)
                                    <span class="badge bg-primary">{{ number_format($event->price, 2) }} ₾</span>
                                @else
                                    <span class="badge bg-success">უფასო</span>
                                @endif
                            </div>

                            <div class="mb-2">
                                @foreach($event->categories as $category)
                                    <span class="badge bg-secondary">{{ $category->name }}</span>
                                @endforeach
                            </div>

                            <p class="text-muted small mb-2">
                                <i class="fas fa-map-marker-alt"></i> {{ $event->location }}
                            </p>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-calendar"></i> {{ $event->start_date->format('d M, Y - H:i') }}
                            </p>
                            
                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-users"></i> {{ $event->available_seats }} / {{ $event->total_seats }}
                                    </small>
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-primary">
                                        დეტალურად <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-calendar-times fa-5x text-muted mb-3"></i>
                    <h4 class="text-muted">ღონისძიებები არ მოიძებნა</h4>
                    <p class="text-muted">სცადეთ სხვა ძიების კრიტერიები</p>
                    <a href="{{ route('events.index') }}" class="btn btn-primary">
                        <i class="fas fa-redo"></i> გასუფთავება
                    </a>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($events->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $events->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.event-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}
.event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.event-image {
    height: 200px;
    object-fit: cover;
}
</style>
@endsection