<div class="mx-3 my-3">
    <div class="row">
        <div class="col-12">
            
            {{-- Session Message (Added from my previous suggestion, good for feedback) --}}
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
                    <div class="d-flex align-items-center px-3 py-1 mb-3 flex-grow-1" style="background-color: #def5e2; border-radius: 18px; min-width: 0;">
                        <input wire:model.lazy="search" type="text" name="table_search" class="form-control border-0 bg-transparent py-1"
                         placeholder="Search by file name" style="min-width: 0; width: 100%; height: 32px;">
                        <i class="fas text-dark fa-search fa-lg ml-2 pt-2" style="height: 32px;"></i>
                    </div>
                    
                    {{-- Admin-only Add Button --}}
                    @if(auth()->user()->role == 1 || \Auth::user()->role == 10)
                        <div class="ml-3 mb-3" style="flex-shrink: 0; background-color: #def5e2; border-radius: 18px; ">
                            <button type="button" class="mx-2 btn btn-link text-dark p-0" style="height: 32px;" 
                            wire:click="addFile()">
                                <span class="ml-2">Upload File</span> <i class="fas fa-plus-circle text-success pl-2 pr-1" ></i> 
                            </button>
                        </div>
                    @endif
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
                                    <a class="dropdown-item" wire:click="srt('file_name')" href="#">File Name</a>
                                    <a class="dropdown-item" wire:click="srt('created_at')" href="#">Date Created</a>
                                    <a class="dropdown-item" wire:click="srt('updated_at')" href="#">Date Modified</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0" style="border-radius: 0 0 18px 18px; overflow-x: auto;">
                    <table class="table table-hover text-nowrap mb-0" style="border-radius: 0 0 18px 18px; min-width: 700px;">
                        <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th>File Name</th>
                                <th>File Type</th>
                                <th>Created By</th>
                                
                                {{-- CHANGE 1: New Status Header for Employees --}}
                               

                                <th>Date Created</th>
                                <th>Modified By</th>
                                <th>Date Modified</th>
                                 @if(auth()->user()->role != 1 || \Auth::user()->role != 10)
                                    <th>Status</th>
                                @endif
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($files as $index => $item)
                            <tr>
                                <td>{{ $files->firstItem() + $index }}</td>
                                <td>{{ $item->file_name }}</td>
                                @php
                                    $ext = strtolower(pathinfo($item->file_path, PATHINFO_EXTENSION));
                                    if (in_array($ext, ['xlsx', 'xls'])) {
                                        $type = 'Excel';
                                        $icon = 'fa-file-excel text-success'; // Green
                                    } elseif ($ext === 'pdf') {
                                        $type = 'PDF';
                                        $icon = 'fa-file-pdf text-danger'; // Red
                                    } elseif (in_array($ext, ['pptx', 'ppt'])) {
                                        $type = 'PowerPoint';
                                        $icon = 'fa-file-powerpoint text-warning'; // Orange
                                    } elseif (in_array($ext, ['docx', 'doc'])) {
                                        $type = 'Word';
                                        $icon = 'fa-file-word text-primary'; // Blue
                                    } else {
                                        $type = 'Unknown';
                                        $icon = 'fa-file text-secondary'; // Gray
                                    }
                                @endphp
                                <td><i class="fas {{ $icon }} mr-2"></i>{{ $type }}</td>
                                <td>
                                    @if($item->createdBy->profile_photo_path)
                                        <img class="img-circle img-sm mr-2" src="{{ asset('storage/' . $item->createdBy->profile_photo_path) }}" alt="User Image">
                                    @else
                                        <img class="img-circle img-sm mr-2" src="https://placehold.co/40x40/838584/ffffff?text={{ $this->getInitials($item->createdBy->name) }}" alt="User Image">
                                    @endif
                                    {{ $item->createdBy->name }}
                                </td>

                                {{-- CHANGE 2: New Status Data for Employees --}}
                                

                                <td>{{ $item->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($item->modifiedBy->profile_photo_path)
                                        <img class="img-circle img-sm mr-2" src="{{ asset('storage/' . $item->modifiedBy->profile_photo_path) }}" alt="User Image">
                                    @else
                                        <img class="img-circle img-sm mr-2" src="https://placehold.co/40x40/838584/ffffff?text={{ $this->getInitials($item->modifiedBy->name) }}" alt="User Image">
                                    @endif
                                    {{ $item->modifiedBy->name }}
                                </td>
                                <td>{{ $item->updated_at->format('M d, Y') }}</td>
                                @if(auth()->user()->role != 1 || \Auth::user()->role != 10)
                                    <td>
                                        @php
                                            $userRequest = $item->requests->first();
                                            $status = $userRequest ? $userRequest->request_status : null;
                                        @endphp
                                        
                                        @if ($status === 0)
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif ($status === 1)
                                            <span class="badge bg-success">Granted</span>
                                        @elseif ($status === 2)
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">Not Requested</span>
                                        @endif
                                    </td>
                                @endif
                                {{-- CHANGE 3: Updated Action Column --}}
                                <td>
                                    <button class="btn btn-link text-success px-2" wire:click="downloadFile({{ $item->id }})" title="Download">
                                        <div wire:loading wire:target="downloadFile({{ $item->id }})">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </div>
                                        <div wire:loading.remove wire:target="downloadFile({{ $item->id }})">
                                            <i class="fas fa-download"></i>
                                        </div>
                                    </button>
                                    
                                    @if(auth()->user()->role == 1  || \Auth::user()->role == 10)  {{-- ADMIN ACTIONS --}}
                                        <button class="btn btn-link text-info px-2" wire:click="viewFile({{ $item->id }})" title="View Details"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-link text-warning px-2" wire:click="editFile({{ $item->id }})" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                                        <button class="btn btn-link text-danger px-2" wire:click="confirmDelete({{ $item->id }})" title="Delete"><i class="fas fa-trash"></i></button>
                                    
                                    @else  {{-- EMPLOYEE ACTIONS --}}
                                        @php
                                            // Re-check status for this button logic
                                            $userRequest = $item->requests->first();
                                            $status = $userRequest ? $userRequest->request_status : null;
                                        @endphp

                                        @if ($status === 1) {{-- 1: Granted --}}
                                            <button class="btn btn-link text-info px-2" wire:click="showGrantedKey({{ $item->id }})" title="Show Key">
                                                <i class="fas fa-key"></i>
                                            </button>
                                        @elseif ($status === 2) {{-- 2: Rejected --}}
                                            <button class="btn btn-link text-warning px-2" wire:click="sendRequest({{ $item->id }})" title="Resend Request">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        @elseif ($status === 0) {{-- 0: Pending --}}
                                            <button class="btn btn-link text-secondary px-2" disabled title="Pending">
                                                <i class="fas fa-clock"></i>
                                            </button>
                                        @else {{-- null: Not Requested --}}
                                            <button class="btn btn-link text-primary px-2" wire:click="sendRequest({{ $item->id }})" title="Request Key">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>

                            {{-- CHANGE 4: New Inline Key Display Row --}}
                            @if ($inlineKeyFileId == $item->id && !empty($inlineKey))
                                <tr style="background-color: #f8f9fa;">
                                    {{-- Use dynamic colspan --}}
                                    <td colspan="{{ auth()->user()->role > 0 ? 7 : 8 }}"> 
                                        <div class="alert alert-info d-flex justify-content-between align-items-center mb-0 py-2">
                                            <div>
                                                <strong>Decryption Key:</strong>
                                                <code class="ml-2">{{ $inlineKey }}</code>
                                            </div>
                                            <button wire:click="closeKey" class="close" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            @empty
                            <tr>
                                {{-- CHANGE 5: Dynamic Colspan for Empty Message --}}
                                <td colspan="{{ auth()->user()->role > 0 ? 7 : 8 }}" class="text-center text-danger">No files found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
    </div>

    
    <div wire:ignore.self class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="fileModalLabel">
                        @if ($isEditMode)
                        <i class="fas text-success fa-pen pr-2"></i>Edit File
                        @else
                        <i class="fas text-success fa-plus pr-2"></i>Add File
                        @endif
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveFile">
                        <div class="form-group">
                            <i class="fas fa-file login-color"></i>
                            <input id="fileName" type="text" wire:model.defer="fileName" class="form-control @error('fileName') is-invalid @enderror" placeholder="Enter file name">
                            @error('fileName')  <span class="invalid-feedback" style="position: absolute; ">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="text-success" for="file">File (Excel, PDF, PowerPoint, Word)</label>
                            <div class="custom-file">
                                <input id="file" type="file" wire:model="file" class="custom-file-input @error('file') is-invalid @enderror" accept=".xlsx,.xls,.pdf,.pptx,.ppt,.docx">
                                <label class="custom-file-label text-grey" for="file">
                                    @if ($file)
                                        {{ $file->getClientOriginalName() }}
                                    @else
                                        Choose File
                                    @endif
                                </label>
                                
                                @error('file') <span class="invalid-feedback" style="position: absolute; ">{{ $message }}</span> @enderror
                            </div>
                            <div wire:loading>
                                <span class="mt-2 text-success">Loading File....</span>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save</button>
                            <div wire:loading wire:target="saveFile">
                                <i class="fas fa-spinner fa-spin"></i> Saving...
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div wire:ignore.self class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="downloadModalLabel"><i class="fas fa-key text-success pr-2"></i>Enter Decryption Key</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="decryptAndDownload">
                        <div class="form-group">
                            <i class="fas fa-key login-color"></i>
                            <input id="decryptionKey" type="password" wire:model.defer="decryptionKey" class="form-control @error('decryptionKey') is-invalid @enderror" placeholder="Enter the decryption key">
                            @error('decryptionKey') <span class="invalid-feedback" style="position: absolute; ">{{ $message }}</span> @enderror
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Download</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div wire:ignore.self class="modal fade" id="viewFileModal" tabindex="-1" aria-labelledby="viewFileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="viewFileModalLabel"><i class="fas fa-file-alt pr-2 text-success"></i>File Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>File Name:</strong> {{ $selectedFile ? $selectedFile->file_name : '' }}</p>
                    <p><strong>Decryption Key:</strong> {{ $selectedFile ? $selectedFile->encryption_key : '' }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-file-modal', () => $('#fileModal').modal('show'));
        Livewire.on('hide-file-modal', () => $('#fileModal').modal('hide'));
        Livewire.on('show-download-modal', () => $('#downloadModal').modal('show'));
        Livewire.on('show-view-file-modal', () => $('#viewFileModal').modal('show'));

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
    });
</script>
</div>