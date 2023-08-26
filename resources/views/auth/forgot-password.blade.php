<nav         style=   background-color:powderblue;          
				class="uk-navbar"
				role="main-navigation"
				uk-navbar="align: center;mode: click;animation: reveal-top; animate-out: true; duration: 700"
			>
				<div class="uk-navbar-left">
					<a
						class="uk-navbar-item uk-logo"
						href="/"
					>
						<img
							src="{{ asset('img/gmk.png') }}"
							alt="GMK Brand Logo"
							width="100"
							height="100"
							loading="lazy"
						>
					</a>
				</div>
				<div class="uk-navbar-center">
					{{-- navigation links --}}
					<ul class="uk-navbar-nav uk-visible@m">
						<li class="{{ request()->is('/') ? 'uk-active' : '' }}">
							<a href="/">
                                Digita Divide Data Productivity Reporting System
                            </a>
						</li>
						@auth
							<li class="{{ request()->routeIs('dashboard') ? 'uk-active' : '' }}">
								<a href="{{ route('dashboard') }}">Dashboard</a>
							</li>
							<li>
								<a href="{{ route('reports.index', auth()->user()) }}">Daily Reports</a>
							</li>
						@endauth
					</ul>
				</div>
				<div class="uk-navbar-right">
					<div class="uk-navbar-item uk-hidden@m">
						<a
							class="uk-navbar-toggle uk-navbar-toggle-animate"
							uk-navbar-toggle-icon
							href="#mobile-nav"
							uk-toggle="animation: reveal-top; animate-out: true; duration: 700"
						>

						</a>
					</div>
					@guest
						<div class="uk-navbar-item uk-visible@m">
						</div>
					@else
						<ul class="uk-navbar-nav uk-visible@m">
							<li>
								<a href="#">
                                    <span
										class="uk-icon uk-preserve"
										uk-icon="icon: profile"
									>
                                    </span>
									<span
										class="uk-icon"
										uk-navbar-parent-icon
									></span>
								</a>
								<div class="uk-navbar-dropdown">
									<ul class="uk-nav uk-navbar-dropdown-nav">
										<li class="uk-nav-header">
											Hi, {{ Auth::user()->name }}
										</li>
										<li class="uk-nav-divider"></li>
										<li>
											<x-logout/>
										</li>
									</ul>
								</div>
							</li>
						</ul>
					@endguest
				</div>
			</nav>
<x-auth-layout>
    <div class="uk-width-1-1 uk-margin-bottom">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>


    <form
        method="POST"
        action="{{ route('password.email') }}"
        class="uk-form-stacked uk-grid uk-grid-medium uk-child-width-1-1"
        uk-grid>
        @csrf

        <div>
            <label class="uk-form-label">{{ __('Email') }}</label>
            <div class="uk-form-controls">
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="uk-input" />
            </div>

            @error('email')
            <p class="uk-text-danger">{{$message}}</p>
            @enderror
        </div>

        <div>
            <button
                type="submit"
                class="uk-button uk-button-primary uk-width-1-1 uk-border-rounded">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</x-auth-layout>
