<aside
	id="sidebar"
	class="uk-sticky uk-visible@m uk-position-z-index"
	uk-sticky="offset: 150; end: #dashboard; media: @m;"
>
	<div class="uk-width-1-1">
		<ul
			class="uk-nav uk-nav-secondary  "
			uk-nav
			uk-margin
		>
			<li class="uk-nav-header uk-text-bold uk-first-column">Overview</li>


			<li @class([
				'uk-margin-small-top uk-first-column',
				'uk-active' => request()->routeIs('dashboard')
			])>
				<a href="{{ route('dashboard') }}">
                    <span
						class="uk-icon uk-margin-small-right"
						uk-icon="icon: home-alt"
					></span>
					Dashboard
				</a>
			</li>
			<li class="uk-nav-divider uk-margin-small-top uk-first-column"></li>
			<li class="uk-nav-header uk-text-bold uk-margin-small-top uk-first-column">Productivity Report</li>
			<li @class([
				'uk-margin-small-top uk-first-column',
				'uk-active' => request()->routeIs('reports.create')
			])>
				<a href="{{ route('reports.create') }}">
                    <span
						class="uk-icon uk-margin-small-right"
						uk-icon="icon: new-section"
					></span>
					Create Daily Report
				</a>
			</li>
			<li @class([
				'uk-margin-small-top uk-first-column',
				'uk-active' => request()->routeIs('reports.index')
			])>
				<a href="{{ route('reports.index', auth()->user()) }}">
				                    <span
										class="uk-icon uk-margin-small-right"
										uk-icon="icon: report"
									></span>
					View Your Daily Reports
				</a>
			</li>
			<li class="uk-nav-divider uk-margin-small-top uk-first-column"></li>
			@can('manage-projects')
				<li class="uk-nav-header uk-text-bold uk-margin-small-top uk-first-column">Project Management</li>
				<li @class([
				'uk-margin-small-top uk-first-column',
				'uk-active' => request()->routeIs('projects.create')
			])>
					<a href="{{ route('projects.create') }}">
                    <span
						class="uk-icon uk-margin-small-right"
						uk-icon="icon: layout-grid-add"
					></span>
						Create New Project
					</a>
				</li>
				<li @class([
				'uk-margin-small-top uk-first-column',
				'uk-active' => request()->routeIs('projects.index')])>
					<a href="{{ route('projects.index') }}">
                    <span
						class="uk-icon uk-margin-small-right"
						uk-icon="icon: subtask"
					></span>
						View Projects
					</a>
				</li>
				<li class="uk-nav-divider uk-margin-small-top uk-first-column"></li>
			@endcan
			@can('is-admin')
				<li class="uk-nav-header uk-text-bold uk-margin-small-top uk-first-column">User Management</li>
				<li @class([
				'uk-margin-small-top uk-first-column',
				'uk-active' => request()->routeIs('users.create')])>
					<a href="{{ route('users.create') }}">
                    <span
						class="uk-icon uk-margin-small-right"
						uk-icon="icon: user-plus"
					></span>
						Create New User
					</a>
				</li>
				<li @class([
				'uk-margin-small-top uk-first-column',
				'uk-active' => request()->routeIs('users.index')])>
					<a href="{{ route('users.index') }}">
                    <span
						class="uk-icon uk-margin-small-right"
						uk-icon="icon: users"
					></span>
						View All Users
					</a>
				</li>
			@endcan

			<li class="uk-margin-small-top uk-first-column">
				<x-logout/>
			</li>
		</ul>
	</div>
</aside>
