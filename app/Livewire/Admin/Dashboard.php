<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Content;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    #[Title('Dashboard Admin')]
    public function render()
    {
        $viewData = Category::select('categories_raffi.name as category')->leftJoin('category_content', 'categories_raffi.id', '=', 'category_content.category_id')->leftJoin('contents', 'category_content.content_id', '=', 'contents.id')->selectRaw('categories_raffi.name as category, COALESCE(SUM(contents.views), 0) as total_views')->groupBy('categories_raffi.name')->get();

        return view('livewire.admin.dashboard', [
            'departments' => Department::count(),
            'categories' => Category::count(),
            'contents' => Content::count(),
            'publishedContents' => Content::where('status', 'published')->count(),
            'unpublishedContents' => Content::where('status', 'unpublished')->count(),
            'users' => User::count(),
            'viewData' => $viewData,
        ]);
    }
}
