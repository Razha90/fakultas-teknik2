<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class NewsIndex extends Component
{
    public $data;
    public function mount()
    {
        $this->loadNews();
    }

    public function loadNews()
    {
        try {
            // $locale = app()->getLocale();
            // $news = News::with('translations')->get();
            $locale = app()->getLocale();
            $news = News::with([
                'translations' => function ($query) use ($locale) {
                    $query->where('locale', $locale);
                },
            ])->get();

            $this->data = $news->toArray();
        } catch (\Throwable $th) {
            Log::error('Error fetching news data: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat mengambil data berita.',
            ]);
        }
    }

    public function addNews()
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'staff') {
                $this->dispatch('failed', [
                    'message' => 'Hanya staff yang dapat menambahkan berita baru.',
                ]);
                return;
            }
            $userId = $user->id;
            $count = News::where('user_id', $userId)->count();
            $newPageName = __('news.news') . ($count + 1);
            $locale = app()->getLocale();
            $news = News::create([
                'user_id' => $userId,
            ]);
            $news->translations()->create([
                'locale' => $locale,
                'title' => $newPageName,
            ]);
            Redirect::route('news-edit', ['id' => $news->id]);
            return;
        } catch (\Throwable $th) {
            Log::error('Error adding new page: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat menambahkan Berita baru.',
            ]);
        }
    }

    public function deleteNews($id)
    {
        try {
            $page = News::with('files')->findOrFail($id);
            foreach ($page->files as $file) {
                if (Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);
                }
            }
            $page->files()->delete();
            $page->delete();
            $this->loadNews();
            $this->dispatch('success', [
                'message' => 'Halaman berhasil dihapus.',
            ]);
        } catch (\Throwable $th) {
            Log::error('Error deleting news: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat menghapus berita.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.news.news-index');
    }
}
