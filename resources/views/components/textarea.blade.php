@props(['hasError' => false, 'disabled' => false])
<div class="uk-form-controls">
	<textarea rows="5" {{ $attributes->class(['uk-form-danger' => $hasError])->merge(['class' => 'uk-textarea ']) }}
		@disabled($disabled)
	></textarea>
</div>
