<a href="{{ route('clients.create') }}">
    Ajouter client
</a>

@foreach($clients as $client)

    <div>
        {{ $client->name }}
        {{ $client->phone }}
        {{ $client->email }}

        <a href="{{ route('clients.edit', $client->id) }}">
            Edit
        </a>

        <form action="{{ route('clients.destroy', $client->id) }}" method="POST">

            @csrf
            @method('DELETE')

            <button>
                Delete
            </button>

        </form>

    </div>

@endforeach