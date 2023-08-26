@props([
'mobile' => false,
])

@if ($mobile)
	<a
		href="{{ route('logout') }}"
		class="uk-icon"
		uk-icon="icon: logout;"
		onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

	</a>
@else
	<a
		href="{{ route('logout') }}"
		onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
         <span
			 uk-icon="icon: logout;"
			 class="uk-margin-small-right"></span>
		Logout
	</a>
@endif

<form
	id="logout-form"
	action="{{ route('logout') }}"
	method="POST"
	uk-hidden>
	@csrf
</form>
