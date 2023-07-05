<div class="{{ $inputColClass }}">
    <label class="form-label" for="{{ $inputId }}">{{ $inputLabel }}
        <span class="text-danger">{{ $required ? '*' : '' }}</span>
    </label>
        <span class="text-danger error_txt {{ $inputId }}"></span>
    <input class="result form-control datepicker" id="{{ $inputId }}" type="{{ $inputType }}"
        data-value="{{ date('Y/M/d') }}" {{ $required }}>
</div>
