<div class="mx-3 my-3">
    
    <div class="row">
        <div class="col-12">
            <div class="col-12 mb-3">
                <div class="row align-items-center">
                    <div class="d-flex align-items-center px-3 py-1 mb-3 flex-grow-1" style="background-color: #def5e2; border-radius: 18px; min-width: 0;">
                        <input wire:model.lazy="search" type="text" name="table_search" class="form-control border-0 bg-transparent py-1"
                         placeholder="Search" style="min-width: 0; width: 100%; height: 32px;">
                        <i class="fas text-dark fa-search fa-lg ml-2 pt-2" style="height: 32px;"></i>
                    </div>
                    <div class="ml-3 mb-3" style="flex-shrink: 0;">
                        <button type="button" class="btn btn-link text-success p-0" style="height: 32px;" wire:click="addUser()">
                            <i class="fas fa-user-plus fa-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card" style="background-color: #def5e2; border-radius: 12px; box-shadow: none; border: 0px solid #c8e6c9;">
                <div class="card-header" style="border-radius: 18px 18px 0 0; background: transparent;">
                    <h3 class="card-title"></h3>
                    <div class="card-tools">
                        <div class="d-flex justify-content-end align-items-center  mr-2">
                            <div class="dropdown mr-2">
                                <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Sort by: <b >{{ Str::title($sortname) }}</b>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" wire:click="srt('name')" href="#">Name</a>
                                    <a class="dropdown-item" wire:click="srt('created_at')" href="#">Date Created</a>
                                    <a class="dropdown-item" wire:click="srt('role')" href="#">Role</a>
                                    {{-- <a class="dropdown-item" href="#">Status</a> --}}
                                </div>
                            </div>
                            <div class="dropdown mr-2">
                                <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Role: <b >{{ Str::title($rolename) }}</b>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" wire:click="accountrole(0)" href="#">Employee</a>
                                    <a class="dropdown-item" wire:click="accountrole(1)" href="#">Admin</a>
                                    <a class="dropdown-item" wire:click="accountrole(10)" href="#">Super Admin</a>
                                </div>
                            </div>
                            {{-- <button class="btn btn-outline-secondary">
                                <i class="fas fa-filter"></i>
                            </button> --}}
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0" style="border-radius: 0 0 18px 18px; overflow-x: auto;">
                    <table class="table table-hover text-nowrap mb-0" style="border-radius: 0 0 18px 18px; min-width: 700px;">
                        <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Date Created</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($profiles as $index => $item)
                            <tr>
                                <td>{{ $profiles->firstItem() + $index }}</td>
                                <td>
                                    
                                    @if($item->profile_photo_path)
                                        <img class="img-circle img-sm mr-2" src="{{ asset('storage/' . $item->profile_photo_path) }}" alt="User Image">
                                    @else
                                        <img class="img-circle img-sm mr-2" src="https://placehold.co/40x40/838584/ffffff?text={{ $this->getInitials($item->name) }}" alt="User Image">
                                    @endif
                                    {{ $item->name }}
                                </td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->created_at->format('M d, Y') }}</td>
                                <td>{{ $item->role == 0 ? 'Employee' : 'Admin' }}</td>
                                <td>
                                    <span>
                                        <span class="text-success" style="font-size: 1.2em;">●</span>
                                        <span class="text-dark">Active</span>
                                    </span>
                                </td>
                                <td>
                                    
                                    <button class="btn btn-link text-success px-2" wire:click="editUser({{ $item->id }})"><i class="fas fa-pencil-alt"></i></button>
                                    @if($item->role != 10)
                                        <button class="btn btn-link text-danger px-2" wire:click="confirmDelete({{ $item->id }})"><i class="fas fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-danger">No users found.</td>
                            </tr>
                            @endforelse
                            
                            
                            
                        </tbody>
                    </table>
                </div>
              
            </div>
           
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="userModalLabel">
                        @if ($isEditMode)
                        <i class="fas text-success fa-user-edit pr-2"></i>Edit Account
                        @else
                        <i class="fas text-success fa-user-plus pr-2"></i>Add Account
                        @endif
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveUser">
                        <div class="row mx-2 mt-3">
                            {{-- Picture Section --}}
                            <div class="col-12 col-lg-4">
                                <div class="card col-12 card-success card-outline">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-center">
                                            <h5>Picture</h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-center">
                                            @if ($photo && !$errors->has('photo'))
                                                <img class="img-circle" width="100" height="100" src="{{ $photo->temporaryUrl() }}" alt="New Profile Picture">
                                            @elseif ($existing_photo_path)
                                                <img class="img-circle" width="100" height="100" src="{{ asset('storage/' . $existing_photo_path) }}" alt="Current Profile Picture">
                                            @else
                                                <img class="img-circle" width="100" height="100" src="https://placehold.co/100x100/838584/ffffff?text={{ $this->getInitials($name) }}" alt="Default User Image">
                                            @endif
                                        </div>
                                        {{-- <div wire:loading wire:target="photo" class="text-center text-success mt-2">Uploading...</div> --}}
                                        @error('photo') <span class="text-danger d-block text-center">{{ $message }}</span> @enderror
                                        <div>
                                            <div class="d-flex justify-content-center mt-3">
                                                {{-- Hidden file input --}}
                                                <input type="file" wire:model="photo" id="photoInput" class="d-none">
                                                {{-- Browse Button --}}
                                                <button type="button" class="btn btn-success btn-sm" style="border-radius: 20px;" onclick="document.getElementById('photoInput').click();">
                                                    <i class="fas fa-camera"></i> Browse <i wire:loading wire:target="photo" class="fas fa-spinner fa-spin"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Account Details Section --}}
                            <div class="col-12 col-lg-8">
                                <div class="card col-12 card-success card-outline">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-center">
                                            <h5>Account</h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            
                                            <div class="form-group col-md-6">
                                                <i class="fas fa-user login-color"></i>
                                                <input id="name" type="text" wire:model.defer="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter name">
                                                @error('name') <span class="invalid-feedback" style="position: absolute; ">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="form-group col-md-6">
                                                <i class="fas fa-envelope login-color"></i>
                                                <input id="email" type="email" wire:model.defer="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email">
                                                @error('email') <span class="invalid-feedback" style="position: absolute; ">{{ $message }}</span> @enderror
                                            </div>
                                            {{-- @if (!$isEditMode) --}}
                                            <div class="form-group col-md-6">
                                                <i class="fas fa-lock login-color"></i>
                                                <input id="password" type="password" wire:model.defer="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ $isEditMode ? 'Leave blank to keep current' : 'Enter Password' }}">
                                                @error('password') <span class="invalid-feedback" style="position: absolute; ">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="form-group col-md-6">
                                                <i class="fas fa-lock login-color"></i>
                                                <input id="password_confirmation" type="password" wire:model.defer="password_confirmation" class="form-control" placeholder="Confirm Password">
                                            </div>
                                            {{-- @endif --}}
                                            
                                            <div class="form-group col-md-6 mt-2">
                                                <select id="user_role" wire:model.defer="user_role" class="form-control @error('user_role') is-invalid @enderror">
                                                    <option value="" disabled>Select role</option>
                                                    <option value="1">Admin</option>
                                                    <option value="0">Employee</option>
                                                </select>
                                                <i class="text-success fas fa-briefcase" style="margin-left: 0px"></i>
                                                @error('user_role') <span class="invalid-feedback" style="position: absolute; ">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button wire:loading.remove type="submit" class="btn btn-success">
                                <span wire:loading wire:target="saveUser"><i class="fas fa-spinner fa-spin"></i></span>
                                <span >Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>

    document.addEventListener('livewire:initialized', () => {
        // Show/Hide Modal Events
        Livewire.on('show-user-modal', () => {
            $('#userModal').modal('show');
        });
        Livewire.on('hide-user-modal', () => {
            $('#userModal').modal('hide');
        });

        // SweetAlert2 for Delete Confirmation
        Livewire.on('show-delete-confirmation', () => {
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#a7a8a7',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.dispatch('deleteConfirmed');
                }
            });
        });

        // Toastr for Alerts
        Livewire.on('show-alert', () => {
            // Check if toastr is available
            /* if (typeof toastr !== 'undefined') {
                toastr[event[0].type](event[0].message);
            } else {
                // Fallback to standard alert if toastr is not loaded
                alert(event[0].message);
            } */
        });
    });
</script>

</div>
