<x-auth-layout>
    <form
        method="POST"
        action="{{ route('password.confirm') }}"
        class="uk-form-stacked uk-grid uk-grid-medium uk-child-width-1-1"
        uk-grid>
        @csrf

        <div>
            <label>{{ __('Password') }}</label>
            <div class="uk-form-controls">
                <input
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="uk-input " />
            </div>
            @error('password')
            <p class="uk-text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button
                type="submit"
                class="uk-button uk-button-primary uk-width-1-1 uk-border-rounded">
                {{ __('Confirm Password') }}
            </button>
        </div>

        <div>
            @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="uk-button uk-button-text uk-text-capitalize">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </div>
    </form>
</x-auth-layout>
