<?php

namespace App\Livewire\Admin\ContentType;

use Livewire\Component;
use App\Models\ContentType;


class ContentTypeList extends Component
{

    public $contentTypeIdBeingDeleted;
    public $contentTypeIdBeingEdited;
    public $name;
    public $editKey;

    protected $listeners = ['contentTypeCreated' => '$refresh'];

    public function confirmDelete($id)
    {
        $this->contentTypeIdBeingDeleted = $id;
        $this->dispatch('openDeleteModal');
    }


    public function delete()
    {
        $department = ContentType::find($this->contentTypeIdBeingDeleted);

        if ($department) {
            $department->delete();
        }

        $this->contentTypeIdBeingDeleted = null;

        $this->dispatch('closeDeleteModal');
        $this->dispatch('showSDeleteAlert');
    }


    public function editContentType($id)
    {
        $categorys = ContentType::findOrFail($id);

        $this->contentTypeIdBeingEdited = $categorys->id;
        $this->name = $categorys->name;
        $this->editKey = uniqid();

        $this->dispatch('showEditModal');
    }

    public function updateContentType()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $contentType = ContentType::find($this->contentTypeIdBeingEdited);

        if ($contentType) {
            $contentType->update([
                'name' => $this->name,
            ]);
        }

        $this->dispatch('closeEditModal');
        $this->dispatch('showEditAlert');
        $this->reset(['name', 'contentTypeIdBeingEdited']);
    }

    public function render()
    {
        $content_types = ContentType::all();
        return view('livewire.admin.content-type.content-type-list',[
            'contentType' => $content_types
        ]);
    }
}
