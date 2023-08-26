<x-auth-layout>

    <p>
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <p class="uk-text-success">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </p>
    @endif

    <section
        class="uk-grid uk-grid-small uk-child-width-expand uk-flex-middle"
        uk-grid>
        <div class="uk-panel">
            <form
                method="POST"
                action="{{ route('verification.send') }}">
                @csrf

                <button
                    type="submit"
                    class="uk-button uk-button-primary uk-border-rounded">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>
        </div>
        <div class="uk-panel uk-width-auto">
            <form
                method="POST"
                action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="uk-button uk-button-link">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </section>

</x-auth-layout>

@extends('layouts.app')

@section('content')




@endsection
