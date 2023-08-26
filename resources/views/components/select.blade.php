@props(['collection', 'choose_text' => 'Choose an option', 'hasError' => false, 'disabled' => false])

<div class="uk-form-controls">
	<select {{ $attributes->class(['uk-form-danger' => $hasError])->merge(['class' => 'uk-select']) }} @disabled($disabled)>
		<option value="-1">{{ $choose_text }}</option>
		@foreach ($collection as $key => $value)
			<option value="{{ $key }}">{{ $value }}</option>
		@endforeach
	</select>
</div>
