@extends('layouts.app')

@section('title', 'Edit Notification')
@section('page-title', 'Edit notification')
@section('page-subtitle', $notification->client->name)

@section('content')
    <div class="card content-card">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <div class="fw-semibold">Manual email notification</div>
            <div class="small text-secondary">Review status, reminder date, and delivery timing.</div>
        </div>
        <div class="card-body">
            <form action="{{ route('notifications.update', $notification) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                @include('notifications.form')
                <div class="col-12 d-flex gap-2 pt-2">
                    <button class="btn btn-primary">Update notification</button>
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
