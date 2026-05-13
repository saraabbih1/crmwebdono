@extends('layouts.app')

@section('title', 'Edit Subscription')
@section('page-title', 'Edit subscription')
@section('page-subtitle', $subscription->client->name)

@section('content')
    <div class="card content-card">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <div class="fw-semibold">Subscription setup</div>
            <div class="small text-secondary">Changes will refresh the pending reminder notification.</div>
        </div>
        <div class="card-body">
            <form action="{{ route('subscriptions.update', $subscription) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                @include('subscriptions.form')
                <div class="col-12 d-flex gap-2 pt-2">
                    <button class="btn btn-primary">Update subscription</button>
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
