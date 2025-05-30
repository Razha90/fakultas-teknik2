<?php

namespace App\Livewire\Admin\Category;

use App\Models\Category;
use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryList extends Component
{

    public $categoryIdBeingDeleted;
    public $categoryIdBeingEdited;
    public $name;
    public $editKey;


    protected $listeners = ['categoryCreated' => '$refresh'];


    public function confirmDelete($id)
    {
        $this->categoryIdBeingDeleted = $id;
        $this->dispatch('openDeleteModal');
    }

    public function closeEditModal()
{
    $this->reset(['name', 'categoryIdBeingEdited']);
}

    public function delete()
    {
        $department = Category::find($this->categoryIdBeingDeleted);

        if ($department) {
            $department->delete();
        }

        $this->categoryIdBeingDeleted = null;

        $this->dispatch('closeDeleteModal');
        $this->dispatch('showSDeleteAlert');
    }


    public function editCategory($id)
    {
        $category = Category::findOrFail($id);

        $this->categoryIdBeingEdited = $category->id;
        $this->name = $category->name;

        $this->editKey = uniqid(); // <<=== ini penting

        $this->dispatch('showEditModal');
    }


    public function updateCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $categorys = Category::find($this->categoryIdBeingEdited);

        if ($categorys) {
            $categorys->update([
                'name' => $this->name,
            ]);
        }

        $this->dispatch('closeEditModal');
        $this->dispatch('showEditAlert');
        $this->reset(['name', 'categoryIdBeingEdited']);
    }


    public function render()
    {
        $categorys = Category::all();
        return view('livewire.admin.category.category-list', [
            'categorys' => $categorys
        ]);
    }
}
