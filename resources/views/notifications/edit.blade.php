@extends('layouts.app')

@section('title', 'Edit Notification')
@section('page-title', 'Edit notification')
@section('page-subtitle', $notification->client->name)

@section('content')
    <div class="card content-card">
        <div class="card-body">
            <form action="{{ route('notifications.update', $notification) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                @include('notifications.form')
                <div class="col-12">
                    <button class="btn btn-primary">Update notification</button>
                    <a href="{{ route('notifications.index') }}" class="btn btn-link">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
