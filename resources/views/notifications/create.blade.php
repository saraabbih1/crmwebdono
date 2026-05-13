@extends('layouts.app')

@section('title', 'Add Notification')
@section('page-title', 'Add notification')
@section('page-subtitle', 'Create a manual email notification')

@section('content')
    <div class="card content-card">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <div class="fw-semibold">Manual email notification</div>
            <div class="small text-secondary">Pending notifications are sent by the scheduled reminder command.</div>
        </div>
        <div class="card-body">
            <form action="{{ route('notifications.store') }}" method="POST" class="row g-3">
                @csrf
                @include('notifications.form', ['notification' => null])
                <div class="col-12 d-flex gap-2 pt-2">
                    <button class="btn btn-primary">Save notification</button>
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
