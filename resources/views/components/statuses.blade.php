@if(session('success'))
	<div class="uk-width-1-1 uk-margin">
		<x-alert type="success"
				 icon="circle-check"
				 message="{{ session('success') }}"
		/>
	</div>
@endif

@if(session('warning'))
	<div class="uk-width-1-1 uk-margin">
		<x-alert type="warning"
				 icon="circle-alert"
				 :message="session('warning')"
		/>
	</div>
@endif

@if(session('error'))
	<div class="uk-width-1-1 uk-margin">
		<x-alert type="danger"
				 icon="circle-x"
				 :message="session('error')"
		/>
	</div>
@endif

@if(session('status'))
	<div class="uk-width-1-1 uk-margin">
		<x-alert :message="session('status')"
				 icon="circle-info"
		/>
	</div>
@endif
