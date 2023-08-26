@props(['value'])

<label {{ $attributes->merge(['class' => 'uk-form-label']) }}>{{ $value }}</label>
