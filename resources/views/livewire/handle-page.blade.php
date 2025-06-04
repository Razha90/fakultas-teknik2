<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use App\Models\Page;

new #[Layout('components.layouts.home')] class extends Component {
    public $pathPage;
    public $data;
    public function mount($any)
    {
        $this->pathPage = $any;
        if (request()->method() !== 'GET') {
            abort(405);
        }
        $this->getPage();
    }

    public function getPage()
    {
        try {
            $data = Page::with('menu')
                ->get()
                ->filter(function ($page) {
                    $fullPath = trim($page->menu->path ?? '', '/') . '/' . trim($page->path, '/');
                    return $fullPath == $this->pathPage;
                })
                ->first();
            if (!$data) {
                abort(404);
            }
            $this->data = $data->toArray();
        } catch (\Exception $e) {
            // Log::error('Error fetching page: ' . $e->getMessage());
            abort(404);
        }
    }
}; ?>

<div x-data="dataHandlePage" x-init="init">
        @push('meta')
        <meta name="keywords" content="universitas, pendidikan, Medan, kampus, unimed, mahasiswa, akademik">
        <meta name="description"
            content="Website resmi Universitas Negeri Medan - informasi akademik, berita kampus, dan layanan mahasiswa.">
    @endpush
    <div class="h-[100px]"></div>
    <div x-data="{
        htmlContent: '',
        loadHtml() {
            fetch(domain + data.html)
                .then(res => res.text())
                .then(html => this.htmlContent = html)
                .catch(() => this.htmlContent = '<p>Gagal memuat konten.</p>');
        }
    }" x-init="loadHtml()" x-html="htmlContent" class="prose max-w-full">
    </div>

</div>
<script>
    function dataHandlePage() {
        return {
            data: @entangle('data'),
            domain: `${window.location.protocol}//${window.location.host}/storage/`,
            init() {
                console.log('Data loaded:', this.data);
            }
        }
    }
</script>
