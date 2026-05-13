@extends('layouts.guest')

@section('title', 'Login')
@section('subtitle', 'Sign in to manage your CRM')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="vstack gap-3">
        @csrf
        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-check">
                <input type="checkbox" name="remember" class="form-check-input">
                <span class="form-check-label">Remember me</span>
            </label>
            <a href="{{ route('password.request') }}" class="small">Forgot password?</a>
        </div>
        <button class="btn btn-primary w-100">Login</button>
        <div class="text-center small">No account? <a href="{{ route('register') }}">Register</a></div>
    </form>
@endsection
