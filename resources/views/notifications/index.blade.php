<a href="{{ route('notifications.create') }}">Ajouter notification</a>

@foreach($notifications as $notification)
    <div>
        <strong>{{ $notification->client->name }}</strong>
        {{ $notification->message }}
        {{ $notification->status }}
        {{ $notification->reminder_date?->format('Y-m-d') }}

        <a href="{{ route('notifications.edit', $notification) }}">Edit</a>

        <form action="{{ route('notifications.destroy', $notification) }}" method="POST">
            @csrf
            @method('DELETE')
            <button>Delete</button>
        </form>
    </div>
@endforeach
