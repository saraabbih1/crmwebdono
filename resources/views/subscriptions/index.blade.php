<a href="{{ route('subscriptions.create') }}">Ajouter abonnement</a>

@foreach($subscriptions as $subscription)
    <div>
        <strong>{{ $subscription->client->name }}</strong>
        {{ strtoupper($subscription->service_type) }}
        {{ $subscription->start_date->format('Y-m-d') }} - {{ $subscription->end_date->format('Y-m-d') }}
        {{ $subscription->status }}

        <a href="{{ route('subscriptions.edit', $subscription) }}">Edit</a>

        <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST">
            @csrf
            @method('DELETE')
            <button>Delete</button>
        </form>
    </div>
@endforeach
