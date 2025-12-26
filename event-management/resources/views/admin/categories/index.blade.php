@extends('admin.layouts.app')

@section('title', 'კატეგორიები')
@section('page-title', 'კატეგორიების მართვა')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">ყველა კატეგორია</h4>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> ახალი კატეგორია
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>სახელი</th>
                        <th>Slug</th>
                        <th>აღწერა</th>
                        <th>ღონისძიებები</th>
                        <th>ქმედებები</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>{{ Str::limit($category->description, 50) }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $category->events_count }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('დარწმუნებული ხართ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fas fa-tags fa-3x mb-3"></i>
                            <p>კატეგორიები არ არის</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                დაამატეთ პირველი კატეგორია
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection