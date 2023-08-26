<div>
	<h1>Edit {{ $user->name }}</h1>

	<form wire:submit.prevent="edit"
		  class="uk-grid uk-grid-medium uk-child-width-1-1 uk-form-horizontal"
		  uk-grid
	>
		<div class="uk-grid-margin uk-first-column">
			<x-label for="firstname"
					 value="Name of the user"
			/>
			<x-input id="firstname"
					 type="text"
					 required
					 wire:model.defer="user.firstname"
			/>
			<x-form-error input="user.firstname" />
		</div>

		<div class="uk-grid-margin ">
			<x-label for="lastname"
					 value="Name of the user"
			/>
			<x-input id="lastname"
					 type="text"
					 required
					 wire:model.defer="user.lastname"
			/>
			<x-form-error input="user.lastname" />
		</div>

		{{--		<div class="uk-grid-margin uk-first-column">--}}
		{{--			<x-label for="email"--}}
		{{--					 value="Email of the user"--}}
		{{--			/>--}}
		{{--			<x-input id="email"--}}
		{{--					 type="email"--}}
		{{--					 wire:model.defer="email"--}}
		{{--				--}}{{-- 					 required --}}
		{{--			/>--}}
		{{--			<x-form-error input="email" />--}}
		{{--		</div>--}}

		<div class="uk-grid-margin uk-first-column">
			<x-label for="role"
					 value="Special Role for the user"
			/>
			<x-select id="role"
					  wire:model.lazy="role"
					  choose_text="Choose a special role"
					  :collection="$this->roles"
					  :hasErrors="$errors->has('role')"
			/>
			<x-form-error input="role" />
		</div>
		<div class="uk-grid-margin uk-first-column">
			<button type="submit"
					class="uk-button uk-button-primary uk-width-1-1"
					wire:loading.attr="disabled"
					wire:target="edit"
			>
				Edit user
			</button>
		</div>
	</form>
</div>
