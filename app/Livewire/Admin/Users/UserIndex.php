<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class UserIndex extends Component
{
    public $userContents = [];
    public $confirmingUserDeletion = false;
    public $userIdBeingDeleted = null;



    public function confirmDelete($id)
    {
        $this->userIdBeingDeleted = $id;
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->userIdBeingDeleted);
        $user->delete();

        $this->confirmingUserDeletion = false;
        $this->userIdBeingDeleted = null;

        session()->flash('success', 'User berhasil dihapus.');
    }

    public function render()
    {

        $users = User::with('department')->get();

        return view('livewire.admin.users.user-index',[
            'users' => $users
        ]);
    }
}
