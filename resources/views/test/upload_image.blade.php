@extends('layouts.guest')

@section('content')
<div class="container mt-4">
    <h2>Test Image Upload</h2>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @if(session('image'))
    <div>
        <strong>Uploaded Image Preview:</strong><br>
        <img src="{{ asset('storage/' . session('image')) }}" alt="Uploaded Image" style="max-width:300px;">
    </div>
    @endif
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('test.upload.store') }}" method="POST" enctype="multipart/form-data" class="mt-3 mb-3">
        @csrf
        <div class="mb-3">
            <label for="image" class="form-label">Select image to upload</label>
            <input type="file" name="image" id="image" class="form-control" required>
        </div>
        <button class="btn btn-primary" type="submit">Upload</button>
    </form>
</div>
@endsection
