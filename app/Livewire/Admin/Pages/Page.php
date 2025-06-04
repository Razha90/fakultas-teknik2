<?php

namespace App\Livewire\Admin\Pages;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use App\Models\Page as PageModel;

class Page extends Component
{
    public $data;
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

    public function mount() {
        $this->getData();
    }

    public function getData() {
        try {
            $pages = PageModel::with('menu')->get();
            $this->data = $pages->toArray();
        } catch (\Throwable $th) {
            Log::error('Error fetching pages data: ' . $th->getMessage());
            $this->data = [];
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat mengambil data halaman.',
            ]);
        }
    }

    public function deletePage($id)
    {
        try {
            $page = PageModel::with('files')->findOrFail($id);
            foreach ($page->files as $file) {
                if (Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);
                }
            }
            $page->files()->delete();
            $page->delete();
            $this->getData();
            $this->dispatch('success', [
                'message' => 'Halaman berhasil dihapus.',
            ]);
        } catch (\Throwable $th) {
            Log::error('Error deleting page: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat menghapus halaman.',
            ]);
        }
    }
    public function render()
    {
        return view('livewire.admin.pages.page');
    }
}
