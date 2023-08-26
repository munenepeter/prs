<div class="uk-margin-vertical">
	@if ($paginator->hasPages())
		@php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : $this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1)

		<nav aria-label="Pagination Navigation">
			<ul class="uk-pagination uk-flex-center"
				uk-margin
			>
				{{-- Previous Page Link --}}
				@if ($paginator->onFirstPage())
					<li class="page-item uk-disabled"
						aria-disabled="true"
						aria-label="@lang('pagination.previous')"
					>
						<span class="uk-icon uk-pagination-previous"
							  uk-pagination-previous="icon: arrow-left"
						></span>
					</li>
				@else
					<li>
						<a
							dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
							wire:click="previousPage('{{ $paginator->getPageName() }}')"
							wire:loading.attr="disabled"
							rel="prev"
							aria-label="@lang('pagination.previous')"
						>
									<span class="uk-icon uk-pagination-previous"
										  uk-pagination-previous="icon: arrow-left"
									></span>
						</a>
					</li>
				@endif

				{{-- Pagination Elements --}}
				@foreach ($elements as $element)
					{{-- "Three Dots" Separator --}}
					@if (is_string($element))
						<li class="uk-disabled"
							aria-disabled="true"
						><span class="page-link">{{ $element }}</span></li>
					@endif

					{{-- Array Of Links --}}
					@if (is_array($element))
						@foreach ($element as $page => $url)
							@if ($page == $paginator->currentPage())
								<li class="uk-active"
									wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page-{{ $page }}"
									aria-current="page"
								><span class="page-link">{{ $page }}</span></li>
							@else
								<li class="page-item"
									wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page-{{ $page }}"
								>
									<a
										class="page-link"
										wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
									>{{ $page }}</a>
								</li>
							@endif
						@endforeach
					@endif
				@endforeach

				{{-- Next Page Link --}}
				@if ($paginator->hasMorePages())
					<li>
						<a
							dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"

							wire:click="nextPage('{{ $paginator->getPageName() }}')"
							wire:loading.attr="disabled"
							rel="next"
							aria-label="@lang('pagination.next')"
						>
								<span class="uk-icon uk-pagination-next"
									  aria-hidden="true"
									  uk-pagination-next="icon: arrow-right"
								></span>
						</a>
					</li>
				@else
					<li class="uk-disabled"
						aria-disabled="true"
						aria-label="@lang('pagination.next')"
					>
						<span class="uk-icon uk-pagination-next"
							  aria-hidden="true"
							  uk-pagination-next="icon: arrow-right"
						></span>
					</li>
				@endif
			</ul>
		</nav>
	@endif
</div>
