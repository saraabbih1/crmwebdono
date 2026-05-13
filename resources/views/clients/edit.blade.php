@extends('layouts.app')

@section('title', 'Edit Client')
@section('page-title', 'Edit client')
@section('page-subtitle', $client->name)

@section('content')
    <div class="card content-card">
        <div class="card-body">
            <form action="{{ route('clients.update', $client) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                @include('clients.form')
                <div class="col-12">
                    <button class="btn btn-primary">Update client</button>
                    <a href="{{ route('clients.index') }}" class="btn btn-link">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
