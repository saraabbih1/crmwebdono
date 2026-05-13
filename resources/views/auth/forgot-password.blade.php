@extends('layouts.guest')

@section('title', 'Forgot Password')
@section('subtitle', 'Receive a secure reset link by email')

@section('content')
    <form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">
        @csrf
        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button class="btn btn-primary w-100">Send reset link</button>
        <div class="text-center small"><a href="{{ route('login') }}">Back to login</a></div>
    </form>
@endsection
