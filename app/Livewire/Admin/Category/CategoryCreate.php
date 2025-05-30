<?php

namespace App\Livewire\Admin\Category;

use App\Models\Category;
use Livewire\Component;

class CategoryCreate extends Component
{

    public $name;

    protected $rules = [
        'name' => 'required|string|max:255'
    ];

    public function save() {

        try{
            $this->validate();
            $existing = Category::where('name', $this->name)->first();
            if ($existing) {
                // Dispatch browser event untuk alert biasa
                $this->dispatch('dataDuplicate');
                return; // Stop proses
            }

            Category::create([
                    'name' => $this->name,
                    ]);

            $this->reset('name');

            $this->dispatch('closeModal');
            $this->dispatch('categoryCreated');
            $this->dispatch('dataCreated');

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
        return view('livewire.admin.category.category-create');
    }
}
