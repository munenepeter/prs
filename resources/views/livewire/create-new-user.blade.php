<div>
    <h1>Create New User</h1>

    <form wire:submit.prevent="create" class="uk-grid uk-grid-medium uk-child-width-1-1 uk-form-horizontal" uk-grid>

        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-1-2@s">
                <x-label for="firstname" value="Firstname of the user" />
                <input class="uk-input" id="firstname" type="text" required wire:model.defer="firstname">
                <x-form-error input="firstname" />
            </div>
            <div class="uk-width-1-2@s">
                <x-label for="lastname" value="Lastname of the user" />
                <input class="uk-input" id="lastname" type="text" required wire:model.defer="lastname" />
                <x-form-error input="lastname" />
            </div>
            <div class="uk-width-1-2@s">
                <x-label for="email" value="Email of the user" />
                <input class="uk-input" id="email" type="email" wire:model.defer="email" required />
                <x-form-error input="email" />
            </div>
            <div class="uk-width-1-2@s">
                <x-label for="phone_number" value="Phone Number of the user" />
                <input class="uk-input" id="phone_number" type="text" wire:model.defer="phone_number"
                  required />
                <x-form-error input="phone_number" />
            </div>
            <div class="uk-width-1-2@s">
                <x-label for="gender" value="Gender for the user" />
                <x-select id="gender" wire:model.lazy="gender" :collection="$this->genders" :hasErrors="$errors->has('gender')" />
                <x-form-error input="gender" />
            </div>
            <div class="uk-width-1-2@s">
                <x-label for="role" value="Special Role for the user" />
                <x-select id="role" wire:model.lazy="role" choose_text="Choose a special role" :collection="$this->roles"
                    :hasErrors="$errors->has('role')" />
                <x-form-error input="role" />
            </div>
        </div>

        <div class="uk-grid-margin uk-first-column">
            <button type="submit" class="uk-button uk-button-primary uk-width-1-1" wire:loading.attr="disabled"
                wire:target="create">
                Create new user
            </button>
        </div>
    </form>
</div>
