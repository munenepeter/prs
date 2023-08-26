<div class="uk-position-bottom-right uk-position-small uk-hidden">
	<a href="#main"
	   class="uk-icon-button"
	   uk-totop
	   uk-scroll="offset: 160"></a>
</div>



@push('scripts')
	<script>
		window.addEventListener('pageUpdated', event => {
			setTimeout(() => {
				UIkit.scroll(
					document.querySelector("[uk-totop]")
				)
					.scrollTo(
						document.querySelector('#main')
					);
			}, 900);
		});

	</script>
@endpush
