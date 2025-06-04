<?php

namespace App\Livewire\Admin\Menu;

use App\Http\Controllers\Permalink;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\Menu as MenuModel;
use App\Models\Page;

class MenuIndex extends Component
{
    public $menus;
    public function menusPageAdd($name, $path)
    {
        try {
            if (empty($name)) {
                $this->dispatch('failed', [
                    'message' => 'Nama menu tidak boleh kosong.',
                ]);
                return;
            }

            if (empty($path)) {
                $this->dispatch('failed', [
                    'message' => 'Path menu tidak boleh kosong.',
                ]);
                return;
            }
            $linked = new Permalink();
            $formattedPath = $linked->formatLink($path);
            if ($formattedPath === false) {
                $this->dispatch('failed', [
                    'message' => 'Path menu mengandung karakter yang tidak valid.',
                ]);
                return;
            }
            if (!$linked->checkLink($formattedPath)) {
                $this->dispatch('failed', [
                    'message' => 'Path menu sudah digunakan oleh halaman atau menu lain.',
                ]);
                return;
            }
            $maxPosition = MenuModel::max('position') ?? 0;
            MenuModel::create([
                'name' => $name,
                'position' => $maxPosition + 1,
                'user_id' => auth()->user()->id,
                'path' => $formattedPath,
            ]);
            $this->loadMenus();
        } catch (\Throwable $th) {
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat menambahkan menu baru: ' . $th->getMessage(),
            ]);
            Log::error('Error adding menu: ' . $th->getMessage());
        }
    }

    public function mount()
    {
        $this->loadMenus();
    }

    private function loadMenus()
    {
        try {
            $menus = MenuModel::orderBy('position', 'asc')->get();
            $this->menus = $menus->toArray();
        } catch (\Throwable $th) {
            Log::error('Error loading menus: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat memuat menu: ' . $th->getMessage(),
            ]);
            $this->menus = [];
        }
    }

    public function deleteMenu($id)
    {
        try {
            $menu = MenuModel::with('pages')->find($id);
            if ($menu) {
                foreach ($menu->pages as $page) {
                    $page->menu_id = null;
                    $page->save();
                }
                $menu->delete();
                $this->reorderMenuPositions();
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

    private function reorderMenuPositions()
    {
        try {
            $menus = MenuModel::orderBy('position')->get();
            $newPosition = 1;
            foreach ($menus as $menu) {
                $menu->position = $newPosition;
                $menu->save();
                $newPosition++;
            }
        } catch (\Throwable $th) {
            Log::error('Error reordering menu positions: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat mengurutkan posisi menu: ' . $th->getMessage(),
            ]);
        }
    }

    public function updateMenu($id, $name, $path)
    {
        try {
            if (empty($name)) {
                $this->dispatch('failed', [
                    'message' => 'Nama menu tidak boleh kosong.',
                ]);
                return;
            }

            if (empty($path)) {
                $this->dispatch('failed', [
                    'message' => 'Path menu tidak boleh kosong.',
                ]);
                return;
            }
            $linked = new Permalink();
            $formattedPath = $linked->formatLink($path);
            if ($formattedPath === false) {
                $this->dispatch('failed', [
                    'message' => 'Path menu mengandung karakter yang tidak valid.',
                ]);
                return;
            }
            if (!$linked->checkLink($formattedPath)) {
                $this->dispatch('failed', [
                    'message' => 'Path menu sudah digunakan oleh halaman atau menu lain.',
                ]);
                return;
            }
            $menu = MenuModel::find($id);
            if ($menu) {
                $menu->name = $name;
                $menu->path = $formattedPath;
                $menu->save();
                $this->loadMenus();
                $this->dispatch('success', [
                    'message' => 'Menu berhasil diperbarui.',
                ]);
            } else {
                $this->dispatch('failed', [
                    'message' => 'Menu tidak ditemukan.',
                ]);
            }
        } catch (\Throwable $th) {
            Log::error('Error updating menu: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat memperbarui menu: ' . $th->getMessage(),
            ]);
        }
    }

    public function upMenu($id)
    {
        try {
            $menu = MenuModel::find($id);
            if (!$menu) {
                $this->dispatch('failed', ['message' => 'Menu tidak ditemukan.']);
                return;
            }
            if ($menu->position <= 1) {
                $this->dispatch('failed', ['message' => 'Menu sudah berada di posisi paling atas.']);
                return;
            }
            $upperMenu = MenuModel::where('position', $menu->position - 1)->first();

            if (!$upperMenu) {
                $this->dispatch('failed', ['message' => 'Menu atas tidak ditemukan.']);
                return;
            }
            $tempPosition = $menu->position;
            $menu->position = $upperMenu->position;
            $upperMenu->position = $tempPosition;
            $menu->save();
            $upperMenu->save();
            $this->dispatch('success', ['message' => 'Menu berhasil dipindahkan ke atas.']);
            $this->loadMenus();
        } catch (\Throwable $th) {
            Log::error('Error moving menu up: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat memindahkan menu ke atas: ' . $th->getMessage(),
            ]);
        }
    }

    public function downMenu($id)
    {
        try {
            $menu = MenuModel::find($id);
            if (!$menu) {
                $this->dispatch('failed', ['message' => 'Menu tidak ditemukan.']);
                return;
            }
            $maxPosition = MenuModel::max('position');
            if ($menu->position >= $maxPosition) {
                $this->dispatch('failed', ['message' => 'Menu sudah berada di posisi paling bawah.']);
                return;
            }
            $lowerMenu = MenuModel::where('position', $menu->position + 1)->first();
            if (!$lowerMenu) {
                $this->dispatch('failed', ['message' => 'Menu bawah tidak ditemukan.']);
                return;
            }
            $tempPosition = $menu->position;
            $menu->position = $lowerMenu->position;
            $lowerMenu->position = $tempPosition;
            $menu->save();
            $lowerMenu->save();

            $this->dispatch('success', ['message' => 'Menu berhasil dipindahkan ke bawah.']);
            $this->loadMenus();
        } catch (\Throwable $th) {
            Log::error('Error moving menu down: ' . $th->getMessage());
            $this->dispatch('failed', [
                'message' => 'Terjadi kesalahan saat memindahkan menu ke bawah: ' . $th->getMessage(),
            ]);
        }
    }

    public function toggleMenuActive($id)
{
    try {
        $menu = MenuModel::find($id);

        if (!$menu) {
            $this->dispatch('failed', ['message' => 'Menu tidak ditemukan.']);
            return;
        }

        // Toggle nilai isActive
        $menu->isActive = !$menu->isActive;
        $menu->save();

        $status = $menu->isActive ? 'diaktifkan' : 'dinonaktifkan';

        $this->dispatch('success', [
            'message' => "Menu berhasil $status."
        ]);

        $this->loadMenus(); // Jika kamu punya fungsi ini untuk me-refresh daftar menu

    } catch (\Throwable $th) {
        Log::error('Error toggling menu isActive: ' . $th->getMessage());
        $this->dispatch('failed', [
            'message' => 'Terjadi kesalahan saat mengubah status menu: ' . $th->getMessage(),
        ]);
    }
}


    public function render()
    {
        return view('livewire.admin.menu.menu-index');
    }
}
