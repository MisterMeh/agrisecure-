<div class="mx-3 my-3">
    <div class="row">
        <div class="col-12">
            <div class="col-12 mb-3">
                <div class="row align-items-center">
                    <div class="d-flex align-items-center px-3 py-1 mb-3 flex-grow-1" style="background-color: #def5e2; border-radius: 18px; min-width: 0;">
                        <input wire:model.lazy="search" type="text" name="table_search" class="form-control border-0 bg-transparent py-1"
                         placeholder="Search by file name" style="min-width: 0; width: 100%; height: 32px;">
                        <i class="fas text-dark fa-search fa-lg ml-2 pt-2" style="height: 32px;"></i>
                    </div>
                    <div class="ml-3 mb-3" style="flex-shrink: 0; background-color: #def5e2; border-radius: 18px; ">
                        <button type="button" class="mx-2 btn btn-link text-dark p-0" style="height: 32px;" 
                        wire:click="addFile()">
                            <span class="ml-2">Add File</span> <i class="fas fa-plus-circle text-success pl-2 pr-1" ></i> 
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
                                <th>Created By</th>
                                <th>Date Created</th>
                                <th>Modified By</th>
                                <th>Date Modified</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($files as $index => $item)
                            <tr>
                                <td>{{ $files->firstItem() + $index }}</td>
                                <td>{{ $item->file_name }}</td>
                                <td>
                                    @if($item->createdBy->profile_photo_path)
                                        <img class="img-circle img-sm mr-2" src="{{ asset('storage/' . $item->createdBy->profile_photo_path) }}" alt="User Image">
                                    @else
                                        <img class="img-circle img-sm mr-2" src="https://placehold.co/40x40/838584/ffffff?text={{ $this->getInitials($item->createdBy->name) }}" alt="User Image">
                                    @endif
                                    {{ $item->createdBy->name }}
                                </td>
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
                                <td>
                                    <button class="btn btn-link text-success px-2" wire:click="downloadFile({{ $item->id }})"><i class="fas fa-download"></i></button>
                                    @if(auth()->user()->role == 1) 
                                        <button class="btn btn-link text-info px-2" wire:click="viewFile({{ $item->id }})"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-link text-primary px-2" wire:click="editFile({{ $item->id }})"><i class="fas fa-pencil-alt"></i></button>
                                        <button class="btn btn-link text-danger px-2" wire:click="confirmDelete({{ $item->id }})"><i class="fas fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-danger">No files found.</td>
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