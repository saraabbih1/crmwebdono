@props([
    'label',
    'name',
    'type' => 'text',
    'value' => null,
])

<div {{ $attributes->class('') }}>
    <label class="form-label">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        class="form-control @error($name) is-invalid @enderror"
        {{ $attributes->except('class') }}
    >
    @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
