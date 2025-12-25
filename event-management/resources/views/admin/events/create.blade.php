@extends('admin.layouts.app')

@section('title', isset($event) ? 'ღონისძიების რედაქტირება' : 'ახალი ღონისძიება')
@section('page-title', isset($event) ? 'ღონისძიების რედაქტირება' : 'ახალი ღონისძიება')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-plus"></i>
                    {{ isset($event) ? 'ღონისძიების რედაქტირება' : 'ახალი ღონისძიების შექმნა' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($event) ? route('admin.events.update', $event) : route('admin.events.store') }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($event))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <!-- სათაური -->
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label">სათაური *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $event->title ?? '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- აღწერა -->
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">აღწერა *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description', $event->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ლოკაცია -->
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">ლოკაცია *</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location', $event->location ?? '') }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- სურათი -->
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">სურათი</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(isset($event) && $event->image)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($event->image) }}" class="img-thumbnail" width="200">
                                </div>
                            @endif
                        </div>

                        <!-- დაწყების თარიღი -->
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">დაწყების თარიღი და დრო *</label>
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" 
                                   value="{{ old('start_date', isset($event) ? $event->start_date->format('Y-m-d\TH:i') : '') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- დასრულების თარიღი -->
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">დასრულების თარიღი და დრო *</label>
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" 
                                   value="{{ old('end_date', isset($event) ? $event->end_date->format('Y-m-d\TH:i') : '') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ადგილების რაოდენობა -->
                        <div class="col-md-4 mb-3">
                            <label for="total_seats" class="form-label">სულ ადგილები *</label>
                            <input type="number" class="form-control @error('total_seats') is-invalid @enderror" 
                                   id="total_seats" name="total_seats" min="1" 
                                   value="{{ old('total_seats', $event->total_seats ?? '') }}" required>
                            @error('total_seats')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ფასი -->
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">ფასი (₾) *</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" min="0" 
                                   value="{{ old('price', $event->price ?? '0') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- სტატუსი -->
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">სტატუსი *</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="draft" {{ old('status', $event->status ?? '') == 'draft' ? 'selected' : '' }}>მონახაზი</option>
                                <option value="published" {{ old('status', $event->status ?? '') == 'published' ? 'selected' : '' }}>გამოქვეყნებული</option>
                                <option value="cancelled" {{ old('status', $event->status ?? '') == 'cancelled' ? 'selected' : '' }}>გაუქმებული</option>
                                <option value="completed" {{ old('status', $event->status ?? '') == 'completed' ? 'selected' : '' }}>დასრულებული</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- კატეგორიები -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">კატეგორიები * (მინიმუმ 1)</label>
                            <div class="row">
                                @foreach($categories as $category)
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input @error('categories') is-invalid @enderror" 
                                                   type="checkbox" name="categories[]" 
                                                   value="{{ $category->id }}" id="category_{{ $category->id }}"
                                                   {{ (isset($event) && $event->categories->contains($category->id)) || (is_array(old('categories')) && in_array($category->id, old('categories'))) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category_{{ $category->id }}">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('categories')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> უკან
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ isset($event) ? 'განახლება' : 'შენახვა' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection