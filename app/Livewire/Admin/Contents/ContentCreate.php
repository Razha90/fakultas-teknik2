<?php

namespace App\Livewire\Admin\Contents;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\{Content, Category, ContentType};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ContentCreate extends Component
{
    use WithFileUploads;

    public $title, $slug, $description, $content_types_id, $selected_categories = [], $status = 'draft', $image;
    public $contentTypes = [], $categories = [];
    public bool $showCategoryDropdown = false;

    public function mount() {
        $this->contentTypes = ContentType::all();
        $this->categories = Category::all();
    }

    public function toggleCategoryDropdown()
    {
        $this->showCategoryDropdown = !$this->showCategoryDropdown;
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }


    protected function rules() {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:contents,slug',
            'description' => 'required|string',
            'content_types_id' => 'required|uuid|exists:content_types,id',
            'status' => 'required|in:unpublished,published',
            'image' => 'required|image|max:2048',
            'selected_categories' => 'required|array|min:1',
        ];
    }



    public function toggleCategory($id)
    {
        if (in_array($id, $this->selected_categories)) {
            $this->selected_categories = array_values(array_diff($this->selected_categories, [$id]));
        } else {
            $this->selected_categories[] = $id;
        }
    }

    public function removeCategory($id)
    {
        $this->selected_categories = array_values(array_diff($this->selected_categories, [$id]));
    }

    public function save() {
    $this->validate();

    $path = $this->image->store('contents', 'public');

    $content = Content::create([
        'id' => Str::uuid(),
        'title' => $this->title,
        'slug' => $this->slug,
        'description' => $this->description,
        'content_types_id' => $this->content_types_id,
        'categories_id' => $this->selected_categories[0] ?? null,
        'users_id' => Auth::id(),
        'status' => $this->status,
        'image' => $path,
        'views' => 0, // inisialisasi awal
        'published_at' => $this->status === 'published' ? now() : null,
    ]);
$content->categories()->sync($this->selected_categories);

    $this->dispatch('contentSaved');
}

    public function render()
    {
        return view('livewire.admin.contents.content-create');
    }
}
