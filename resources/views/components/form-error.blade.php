@props(['input'])

@error($input)
<p class="uk-text-danger">{{ $message }}</p>
@enderror
