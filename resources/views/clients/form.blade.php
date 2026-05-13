<div class="col-md-6">
    <label class="form-label">Name</label>
    <input type="text" name="name" value="{{ old('name', $client?->name) }}" class="form-control @error('name') is-invalid @enderror" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" value="{{ old('phone', $client?->phone) }}" class="form-control @error('phone') is-invalid @enderror" required>
    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="email" value="{{ old('email', $client?->email) }}" class="form-control @error('email') is-invalid @enderror">
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
