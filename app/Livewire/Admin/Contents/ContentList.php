<?php

namespace App\Livewire\Admin\Contents;

use App\Models\Category;
use App\Models\Content;
use App\Models\ContentType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;

class ContentList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'published_at';
    public $sortDirection = 'desc';

    public $filterType = '';
    public $filterCategory = '';
    public $filterStatus = '';

    public $confirmingContentId = null;

    protected $updatesQueryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => ''],
        'filterCategory' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'sortField' => ['except' => 'published_at'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingContentId = $id;
        $this->dispatch('openDeleteModal');
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed()
    {
        $content = Content::find($this->confirmingContentId);

        if ($content) {
            if ($content->image && Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }

            $content->delete();
            $this->dispatch('contentDeleted');
        }

        $this->confirmingContentId = null;
        $this->dispatch('closeDeleteModal');
        // Tidak perlu dispatch refreshContentList karena Livewire otomatis rerender
    }

    public function render()
    {
        $contents = Content::with(['user', 'type', 'categories'])
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->filterType, fn ($q) => $q->where('type_id', $this->filterType))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterCategory, function ($q) {
                $q->whereHas('categories', fn ($query) => $query->where('id', $this->filterCategory));
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(5);

        return view('livewire.admin.contents.content-list', [
            'contents' => $contents,
            'types' => ContentType::all(),
            'categories' => Category::all(),
        ]);
    }
}
