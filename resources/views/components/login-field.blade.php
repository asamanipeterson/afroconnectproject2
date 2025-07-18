@props(['label', 'name', 'type' => 'text', 'placeholder' => '', 'value' => ''])

<div class="form-group">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>

    <div class="input-with-icon">
        @if ($type === 'email')
            <i class="bi bi-envelope input-icon"></i>
        @elseif ($type === 'password')
            <i class="bi bi-lock input-icon"></i>
        @endif

        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            class="form-control @error($name) is-invalid @enderror"
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            @if($type === 'password') data-password @endif
        >

        @if ($type === 'password')
            <i class="bi bi-eye toggle-password" onclick="togglePassword(this)"></i>
        @endif
    </div>

    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
