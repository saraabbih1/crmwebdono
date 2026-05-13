@extends('layouts.app')

@section('title', 'Add Subscription')
@section('page-title', 'Add subscription')
@section('page-subtitle', 'End date and reminder date are calculated automatically')

@section('content')
    <div class="card content-card">
        <div class="card-body">
            <form action="{{ route('subscriptions.store') }}" method="POST" class="row g-3">
                @csrf
                @include('subscriptions.form', ['subscription' => null])
                <div class="col-12">
                    <button class="btn btn-primary">Save subscription</button>
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-link">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
