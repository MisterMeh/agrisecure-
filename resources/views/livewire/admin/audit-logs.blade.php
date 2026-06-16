<div class="mx-3 my-3">
    <div class="row">
        <div class="col-12">
            <div class="col-12 mb-3">
                <div class="row align-items-center">
                    <div class="d-flex align-items-center px-3 py-1 mb-3 flex-grow-1" style="background-color: #def5e2; border-radius: 18px; min-width: 0;">
                        
                        <select wire:model.lazy="selectedUserId" class="form-control border-0 bg-transparent py-1" style="min-width: 0; width: 100%; height: 32px; max-width: 200px; margin-right: 10px;">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <input wire:model.lazy="startDate" type="date" class="form-control border-0 bg-transparent py-1" style="min-width: 0; width: 100%; height: 32px;"> <span class="px-2">to</span> <input wire:model.lazy="endDate" type="date" class="form-control border-0 bg-transparent py-1" style="min-width: 0; width: 100%; height: 32px;"> <button wire:click="filter" class="btn btn-link text-success p-0 ml-2" style="height: 32px;"> <i class="fas fa-filter fa-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card" style="background-color: #def5e2; border-radius: 12px; box-shadow: none; border: 0px solid #c8e6c9;"> <div class="card-body p-0" style="border-radius: 0 0 18px 18px; overflow-x: auto;"> <table class="table table-sm table-hover text-nowrap mb-0" style="border-radius: 0 0 18px 18px; min-width: 700px;"> <thead>
                            <tr>
                                <th>User</th> <th>Log</th> <th>Date and Time</th> </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log) <tr>
                                <td>{{ $log->user->name ?? 'System' }}</td> <td>{{ $log->activity }}</td> <td>{{ $log->created_at->format('h:i a F j, Y') }}</td> </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-danger">No logs found.</td> </tr>
                            @endforelse </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix" style="background: transparent; border-top: none;"> {{ $logs->links() }} </div>
            </div>
        </div>
    </div>
</div>