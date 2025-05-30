<?php

namespace App\Livewire\Admin\Contents;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\{Content, Category, ContentType};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ContentEdit extends Component
{
    use WithFileUploads;

    public $content;
    public $title, $slug, $description, $is_berita, $content_types_id, $status;
    public $selected_categories = [], $image;
    public $showCategoryDropdown = false;

public function mount($id)
{
    $this->content = Content::findOrFail($id);

    $this->title = $this->content->title;
    $this->slug = $this->content->slug;
    $this->description = $this->content->description;
    $this->is_berita = $this->content->is_berita;
    $this->content_types_id = $this->content->content_types_id;
    $this->status = $this->content->status;
    $this->selected_categories = $this->content->categories()->pluck('id')->toArray();
}

    public function updatedTitle()
    {
        $this->slug = Str::slug($this->title);
    }

    public function toggleCategoryDropdown()
    {
        $this->showCategoryDropdown = !$this->showCategoryDropdown;
    }

    public function toggleCategory($id)
    {
        if (in_array($id, $this->selected_categories)) {
            $this->selected_categories = array_diff($this->selected_categories, [$id]);
        } else {
            $this->selected_categories[] = $id;
        }
    }

    public function removeCategory($id)
    {
        $this->selected_categories = array_diff($this->selected_categories, [$id]);
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:contents,slug,' . $this->content->id,
            'description' => 'nullable|string',
            'content_types_id' => 'required|exists:content_types,id',
            'status' => 'required|in:published,unpublished',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($this->image) {
            // Hapus gambar lama jika ada
            if ($this->content->image && Storage::disk('public')->exists($this->content->image)) {
                Storage::disk('public')->delete($this->content->image);
            }
            $this->content->image = $this->image->store('contents', 'public');
        }

        $this->content->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_berita' => $this->is_berita,
            'content_types_id' => $this->content_types_id,
            'status' => $this->status,
            'image' => $this->content->image ?? null,
        ]);

        $this->content->categories()->sync($this->selected_categories);

        $this->dispatch('contentSaved');
    }

    public function render()
    {
        return view('livewire.admin.contents.content-edit', [
            'categories' => Category::all(),
            'contentTypes' => ContentType::all(),
        ]);
    }
}
