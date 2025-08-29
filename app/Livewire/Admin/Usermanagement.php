<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Traits\LogsActivity;

class Usermanagement extends Component
{
    use WithPagination, WithFileUploads, LogsActivity;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'destroy'];

    public $perpage = 10;
    public $search = "";
    public $sortField = 'name', $sortname = 'Name';
    public $sortAsc = true;
    public $role = 0, $rolename = 'Employee';

    public $isEditMode = false;
    public $user_id;
    public $name, $email, $password, $password_confirmation, $user_role;
    public $photo;
    public $existing_photo_path;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->user_id)],
            'user_role' => 'required|integer|in:0,1',
            'photo' => 'nullable|image|mimes:jpg,png|max:2048', // Max 2MB
            'password' => $this->isEditMode ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
        ];
    }

    // Custom validation messages
    protected $messages = [
        'user_role.required' => 'The role field is required.',
        'photo.image' => 'The file must be an image.',
        'photo.mimes' => 'Only JPG and PNG images are allowed.',
    ];

    /**
     * NEW: Add this lifecycle hook for real-time validation.
     * This method runs automatically when the 'photo' property is updated.
     */
    public function updatedPhoto()
    {
        $this->validate(['photo' => 'nullable|image|mimes:jpg,png|max:2048']);
    }

    private function resetInputFields()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'user_role', 'photo', 'user_id', 'isEditMode', 'existing_photo_path']);
        $this->resetErrorBag();
    }

    public function addUser()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->dispatch('show-user-modal');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->user_role = $user->role;
        $this->existing_photo_path = $user->profile_photo_path;
        $this->photo = null;
        $this->isEditMode = true;
        $this->resetErrorBag();
        $this->dispatch('show-user-modal');
    }

    public function saveUser()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->user_role,
        ];

        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->photo) {
            if ($this->user_id && $this->existing_photo_path) {
                 Storage::disk('public')->delete($this->existing_photo_path);
            }
        }

        if ($this->isEditMode) {
            $user = User::findOrFail($this->user_id);
            $user->update($userData);

            if ($this->photo) {
                $imageName = 'pic_' . $user->id . '.' . $this->photo->getClientOriginalExtension();
                $path = $this->photo->storeAs('photos', $imageName, 'public');
                $user->update(['profile_photo_path' => $path]);
                
            }
            $this->logActivity("User '{$user->name}' profile updated");
            $this->dispatch('hide-user-modal');
            $this->dispatch('show-alert', type: 'success', message: 'User updated successfully!');

        } else {
            $user = User::create($userData);

            if ($this->photo) {
                $imageName = 'pic_' . $user->id . '.' . $this->photo->getClientOriginalExtension();
                $path = $this->photo->storeAs('photos', $imageName, 'public');
                $user->update(['profile_photo_path' => $path]);
            }
            $this->logActivity("User '{$user->name}' has been created");
            $this->dispatch('hide-user-modal');
            $this->dispatch('show-alert', type: 'success', message: 'User created successfully!');
        }

        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $this->user_id = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function destroy()
    {
        $user = User::findOrFail($this->user_id);
        $user->update(['is_deleted' => 1]);
        
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $this->logActivity("User '{$user->name}' deleted");
        }

        $this->dispatch('show-alert', type: 'success', message: 'User deleted successfully!');
        $this->resetInputFields();
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

    public function srt($field)
    {
        $this->sortField = $field;
        $this->sortname = match ($field) {
            'name' => 'Name',
            'created_at' => 'Date Created',
            'role' => 'Role',
            default => $field,
        };
    }

    public function accountrole($field)
    {
        $this->role = $field;
        $this->rolename = match ($field) {
            0 => 'Employee',
            1 => 'Admin',
            default => $field,
        };
    }

    public function pages($pages)
    {
        $this->perpage = $pages;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $profiles = User::where('is_deleted', 0)
            ->where('role', $this->role)
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perpage);

        return view('livewire.admin.usermanagement', [
            'profiles' => $profiles
        ]);
    }
}
