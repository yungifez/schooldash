<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class ApplicationHistory extends Component
{
    public User $applicant;

    public function mount()
    {
        $this->setErrorBag(session()->get('errors', new \Illuminate\Support\MessageBag())->getMessages());

        $this->applicant->loadMissing('accountApplication', 'accountApplication.statuses');
    }

    public function render()
    {
        return view('livewire.application-history');
    }
}
