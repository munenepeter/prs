<header
	class="uk-sticky uk-position-z-index-large"
	uk-sticky="sel-target: .uk-background-default; cls-active: uk-navbar-sticky;animation: uk-animation-slide-top;show-on-up: true"
>
	<div class="uk-background-default uk-box-shadow-small">
		<div class="uk-container uk-container-large">
			<nav
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
							width="50"
							height="50"
							loading="lazy"
						>

					</a>


				</div>

				<div class="uk-navbar-center">
					{{-- navigation links --}}
					<ul class="uk-navbar-nav uk-visible@m">
						<li class="{{ request()->is('/') ? 'uk-active' : '' }}">
							<a href="/">Home</a>
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
							<a
								href="{{ route('login') }}"
								class="uk-button uk-button-secondary uk-border-rounded"
							>Login</a>
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
											Hi, {{ Auth::user()->fullname }}
										</li>

										<li class="uk-nav-divider"></li>
										<li>
											<x-logout />
										</li>
									</ul>
								</div>
							</li>

						</ul>
					@endguest
				</div>
			</nav>
		</div>

		<aside class="uk-position-relative uk-position-z-index">
			<div
				class="uk-position-top"
				id="mobile-nav"
				hidden
			>
				<div class="uk-background-default uk-box-shadow-small uk-padding">
					<div
						class="uk-child-width-1-1 uk-grid uk-grid-stack"
						uk-grid
					>
						<div>
							<nav
								class="uk-panel"
								role="main-mobile-navigation"
							>
								<ul
									class="uk-nav uk-nav-default uk-nav-divider uk-nav-parent-icon uk-nav-accordion"
									uk-nav="targets: > .js-accordion;"
								>
									<li class="{{ request()->is('/') ? 'uk-active' : '' }}">
										<a href="/">Home</a>
									</li>
									@auth
										<li class="{{ request()->routeIs('dashboard') ? 'uk-active' : '' }}">
											<a href="{{ route('dashboard') }}">Dashboard</a>
										</li>
									@endauth

									@guest
										<li>
											<a href="{{ route('login') }}">Login</a>
										</li>
									@else
										<li class="uk-parent js-accordion">
											<a
												href="#"
												class="uk-flex uk-flex-middle"
											>

                                                <span
													class="uk-icon "
													uk-icon="icon: profile"
												>

                                                </span>
												<span>
                                                    Your settings
                                                </span>
											</a>
											<ul class="uk-nav-sub">
												<li class="uk-nav-header">
													Hi, {{ Auth::user()->fullname }}
												</li>

												<li class="uk-nav-divider"></li>


												<li>
													<x-logout />
												</li>

											</ul>
										</li>
									@endguest
								</ul>

							</nav>
						</div>
						<div class="uk-grid-margin uk-first-column">
							<footer
								class="uk-panel"
								aria-label="mobile-navigation-footer"
							>
								<div class="uk-panel">

									<hr>
									<p class="uk-text-meta uk-text-center">©
										<script>
											document.currentScript.insertAdjacentHTML(
												'afterend',
												'<time datetime="' + new Date().toJSON() + '">' + new Intl
													.DateTimeFormat(document.documentElement.lang, {
														year: 'numeric'
													}).format() + '</time>');
										</script>
										<time datetime=""></time>
										<strong>{{ config('app.name') }}</strong>. All rights reserved.
									</p>

									<div
										class="uk-child-width-auto uk-grid-small uk-flex uk-flex-center uk-grid"
										uk-grid
									>
										<div>
											<a
												class="uk-icon-link uk-icon"
												href="https://bonge-inc.co.ke"
												id="Bonge Inc website link"
												uk-icon="icon: world;"
											>
											</a>
										</div>
										<div>
											<a
												class="uk-icon-link uk-icon"
												href="https://www.facebook.com/bonge.inc/"
												id="Bonge Inc Facebook Page"
												uk-icon="icon: facebook;"
											>
											</a>
										</div>

										<div>
											<a
												class="uk-icon-link uk-icon"
												href="https://github.com/bonge-ian"
												id="Bonge's Github Page"
												uk-icon="icon: github;"
											>
											</a>
										</div>

									</div>
								</div>

							</footer>
						</div>
					</div>
				</div>
			</div>
		</aside>
	</div>

</header>
