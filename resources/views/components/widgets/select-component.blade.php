<div class="{{ $inputColClass }}">
    <label class="form-label" for="{{ $inputId }}">{{ $inputLabel }}
        <span class="text-danger">{{ $required ? '*' : '' }}</span></label><span
        class="text-danger error_txt {{ $inputId }}"></span>
    <select class="form-select" id="{{ $inputId }}" {{ $required }}>
        <option value="">Select A Value</option>
        @foreach ($filteredOptionList as $option)
            <option value="{{ $option }}">{{ $option }}</option>
        @endforeach
    </select>
</div>
