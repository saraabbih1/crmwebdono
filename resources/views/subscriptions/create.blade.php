@extends('layouts.app')

@section('title', 'Add Subscription')
@section('page-title', 'Add subscription')
@section('page-subtitle', 'End date and reminder date are calculated automatically')

@section('content')
    <div class="card content-card">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <div class="fw-semibold">Subscription setup</div>
            <div class="small text-secondary">Renewal and reminder dates are calculated from the service type.</div>
        </div>
        <div class="card-body">
            <form action="{{ route('subscriptions.store') }}" method="POST" class="row g-3">
                @csrf
                @include('subscriptions.form', ['subscription' => null])
                <div class="col-12 d-flex gap-2 pt-2">
                    <button class="btn btn-primary">Save subscription</button>
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
