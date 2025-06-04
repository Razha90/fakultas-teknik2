<?php

namespace App\Livewire\Admin\Category;

use App\Models\Categories;
use App\Models\Categories_Translation;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CategoryIndex extends Component
{
    public $menus;
    public function mount()
    {
        $this->loadMenus();
    }

    private function loadMenus()
    {
        try {
            $menus = Categories::with('translations')->get();
            $this->menus = $menus->toArray();
        } catch (\Throwable $th) {
            Log::error('Error loading menus: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat memuat menu: ' . $th->getMessage(),
            ]);
            $this->menus = [];
        }
    }

    public function categoryAdd($nameEn, $nameId)
    {
        try {
            if (empty($nameEn) || empty($nameId)) {
                $this->dispatch('failed', [
                    'message' => 'Nama kategori dalam bahasa Indonesia dan Inggris wajib diisi.',
                ]);
                return;
            }

            $existingEn = Categories_Translation::where('locale', 'en')->where('name', $nameEn)->first();

            $existingId = Categories_Translation::where('locale', 'id')->where('name', $nameId)->first();

            if ($existingEn || $existingId) {
                $this->dispatch('failed', [
                    'message' => 'Kategori dengan nama yang sama sudah ada.',
                ]);
                return;
            }
            $category = Categories::create();
            $category->translations()->create([
                'locale' => 'en',
                'name' => $nameEn,
            ]);
            $category->translations()->create([
                'locale' => 'id',
                'name' => $nameId,
            ]);

            $this->loadMenus();
            $this->dispatch('success', [
                'message' => 'Kategori berhasil ditambahkan.',
            ]);
        } catch (\Throwable $th) {
            Log::error('Error adding menu: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat menambahkan kategori baru: ' . $th->getMessage(),
            ]);
        }
    }

    public function categoryUpdate($id, $nameEn, $nameId)
    {
        try {
            if (empty($nameEn) || empty($nameId)) {
                $this->dispatch('failed', [
                    'message' => 'Nama kategori dalam bahasa Indonesia dan Inggris wajib diisi.',
                ]);
                return;
            }

            $duplicateEN = Categories_Translation::where('locale', 'en')
                ->where('name', $nameEn)
                ->where('category_id', '!=', $id)
                ->exists();


            if ($duplicateEN) {
                $this->dispatch('failed', [
                    'message' => 'Kategori dengan nama yang sama sudah ada.',
                ]);
                return;
            }

            $duplicateID = Categories_Translation::where('locale', 'id')
                ->where('name', $nameId)
                ->where('category_id', '!=', $id)
                ->exists();

            if ($duplicateID) {
                $this->dispatch('failed', [
                    'message' => 'Kategori dengan nama yang sama sudah ada.',
                ]);
                return;
            }


            $data = Categories::find($id);
            if ($data) {
                $data->translations()->updateOrCreate(['locale' => 'en'], ['name' => $nameEn]);
                $data->translations()->updateOrCreate(['locale' => 'id'], ['name' => $nameId]);
                $this->loadMenus();
                $this->dispatch('success', [
                    'message' => 'Kategori berhasil diperbarui.',
                ]);
            } else {
                $this->dispatch('failed', [
                    'message' => 'Kategori tidak ditemukan.',
                ]);
            }
        } catch (\Throwable $th) {
            Log::error('Error adding menu: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat menambahkan kategori baru: ' . $th->getMessage(),
            ]);
        }
    }

    public function deleteCategory($id)
    {
        try {
            $menu = Categories::find($id);
            if ($menu) {
                $menu->delete();
                $this->loadMenus();
                $this->dispatch('success', [
                    'message' => 'Menu berhasil dihapus.',
                ]);
            } else {
                $this->dispatch('failed', [
                    'message' => 'Menu tidak ditemukan.',
                ]);
            }
        } catch (\Throwable $th) {
            Log::error('Error deleting menu: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat menghapus menu: ' . $th->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.category.category-index');
    }
}
