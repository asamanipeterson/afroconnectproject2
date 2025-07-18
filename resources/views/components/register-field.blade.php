@props([
    'name',
    'label' => null,
    'type' => 'text',
    'required' => false,
    'placeholder' => null,
    'value' => null,
    'options' => [],
])
<div class="form-group">
    <label for="{{ $name }}" class="form-label">
        {{ $label ?? ucfirst($name) }} @if($required)@endif
    </label>

    <div class="input-with-icon">
        @if ($type === 'email')
            <i class="bi bi-envelope input-icon"></i>
        @elseif ($type === 'password')
            <i class="bi bi-lock input-icon"></i>
        @elseif ($type === 'select')
            <i class="bi bi-chevron-down input-icon"></i>
        @elseif($type ==='text')
            <i class="bi bi-person-fill input-icon"></i>
        @elseif($type ==='tel')
            <i class="bi bi-telephone input-icon"></i>
        @elseif($type ==='date')
            <i class="bi bi-calendar-date input-icon"></i>
        @endif

        {{-- Foe the option part --}}
    @if($type === 'select')
        <select id="{{ $name }}" name="{{ $name }}" class="form-select @error($name) is-invalid @enderror">
            @if($placeholder)
                <option value="" disabled {{ old($name) ? '' : 'selected' }}>{{ $placeholder }}</option>
            @endif
            @foreach($options as $key => $option)
                <option value="{{ $key }}" {{ old($name, $value ?? '') == $key ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>

    @else
        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
            value="{{ old($name, $value ?? '') }}"
            class="form-control @error($name) is-invalid @enderror"
            placeholder="{{ $placeholder ?? '' }}"
            @if($type === 'password') data-password @endif
        >
        @if ($type === 'password')
            <i class="bi bi-eye toggle-password" onclick="togglePassword(this)"></i>
        @endif
    @endif
    </div>

    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>



