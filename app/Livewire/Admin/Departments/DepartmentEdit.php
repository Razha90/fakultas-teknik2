<?php

namespace App\Http\Livewire\Admin\Departments;

use Livewire\Component;
use App\Models\Department;

class DepartmentEdit extends Component
{
    public $departmentId;
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    protected $listeners = ['openEditModal'];

    public function openEditModal($id)
    {
        $department = Department::findOrFail($id);
        $this->departmentId = $department->id;
        $this->name = $department->name;

        // Kirim event untuk buka modal
        $this->dispatch('openEditModal');
    }

    public function update()
    {
        $this->validate();

        $department = Department::findOrFail($this->departmentId);
        $department->update([
            'name' => $this->name,
        ]);

        // Reset form
        $this->reset(['departmentId', 'name']);

        // Tutup modal
        $this->dispatch('closeEditModal');

        // Kirim pesan sukses
        $this->dispatch('showToast', ['message' => 'Department berhasil diupdate!']);

        // Emit event untuk refresh list jika ada component list terpisah
        $this->dispatch('departmentUpdated');
    }

    public function render()
    {
        return view('livewire.admin.departments.department-edit');
    }
}
