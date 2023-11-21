<div class="uk-width-1-1">
    <h1 class="uk-heading-small uk-text-bold uk-text-capitalize">
        {{ $project->name }} Tasks
    </h1>
    <hr class="uk-divider-small">

    <div class="uk-flex uk-flex-between uk-flex-middle">
        <h2>All Tasks</h2>
        <a href="{{ route('projects.index') }}" class="uk-button-primary uk-button">All Projects</a>
    </div>
    <div class="uk-overflow-auto">
        <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
            <thead>
                <tr>
                    <th class="uk-table-shrink">#</th>
                    <th class="uk-table-expand">Name</th>
                    <th class="uk-table-expand">Target</th>
                    <!-- <th class="uk-width-small">Unit type</th> -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($project->tasks as $task)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $task->name }}</td>
                        <td>
                            {{ $task->target }}
                            @if ($task->unit_type->name === 'HOUR')
                                mins per day
                            @else
                                {{ strtolower($task->unit_type->name) }} per
                                hr
                            @endif



                        </td>
                        <td>{{ $task->unit_type->name }}</td>
                        <td>
                            <div class="uk-button-group">
                                <button uk-toggle="target: #edit-task-{{ $task->id }}" type="button"
                                    class="uk-button uk-button-secondary uk-margin-small-right">Edit
                                </button>
                                <livewire:edit-task-modal key="{{ $task->id . ' ' . $task->name }}" :task_id="$task->id" />

                                {{-- <button type="button"
                                    onclick="confirm('Are you sure you want to delete this project task?') || event.stopImmediatePropagation()"
                                    wire:click="deleteTask({{ $task->id }})" class="uk-button uk-button-danger">
                                    Delete
                                </button> --}}
                            </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    @push('scripts')
        <script>
            const closeModal = elem => UIkit.modal(elem).hide();

            window.addEventListener('failed-to-edit-task', event => {
                closeModal(event.detail.modal);

                dangerNotification(event.detail.message);
            });

            window.addEventListener('edit-task-successfully', event => {
                closeModal(event.detail.modal);

                successNotification(event.detail.message);
            });

            window.addEventListener('task-deleted', event => {

                successNotification(event.detail.message);
            });

            window.addEventListener('no-change', event => {
                closeModal(event.detail.modal);
            });
        </script>
    @endpush
</div>
