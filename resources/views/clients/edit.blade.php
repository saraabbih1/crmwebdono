<form action="{{ route('clients.update', $client->id) }}" method="POST">

    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $client->name }}">

    <input type="text" name="phone" value="{{ $client->phone }}">

    <input type="email" name="email" value="{{ $client->email }}">

    <button>
        Modifier
    </button>

</form>