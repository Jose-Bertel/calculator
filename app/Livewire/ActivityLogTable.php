<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityLogTable extends Component
{
    use WithPagination;

    public function render()
    {
        $logs = Activity::with('causer')
            ->latest()
            ->paginate(10);

        return view('livewire.activity-log-table', [
            'logs' => $logs,
        ]);
    }
}
