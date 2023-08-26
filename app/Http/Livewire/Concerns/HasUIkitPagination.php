<?php

namespace App\Http\Livewire\Concerns;

use Livewire\WithPagination;

trait HasUIkitPagination
{
    use WithPagination;

    protected string $paginationTheme = 'uikit';

    public function updatedPaginators()
    {
        $this->dispatchBrowserEvent('pageUpdated');
    }
}
