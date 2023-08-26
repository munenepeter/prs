@props(['type' => 'text', 'hasError' => false, 'disabled' => false])

<div class="uk-form-controls">
	<input type="{{ $type }}"
		{{ $attributes->class(['uk-form-danger' => $hasError])->merge(['class' => 'uk-input']) }}
		@disabled($disabled)
	>
</div>
