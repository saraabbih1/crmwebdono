@extends('layouts.app')

@section('title', 'Edit Subscription')
@section('page-title', 'Edit subscription')
@section('page-subtitle', $subscription->client->name)

@section('content')
    <div class="card content-card">
        <div class="card-body">
            <form action="{{ route('subscriptions.update', $subscription) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                @include('subscriptions.form')
                <div class="col-12">
                    <button class="btn btn-primary">Update subscription</button>
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-link">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
