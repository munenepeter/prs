<div class="uk-width-1-1">
    <div class="uk-margin-medium">
        <a href="{{ route('users.create') }}" class="uk-button uk-button-primary">Create New User</a>
    </div>
    <div class="uk-overflow-auto">
        <div class="uk-margin uk-flex uk-flex-middle uk-flex-between">
            <form class="uk-search uk-search-default">
                <span uk-search-icon></span>
                <input wire:model.debounce.300ms="query" class="uk-search-input" type="search" placeholder="Search"
                    aria-label="Search">
            </form>

            <div class="uk-form-controls uk-margin-small-left">
                <label><input class="uk-radio" type="radio" wire:model="selectedRole" value="all">&nbsp;
                    All</label>
                <label><input class="uk-radio" type="radio" wire:model="selectedRole" value="admin">&nbsp;
                    Admins</label>
                <label><input class="uk-radio" type="radio" wire:model="selectedRole" value="project_manager">&nbsp;
                    Project Managers</label>
                <label><input class="uk-radio" type="radio" wire:model="selectedRole" value="user">&nbsp;
                    Users</label>
            </div>
        </div>
        @php
            $usersCount = 0;
            $managersCount = 0;
            $adminsCount = 0;
        @endphp

        <table style="font-size:14px;" class="uk-table uk-table-small uk-table-divider uk-table-middle uk-table-striped uk-table-responsive">

            <thead>
                <tr>
                    <th>User</th>
                    <th>Contact Info</th>
                    <th>Gender</th>
                    <th class="uk-table-shrink">Role</th>
                    <th class="uk-table-expand uk-text-center@m">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="uk-text-capitalize">
                            <p>{{ $user->fullname }}</p>
                            <p class="uk-text-meta">{{ $user->staff_no }}</p>
                        </td>
                        <td>
                            <p>{{ $user->email }}</p>
                            <p class="uk-text-meta">{{ $user->phone_number }}</p>
                        </td>
                        <td>
                            <p>{{ $user->gender }}</p>
                        </td>
                        <td class="uk-text-uppercase uk-table-shrink">
                            @php
                                switch ($user->roles->implode('name.value', ', ')) {
                                    case 'admin':
                                        $adminsCount++;
                                        break;
                                    case 'admin, user':
                                        $adminsCount++;
                                        $usersCount++;
                                        break;
                                    case 'project_manager':
                                        $managersCount++;
                                        break;
                                    case 'project_manager, user':
                                        $managersCount++;
                                        $usersCount++;
                                        break;
                                    case 'user':
                                        $usersCount++;
                                        break;

                                    default:
                                        $usersCount++;
                                        break;
                                }

                            @endphp
                            {{ $user->roles->implode('name.value', ', ') ?? 'Users' }}
                        </td>
                        <td class="uk-table-expand">
                            <div class="uk-button-group">


                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="uk-button uk-button-secondary uk-margin-small-right uk-button-small">
                                    Edit
                                </a>

                                {{-- <button type="button"
                                   onclick="confirm('Are you sure you want to delete this user?') || event.stopImmediatePropagation()"
                                    wire:click="deleteUser('{{ $user->email }}')" class="uk-button uk-button-danger uk-button-small"
                                    @if (auth()->user()->id === $user->id) disabled @endif>
                                    Delete
                                </button> --}}



                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-alert message="There are no users at the moment">
                                <a href="{{ route('projects.create') }}" class="uk-button uk-button-text">
                                    Create a new user
                                </a>
                            </x-alert>

                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-weight: 800" >Total: {{ $users->total() }}</td>
                    <td style="font-weight: 800" >Users: {{ $usersCount }} </td>
                    <td style="font-weight: 800" colspan="2" >Managers: {{ $managersCount }} </td>
                    <td style="font-weight: 800" >Admins: {{ $adminsCount }} </td>
                </tr>
                <tr>
                    <td colspan="7">
                        {{ $users->links() }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <a href="#main" class="uk-hidden" uk-scroll="offest: -10"></a>

    @push('scripts')
        <script>
            window.addEventListener('pageUpdated', () => {
                setTimeout(() => {
                    UIkit.scroll(document.querySelector("[uk-scroll]"))
                        .scrollTo(document.querySelector('#main'))
                }, 1000);
            });

            document.addEventListener('user-deleted', event => {
                successNotification(event.detail.message)
            });
        </script>
    @endpush
</div>
