<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Traits\LogsActivity;

class FileManagement extends Component
{
    use WithFileUploads, WithPagination, LogsActivity;

    public $search = '';
    public $sortname = 'file_name';
    public $sortDirection = 'asc';

    public $file, $fileName, $existingFilePath, $selectedFile;
    public $isEditMode = false;
    public $decryptionKey;

    protected $listeners = ['deleteConfirmed' => 'deleteFile'];

    public function render()
    {
        //Storage::disk('google')->write('test2.txt', 'Hello World!');
        $files = File::with(['createdBy', 'modifiedBy'])
        ->where('file_name', 'like', '%' . $this->search . '%')
        ->orderBy($this->sortname, $this->sortDirection)
        ->paginate(10);        

        return view('livewire.admin.file-management', ['files' => $files]);
    }

    public function srt($name)
    {
        if ($this->sortname === $name) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortname = $name;
    }

    public function addFile()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->dispatch('show-file-modal');
    }

    public function editFile($id)
    {
        $this->selectedFile = File::find($id);
        $this->fileName = $this->selectedFile->file_name;
        $this->existingFilePath = $this->selectedFile->file_path;
        $this->isEditMode = true;
        $this->dispatch('show-file-modal');
    }

    
    public function saveFile()
    {
        $this->validate([
            'fileName' => 'required|string|max:255',
            'file' => $this->isEditMode ? 'nullable|file|mimes:xlsx,xls,pdf,pptx,ppt,docx|max:10240' : 'required|file|mimes:xlsx,xls,pdf,pptx,ppt,docx|max:10240',
        ]);

        $updateData = [
            'file_name' => $this->fileName,
            'modified_by' => auth()->id(),
        ];

        
        if ($this->file) {
            $encryptionKey = Str::random(32);
            //$encryptionKey = "";
            
            $filePath = $this->file->store('files');
            $encryptedContent = Crypt::encrypt(file_get_contents($this->file->getRealPath()), $encryptionKey);
            //Storage::put($filePath, $encryptedContent);
            Storage::disk('google')->write($filePath, $encryptedContent);
            
            $updateData['original_filename'] = $this->file->getClientOriginalName(); 
            $updateData['file_path'] = $filePath;
            $updateData['file_type'] = $this->file->getClientMimeType();
            $updateData['size'] = $this->file->getSize();
            $updateData['encryption_key'] = $encryptionKey;
        }

        $this->logActivity("User '".auth()->user()->name."' ".($this->isEditMode ? "updated" : "uploaded")." file '{$this->fileName}'");
        
        File::updateOrCreate(['id' => $this->selectedFile ? $this->selectedFile->id : null],
            array_merge($updateData, $this->isEditMode ? [] : ['created_by' => auth()->id()])
        );

        $this->dispatch('hide-file-modal');
        $this->resetInputFields();
    }
    

    public function downloadFile($id)
    {
        $this->selectedFile = File::find($id);

        if (auth()->user()->role == 1) { 
            return $this->decryptAndDownload(true);
        } else { 
            $this->decryptionKey = '';
            $this->dispatch('show-download-modal');
        }
    }

    public function decryptAndDownload($isAdmin = false)
    {
        if (!$isAdmin) {
            $this->validate(['decryptionKey' => 'required|string']);
        }


        if (!$isAdmin && $this->decryptionKey !== $this->selectedFile->encryption_key) {
            $this->addError('decryptionKey', 'Invalid decryption key.');
            return;
        }

        try {
            $key = $isAdmin ? $this->selectedFile->encryption_key : $this->decryptionKey;
            //$decryptedContent = Crypt::decrypt(Storage::get($this->selectedFile->file_path), $key);
            $decryptedContent = Crypt::decrypt(Storage::disk('google')->read($this->selectedFile->file_path), $key);
            $headers = ['Content-Type' => $this->selectedFile->file_type];
            
            $this->logActivity("User '".auth()->user()->name."' downloaded file '{$this->selectedFile->file_name}'");

            return response()->streamDownload(function () use ($decryptedContent) {
                echo $decryptedContent;
            }, $this->selectedFile->original_filename, $headers);

        } catch (DecryptException $e) {
            $this->addError('decryptionKey', 'Invalid decryption key.');
        }
    }

    public function viewFile($id)
    {
        $this->selectedFile = File::find($id);
        $this->dispatch('show-view-file-modal');
    }

    public function confirmDelete($id)
    {
        $this->selectedFile = File::find($id);
        $this->dispatch('show-delete-confirmation');
    }

    public function deleteFile()
    {
        //Storage::delete($this->selectedFile->file_path);
        Storage::disk('google')->delete($this->selectedFile->file_path);
        $this->selectedFile->delete();
    }

    private function resetInputFields()
    {
        $this->file = null;
        $this->fileName = '';
        $this->existingFilePath = '';
        $this->selectedFile = null;
        $this->isEditMode = false;
        $this->decryptionKey = '';
    }

    public function getInitials($name)
    {
        $words = explode(' ', trim($name));
        $initials = '';
        if (count($words) > 1) {
            $initials = strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        } elseif (!empty($words[0])) {
            $initials = strtoupper(substr($words[0], 0, 2));
        } else {
            $initials = '??';
        }
        return $initials;
    }
}
