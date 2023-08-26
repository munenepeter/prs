@push ('scripts')
	<script>
		document.addEventListener('task-removed', event => {
			successNotification(event.detail.message)
		});

		document.addEventListener('task-exists', event => {
			dangerNotification(event.detail.message)
		});

		document.addEventListener('task-doesnt-exists', event => {
			dangerNotification(event.detail.message)
		});
	</script>
@endpush
