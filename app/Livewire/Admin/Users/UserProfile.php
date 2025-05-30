<?php

namespace App\Livewire\Admin\Users;

use App\Models\Content;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;
use Illuminate\Validation\Rule;

class UserProfile extends Component
{
    use WithFileUploads;
    public string $activeTab = 'timeline';
    public $username, $fullname, $email, $department_id, $position, $phone_number, $password;
    public $image, $newImage;

    protected function rules()
    {
        return [
            'username' => ['required', 'string', Rule::unique('users', 'username')->ignore(Auth::id())],
            'fullname' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore(Auth::id())],
            'department_id' => 'required|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'newImage' => 'nullable|image|max:1024', // max 1MB
        ];
    }

    protected function messages()
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'department_id.required' => 'Pilih program studi.',
            'department_id.exists' => 'Program studi tidak valid.',
            'password.min' => 'Password minimal 6 karakter.',
            'newImage.image' => 'File harus berupa gambar.',
            'newImage.max' => 'Ukuran gambar maksimal 1MB.',
        ];
    }

    public function mount()
    {
        $user = Auth::user();
        $this->username = $user->username;
        $this->fullname = $user->fullname;
        $this->email = $user->email;
        $this->department_id = $user->id_department;
        $this->position = $user->position;
        $this->phone_number = $user->phone_number;
        $this->image = $user->image;

    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }




    public function updateProfile()
    {
        $this->validate();

        $user = Auth::user();
        // \Log::info('Selected Department ID: ' . $this->department_id);
        // dd($this->department_id);
        $user->update([
            'username' => $this->username,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'id_department' => $this->department_id,
            'position' => $this->position,
            'phone_number' => $this->phone_number,
            'password' => $this->password ? Hash::make($this->password) : $user->password,
        ]);

        if ($this->newImage) {
            $filename = $this->newImage->store('profile-photos', 'public');
            $user->update(['image' => $filename]);
            $this->image = $filename;
        }

        $this->dispatch('updateProfilSuccess');
    }



    public function render()
    {
        $user = Auth::user();

        // Ambil konten berdasarkan user login
        $contents = Content::with('type')
            ->where('users_id', $user->id)
            ->latest()
            ->get();

        return view('livewire.admin.users.user-profile', [
            'departments' => Department::all(),
            'contents' => $contents, // Kirim ke view
        ]);
    }
}
