@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Upravit téma</h1>

    <form action="{{ route('admin.topics.update', $topic) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Název *</label>
            <input type="text" name="name" value="{{ old('name', $topic->name) }}" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Cílová skupina</label>
            <input type="text" name="target_group" value="{{ old('target_group', $topic->target_group) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Popis</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $topic->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Zdroje</label>
            <input type="text" name="sources" value="{{ old('sources', $topic->sources) }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-warning">Uložit</button>
        <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary">Zpět</a>
    </form>
</div>
@endsection
