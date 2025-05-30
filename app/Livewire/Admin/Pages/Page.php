<?php

namespace App\Livewire\Admin\Pages;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use App\Models\Page as PageModel;

class Page extends Component
{
    public function addPage()
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'staff') {
                $this->dispatch('failed', [
                    'message' => 'Hanya staff yang dapat menambahkan halaman baru.',
                ]);
                return;
            }
            $userId = $user->id;
            $count = PageModel::where('user_id', $userId)->count();
            $newPageName = 'Halaman Baru ' . ($count + 1);
            $page = PageModel::create([
                'user_id' => $userId,
                'name' => $newPageName,
                // 'data' => null,
                // 'path' => null,
                // 'release' => null,
                // 'keywords' => null,
                // 'description' => null,
            ]);
            Redirect::route('page-edit', ['id' => $page->id]);
            return;
        } catch (\Throwable $th) {
            Log::error('Error adding new page: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat menambahkan halaman baru.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.pages.page', [
            'pages' => PageModel::with('user')->get(),
        ]);
    }
}
