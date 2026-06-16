<div class="mx-3 my-3">
    <div class="row">
        <div class="col-12">

            {{-- Session Message --}}
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="col-12 mb-3">
                <div class="row align-items-center">
                    {{-- Search Bar --}}
                    <div class="d-flex align-items-center px-3 py-1 mb-3 flex-grow-1" style="background-color: #def5e2; border-radius: 18px; min-width: 0;">
                        <input wire:model.lazy="search" type="text" name="table_search" class="form-control border-0 bg-transparent py-1"
                         placeholder="Search by user or file name" style="min-width: 0; width: 100%; height: 32px;">
                        <i class="fas text-dark fa-search fa-lg ml-2 pt-2" style="height: 32px;"></i>
                    </div>
                    
                    {{-- Status Filter --}}
                    <div class="ml-3 mb-3" style="flex-shrink: 0; background-color: #def5e2; border-radius: 18px; ">
                        <select wire:model.live="statusFilter" class="form-control border-0 bg-transparent text-dark" style="height: 48px; min-width: 150px;">
                            <option value="">All Statuses</option>
                            <option value="0">Pending</option>
                            <option value="1">Granted</option>
                            <option value="2">Rejected</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Requests Table Card --}}
            <div class="card" style="background-color: #def5e2; border-radius: 12px; box-shadow: none; border: 0px solid #c8e6c9;">
                <div class="card-header" style="border-radius: 18px 18px 0 0; background: transparent;">
                    <h3 class="card-title text-success font-weight-bold">Decryption Key Requests</h3>
                </div>
                <div class="card-body p-0" style="border-radius: 0 0 18px 18px; overflow-x: auto;">
                    <table class="table table-hover text-nowrap mb-0" style="border-radius: 0 0 18px 18px; min-width: 700px;">
                        <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th>Employee Name</th>
                                <th>File Name</th>
                                <th>Status</th>
                                <th>Date Requested</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $index => $item)
                            <tr>
                                <td>{{ $requests->firstItem() + $index }}</td>
                                <td>
                                    @if($item->user->profile_photo_path)
                                        <img class="img-circle img-sm mr-2" src="{{ asset('storage/' . $item->user->profile_photo_path) }}" alt="User Image">
                                    @else
                                        <img class="img-circle img-sm mr-2" src="https://placehold.co/40x40/838584/ffffff?text={{ $this->getInitials($item->user->name) }}" alt="User Image">
                                    @endif
                                    {{ $item->user->name }}
                                </td>
                                <td>{{ $item->file->file_name ?? 'File not found' }}</td>
                                <td>
                                    @if ($item->request_status === 0)
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif ($item->request_status === 1)
                                        <span class="badge bg-success">Granted</span>
                                    @elseif ($item->request_status === 2)
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    {{-- Show actions only if pending --}}
                                    @if($item->request_status === 0)
                                        <button class="btn btn-sm btn-success" wire:click="grantRequest({{ $item->id }})">
                                            <i class="fas fa-check"></i> Grant
                                        </button>
                                        <button class="btn btn-sm btn-danger" wire:click="rejectRequest({{ $item->id }})">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    @else
                                        {{-- Show a disabled button for processed requests --}}
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            Processed
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-danger">No requests found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                @if($requests->hasPages())
                    <div class="card-footer bg-transparent">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>