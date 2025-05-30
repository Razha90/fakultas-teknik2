<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Str;
class UserCreate extends Component
{

    use WithFileUploads;

    public $username, $fullname, $email, $role, $id_department, $position, $phone_number, $image, $password, $password_confirmation;
    public $departments = [];
    public $usernameAvailable = null;

    public function mount()
    {
        $this->departments = Department::all();
    }

    public function updatedUsername($value)
    {
        $exists = User::where('username', $value)->exists();
        $this->usernameAvailable = !$exists;
    }

    public function save()
    {
        $this->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'fullname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,staff',
            'id_department' => 'required|exists:departments,id',
            'position' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'username.required' => 'Username wajib diisi!',
            'fullname.required' => 'Nama Lengkap wajib diisi!',
            'email.email' => 'Format email salah!',
            'password.confirmed' => 'Password dan Konfirmasi Password harus sama!',
            'image.image' => 'File harus gambar!',
            'image.mimes' => 'Gambar harus format jpg, jpeg, atau png!',
            'image.max' => 'Maksimal ukuran gambar 2MB!',
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('users', 'public');
        }

        User::create([
            'id' => Str::uuid(),
            'username' => $this->username,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'role' => $this->role,
            'id_department' => $this->id_department,
            'position' => $this->position,
            'phone_number' => $this->phone_number,
            'image' => $imagePath,
            'password' => bcrypt($this->password),
        ]);

        $this->dispatch('user-created');
        $this->reset();

        session()->flash('success', 'User created successfully!');

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.user-create');
    }
}
