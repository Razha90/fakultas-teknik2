<?php

namespace App\Livewire\Admin\ContentType;

use Livewire\Component;
use App\Models\ContentType;

class ContentTypeCreate extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255'
    ];

    public function save() {

        try{
            $this->validate();
            $existing = ContentType::where('name', $this->name)->first();
            if ($existing) {
                // Dispatch browser event untuk alert biasa
                $this->dispatch('dataDuplicate');
                return; // Stop proses
            }

            ContentType::create([
                    'name' => $this->name,
                    ]);

            $this->reset('name');

            $this->dispatch('closeModal');
            $this->dispatch('contentTypeCreated');
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
        return view('livewire.admin.content-type.content-type-create');
    }
}
