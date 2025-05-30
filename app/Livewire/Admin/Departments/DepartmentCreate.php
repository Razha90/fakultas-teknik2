<?php

namespace App\Livewire\Admin\Departments;

use App\Models\Department;
use Livewire\Component;

class DepartmentCreate extends Component
{
    public $name;


    protected $rules = [
        'name' => 'required|string|max:255',
    ];


    public function save()
{
    try {
        $this->validate();

        // Cek apakah data sudah ada
        $existing = Department::where('name', $this->name)->first();
        if ($existing) {
            // Dispatch browser event untuk alert biasa
            $this->dispatch('dataDuplicate');
            return; // Stop proses
        }

        Department::create([
            'name' => $this->name,
        ]);

        $this->reset('name');

        // $this->dispatch('departmentCreated');

        $this->dispatch('closeModal');
        $this->dispatch('dataCreated');
        $this->dispatch('refreshDepartments');
        $this->reset();


    } catch (\Illuminate\Validation\ValidationException $e) {
        $errorMessage = collect($e->validator->errors()->all())->first();

        $this->dispatch('showToast', [
            'type' => 'warning',
            'message' => $errorMessage
        ]);
    }
}
    public function render()
    {
        return view('livewire.admin.departments.department-create');
    }
}
