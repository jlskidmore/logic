<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class NotificationComponent extends Component
{


    /**
     * Render Component
     * @return View
     */
    public function render(): View
    {
        return view('admin.partials.core.notifications');
    }

    /**
     * Mark all read.
     * @return void
     */
    public function markRead(): void
    {
        // TODO: to be clarified, for now just commented
        // user()->notifications()->where('type', EventType::SEV_NOTIFY)->update(['read' => true]);
    }

}
