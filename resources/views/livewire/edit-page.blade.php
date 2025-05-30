<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use App\Models\Page;

new #[Layout('components.layouts.edit')] class extends Component {
    public $pageId;
    public $error;
    public $page;
    public function mount($id)
    {
        $this->pageId = $id;
        $this->loadEdit();
    }

    private function loadEdit()
    {
        try {
            $page = Page::find($this->pageId);

            if (!$page) {
                $this->error = 'Page not found.';
                return;
            }
            $this->page = $page;
        } catch (\Throwable $th) {
            Log::error('Edit Page Error, ' . $th);
            $this->error = 'Error Access Database, check your database.';
        }
    }
}; ?>

<div>
    @push('meta')
        <meta name="keywords" content="universitas, pendidikan, Medan, kampus, unimed, mahasiswa, akademik">
        <meta name="description"
            content="Website resmi Universitas Negeri Medan - informasi akademik, berita kampus, dan layanan mahasiswa.">
    @endpush
    @vite(['resources/js/editor.js'])
    <div x-cloak x-data="{ errorMsg: null, showed: false }" x-show="showed"
        x-on:errors-cont.window="(val) => {
            showed = true;
            errorMsg = val.detail[0].message;
        }"
        tabindex="-1"
        class="animate-fade fixed left-0 right-0 top-0 z-50 h-svh max-h-full w-svw items-center justify-center overflow-y-auto overflow-x-hidden bg-black/30 md:inset-0">
        <div class="relative flex h-full w-full items-center justify-center p-4">
            <div class="relative rounded-lg bg-white shadow-sm dark:bg-gray-700">
                <button type="button"
                    class="absolute end-2.5 top-3 ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="popup-modal">
                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 text-center md:p-5">
                    <svg class="mx-auto mb-4 h-12 w-12 text-gray-400 dark:text-gray-200" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Terjadi Masalah saat
                        mencoba load halaman.</h3>
                    <a href="{{ route('pages.index') }}" type="button"
                        class="ms-3 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <div x-init="initEditPage" x-data="scriptEditPage">
        <div id="gjs">
            <div>Hellow World</div>
        </div>
        <div id="blocks"></div>
    </div>

</div>
<script>
    function scriptEditPage() {
        return {
            stopEditor: false,
            error: @entangle('error'),
            apiToken: null,
            async initEditPage() {
                if (this.stopEditor) return;
                this.stopEditor = true;

                if (this.error) {
                    this.$dispatch('errors-cont', [{
                        message: this.error
                    }]);
                    return;
                }
                await this.getToken();
                if (!this.apiToken) {
                    this.$dispatch('failed', [{
                        message: 'Gagal mendapatkan token API. Segarkan halaman kembali.'
                    }]);
                    return;
                }
                this.runEditor();
            },
            async getToken() {
                await fetch('{{ route('create.token') }}', {
                        method: 'POST',
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.token) {
                            this.apiToken = data.token;
                        }
                    });

            },
            runEditor() {
                const apiToken = this.apiToken;
                const editor = grapesjs.init({
                    container: '#gjs',
                    fromElement: true,
                    storageManager: {
                        type: 'remote',
                        autosave: true,
                        autoload: true,
                        stepsBeforeSave: 10,
                        options: {
                            local: {
                                key: 'gjsProject',
                            },
                            remote: {
                                urlStore: '{{ route('saveData') }}',
                                urlLoad: '{{ route('getPage') }}',
                                headers: {
                                    'Authorization': `Bearer ${apiToken}`,
                                    'Content-Type': 'application/json',
                                },
                            },
                        },

                    },
                    blockManager: {
                        blocks: [{
                                id: 'section',
                                label: '<b class="font-bold text-xl">Section</b>',
                                attributes: {
                                    class: 'gjs-block-section'
                                },
                                content: `<section>
          <h1>This is a simple title</h1>
          <div>This is just a Lorem text: Lorem ipsum dolor sit amet</div>
        </section>`,
                            },
                            {
                                id: 'text',
                                label: 'Text',
                                content: '<div data-gjs-type="text">Insert your text here</div>',
                            },
                            {
                                id: 'image',
                                label: 'Image',
                                select: true,
                                content: {
                                    type: 'image'
                                },
                                activate: true,
                            },
                        ],
                    }
                });

                editor.Panels.addButton('options', {
                    id: 'save-db',
                    className: 'btn-save',
                    label: 'Save',
                    command(editor) {
                        alert('Saving to server...');
                        editor.store();
                    },
                    attributes: {
                        title: 'Save to Server'
                    }
                });
            }
        }
    }
</script>
