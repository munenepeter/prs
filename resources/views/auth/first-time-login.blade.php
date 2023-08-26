<x-auth-layout title="Change Password">
	<form
		method="POST"
		action="{{ route('login.first.time.store') }}"
		class="uk-form-stacked uk-grid uk-grid-medium uk-child-width-1-1"
		uk-grid>
		@csrf

		<div>
			<p class="uk-text-lead">Welcome. You need to change your password in order to continue.</p>
		</div>

		<div>
			<label class="uk-form-label">{{ __('Email') }}</label>
			<div class="uk-form-controls">
				<input
					class="uk-input"
					type="email"
					name="email"
					value="{{ old('email', $request->email) }}"
					required
					autofocus/>
			</div>
			@error('email')
			<p class="uk-text-danger">{{ $message }}</p>
			@enderror
		</div>

		<div>
			<label class="uk-form-label">{{ __('New Password') }}</label>
			<div class="uk-form-controls">
				<input
					class="uk-input"
					type="password"
					name="password"
					required
					autocomplete="new-password"/>
			</div>
			@error('password')
			<p class="uk-text-danger">{{ $message }}</p>
			@enderror
		</div>

		<div>
			<label class="uk-form-label">{{ __('Confirm Password') }}</label>
			<div class="uk-form-controls">
				<input
					class="uk-input"
					type="password"
					name="password_confirmation"
					required
					autocomplete="new-password"/>
			</div>
			@error('password_confirmation')
			<p class="uk-text-danger">{{ $message }}</p>
			@enderror
		</div>

		<div>
			<button
				type="submit"
				class="uk-button uk-button-primary uk-width-1-1 uk-border-rounded">
				{{ __('Reset Password') }}
			</button>
		</div>
	</form>
</x-auth-layout>
