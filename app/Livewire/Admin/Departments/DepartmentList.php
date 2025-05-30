<?php

namespace App\Livewire\Admin\Departments;

use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentList extends Component
{
    use WithPagination;
    public $departmentIdBeingDeleted;
    public $departmentIdBeingEdited;
    public $name;
    public $editKey;

    protected $listeners = ['refreshDepartments' => '$refresh'];

    public function confirmDelete($id)
    {
        $this->departmentIdBeingDeleted = $id;
        $this->dispatch('openDeleteModal');
    }

    public function delete()
    {
        $department = Department::find($this->departmentIdBeingDeleted);

        if ($department) {
            $department->delete();
        }

        $this->departmentIdBeingDeleted = null;

        $this->dispatch('closeDeleteModal');
        $this->dispatch('showSDeleteAlert');
    }

    public function editDepartment($id)
    {
        $this->reset(['name', 'departmentIdBeingEdited']);

        $department = Department::findOrFail($id);

        $this->departmentIdBeingEdited = $department->id;
        $this->name = $department->name;
        $this->editKey = uniqid();

        $this->dispatch('showEditModal');
    }


    public function updateDepartment()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $department = Department::find($this->departmentIdBeingEdited);

        if ($department) {
            $department->update([
                'name' => $this->name,
            ]);
        }

        $this->dispatch('closeEditModal');
        $this->dispatch('showEditAlert');

        $this->reset(['name', 'departmentIdBeingEdited']);
    }


    public function render()
    {
        $departments = Department::latest()->paginate(5);
        return view('livewire.admin.departments.department-list', [
            'departments' => $departments
        ]);
    }
}
