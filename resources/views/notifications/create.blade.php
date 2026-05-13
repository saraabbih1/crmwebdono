@extends('layouts.app')

@section('title', 'Add Notification')
@section('page-title', 'Add notification')
@section('page-subtitle', 'Create a manual email notification')

@section('content')
    <div class="card content-card">
        <div class="card-body">
            <form action="{{ route('notifications.store') }}" method="POST" class="row g-3">
                @csrf
                @include('notifications.form', ['notification' => null])
                <div class="col-12">
                    <button class="btn btn-primary">Save notification</button>
                    <a href="{{ route('notifications.index') }}" class="btn btn-link">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
