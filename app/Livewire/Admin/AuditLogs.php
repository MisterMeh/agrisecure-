<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AuditLog;
use Livewire\WithPagination;

class AuditLogs extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $startDate;
    public $endDate;

    public function render()
    {
        $query = AuditLog::with('user')->latest();

        if ($this->startDate && $this->endDate) {
            $endDateWithTime = $this->endDate . ' 23:59:59';
            $query->whereBetween('created_at', [$this->startDate, $endDateWithTime]);
        }

        $logs = $query->paginate(10);
        return view('livewire.admin.audit-logs', ['logs' => $logs]);
    }

    public function filter()
    {
        // The render method will be re-run with the updated date properties.
        $this->resetPage();
    }
}
