@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Vytvořit téma</h1>

    <form action="{{ route('admin.topics.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Název *</label>
            <input type="text" name="name"  placeholder="Globální oteplování" value="{{ old('name') }}" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Cílová skupina</label>
            <input type="text" name="target_group" placeholder="programátoři" value="{{ old('target_group') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Popis</label>
            <textarea name="description" placeholder="Moje první téma" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Zdroje</label>
            <input type="text" placeholder="např. https://example.com" name="sources" value="{{ old('sources') }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Vytvořit téma</button>
        <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary">Zpět</a>
    </form>
</div>
@endsection
