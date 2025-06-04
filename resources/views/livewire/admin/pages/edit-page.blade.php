<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use App\Models\Page;
use App\Models\Menu;
use App\Http\Controllers\Permalink;

new #[Layout('components.layouts.edit')] class extends Component {
    public $pageId;
    public $error;
    public $page;
    public $menus;
    public function mount($id)
    {
        $this->pageId = $id;
        $this->loadEdit();
        $this->loadMenu();
    }

    private function loadEdit()
    {
        try {
            $page = Page::with('menu')->find($this->pageId);

            if (!$page) {
                $this->error = 'Page not found.';
                return;
            }
            $this->page = $page->toArray();
        } catch (\Throwable $th) {
            Log::error('Edit Page Error, ' . $th);
            $this->error = 'Error Access Database, check your database.';
        }
    }

    private function loadMenu()
    {
        try {
            $menus = Menu::where('isActive', true)->orderBy('position', 'asc')->get();
            $this->menus = $menus->toArray();
        } catch (\Throwable $th) {
            Log::error('Load Menu Error, ' . $th);
            $this->error = 'Error Access Database, check your database.';
        }
    }

    public function savePage($title = '', $link = '', $datetime = '', $keyword = '', $description = '', $parent = null)
    {
        try {
            $page = Page::find($this->pageId);
            if (!$page) {
                $this->dispatch('failed', ['message' => 'Halaman tidak ditemukan.']);
                return [
                    'status' => false,
                ];
            }
            if ($datetime) {
                $datetime = str_replace('T', ' ', $datetime);
                if (strlen($datetime) == 16) {
                    $datetime .= ':00';
                }
            } else {
                $datetime = null;
            }
            $linked = new Permalink();
            $formattedPath = $linked->formatLink($link);
            if ($formattedPath === false) {
                $this->dispatch('failed', [
                    'message' => 'Path menu mengandung karakter yang tidak valid.',
                ]);
                return [
                    'status' => false,
                ];
            }
            if (!$linked->checkLink($formattedPath) && $formattedPath != $page->path) {
                $this->dispatch('failed', [
                    'message' => 'Path menu sudah digunakan oleh halaman atau menu lain.',
                ]);
                return [
                    'status' => false,
                ];
            }

            $page->name = $title;
            $page->path = $formattedPath;
            $page->release = $datetime;
            $page->menu_id = $parent;
            $page->keywords = $keyword;
            $page->description = $description;
            $page->save();
            $this->dispatch('success', ['message' => 'Halaman berhasil disimpan.']);
            return [
                'status' => true,
            ];
        } catch (\Throwable $th) {
            Log::error('Save Page Error, ' . $th);
            $this->dispatch('failed', ['message' => 'Gagal menyimpan halaman, periksa koneksi database.']);
            return [
                'status' => false,
            ];
        }
    }

    public function publishPage($condition)
    {
        try {

            $page = Page::find($this->pageId);
            if (!$page) {
                $this->dispatch('failed', ['message' => 'Halaman tidak ditemukan.']);
                return;
            }
            if ($page->name == '') {
                $this->dispatch('failed', ['message' => 'Judul halaman tidak boleh kosong.']);
                return;
            }
            if (empty($page->path) ) {
                $this->dispatch('failed', ['message' => 'Path halaman tidak boleh kosong.']);
                return;
            }
           
            $page->isReleased = !$condition;
            $page->save();
            $this->loadEdit();
            if ($condition) {
                $this->dispatch('saved', ['message' => 'Halaman berhasil dipublikasikan.']);
            } else {
                $this->dispatch('saved', ['message' => 'Halaman berhasil disimpan sebagai draf.']);
            }
        } catch (\Throwable $th) {
            Log::error('Publish Page Error, ' . $th);
            $this->dispatch('failed', ['message' => 'Gagal mempublikasikan halaman, periksa koneksi database.']);
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

    <div x-init="initEditPage" x-data="scriptEditPage" class="relative h-screen min-h-[600px] w-full min-w-[600px]">
        <div x-show="openMenu" class="animate-fade-right fixed inset-0 z-10 bg-black/30"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full opacity-0 bg-secondary-accent/0"
            x-transition:enter-end="translate-x-0 opacity-100 bg-secondary-accent/30"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="-translate-x-full opacity-0">
            <div @click.away="openMenu = false"
                class="fixed left-0 top-0 z-50 h-full w-[400px] overflow-y-auto bg-white p-3 shadow-lg">
                <div class="hover:text-secondary-warn absolute right-5 top-5 cursor-pointer text-red-500 transition-all hover:rotate-90"
                    @click="openMenu=false">
                    <svg class="h-[25px] w-[25px]" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill="currentColor"
                                d="M195.2 195.2a64 64 0 0 1 90.496 0L512 421.504 738.304 195.2a64 64 0 0 1 90.496 90.496L602.496 512 828.8 738.304a64 64 0 0 1-90.496 90.496L512 602.496 285.696 828.8a64 64 0 0 1-90.496-90.496L421.504 512 195.2 285.696a64 64 0 0 1 0-90.496z">
                            </path>
                        </g>
                    </svg>
                </div>
                <a href="{{ route('pages.index') }}"
                    class="hover:text-secondary-warn absolute left-5 top-5 cursor-pointer text-blue-500 transition-all">
                    Kembali
                </a>
                <h2 class="text-primary mt-10 text-2xl font-bold">Pengaturan Halaman</h2>
                <div class="mt-5">
                    <label for="titlePage" class="text-primary ml-2">Judul Halaman</label>
                    <input id="titlePage" type="text" class="w-full rounded-md border border-gray-300 p-2"
                        placeholder="Masukkan Judul Halaman" x-model="pageTitle">
                </div>
                <div class="relative mt-5" x-data="{ openLink: false }">
                    <label for="linked" class="text-primary ml-2">Masukkan Tautan Halaman</label>
                    <div class="line-clamp-1 flex flex-row overflow-hidden rounded-xl bg-gray-100"
                        @click.away="openLink=false">
                        <div class="flex flex-row items-center px-2" @click="openLink=!openLink">
                            <p class="text-sm text-gray-500" x-text="pageParentLink ? pageParentLink.path : 'Utama' ">
                            </p>
                            <div class="text-gray-400">
                                <svg class="w-[25px]" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M5.70711 9.71069C5.31658 10.1012 5.31658 10.7344 5.70711 11.1249L10.5993 16.0123C11.3805 16.7927 12.6463 16.7924 13.4271 16.0117L18.3174 11.1213C18.708 10.7308 18.708 10.0976 18.3174 9.70708C17.9269 9.31655 17.2937 9.31655 16.9032 9.70708L12.7176 13.8927C12.3271 14.2833 11.6939 14.2832 11.3034 13.8927L7.12132 9.71069C6.7308 9.32016 6.09763 9.32016 5.70711 9.71069Z"
                                            fill="currentColor"></path>
                                    </g>
                                </svg>
                            </div>
                        </div>
                        <input id="linked" type="text" class="w-full rounded-r-xl border border-gray-300 p-2"
                            placeholder="contoh-link" x-model="pageLink">
                    </div>
                    <div x-cloak x-show="openLink"
                        class="absolute top-full flex w-[300px] flex-col items-center gap-y-3 rounded-md border border-gray-300 bg-white px-3 py-2"
                        x-transition>
                        <button type="button"
                            class="mb-2 me-2 rounded-lg bg-gradient-to-r from-red-400 via-red-500 to-red-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-gradient-to-br focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800"
                            @click="pageParentLink = null">
                            Hapus
                        </button>
                        <template x-if="menus">
                            <template x-for="menu in menus" :key="menu.id">
                                <div class="flex w-full flex-row items-center justify-between rounded-md bg-gray-100 p-2"
                                    @click="pageParentLink = menu">
                                    <p x-text="menu.path" class="line-clamp-1 text-gray-500"></p>
                                    <p x-text="menu.name" class="line-clamp-1 text-gray-400"></p>
                                </div>
                            </template>
                        </template>

                        <a href="{{ route('menu.index') }}" target="_blank"
                            class="text-sm text-blue-500 underline">Tambah Menu Baru</a>
                    </div>
                </div>
                <div class="mt-5 flex w-full flex-col">
                    <label for="keywords" class="text-primary ml-2">keywords</label>
                    <textarea id="keywords" x-model="pageKeywords" rows="7"
                        class="border-primary rounded-md border bg-gray-100 p-2"></textarea>
                </div>
                <div class="mt-5 flex w-full flex-col">
                    <label for="description" class="text-primary ml-2">Deskripsi</label>
                    <textarea id="description" x-model="pageDescription" rows="7"
                        class="border-primary rounded-md border bg-gray-100 p-2"></textarea>
                </div>
                <div class="mt-5">
                    <label for="datetime" class="text-primary ml-2">Waktu Rilis</label>
                    <div class="w-full rounded-xl border border-gray-300 bg-gray-100 p-2">
                        <!-- <div id="times" class="w-full"></div> -->
                        <input type="datetime-local" :min="minDate" class="h-full w-full" id="datetime"
                            x-model="pageRelease">
                    </div>
                </div>
                <div class="mt-5 text-center">
                    <button type="button" @click="handlePublish"
                        x-bind:class="pageIsRelease ? 'bg-red-600 focus:ring-red-600 hover:bg-red-800' :
                            'bg-blue-700 focus:ring-blue-300 hover:bg-blue-800'"
                        class="mb-2 me-2 rounded-lg px-5 py-2.5 text-sm font-medium text-white focus:ring-4">
                        <span x-show="!loadingPublish" x-text="pageIsRelease ? 'Draf' : 'Publikasi'"></span>
                        <svg x-show="loadingPublish" aria-hidden="true"
                            class="h-5 w-5 animate-spin fill-blue-600 text-gray-200 dark:text-gray-600"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="gjs" class="!h-full" x-cloak wire:ignore>
            <div style="position: relative; overflow: hidden;">
                <div
                    style="
    position: absolute;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    z-index: 10;
    height: 100%;
    width: 100%;
    /* Jika ingin animasi fade, tambahkan animasi di sini */
  ">
                    <div
                        style="
      position: relative;
      display: flex;
      height: 100%;
      width: 100%;
      align-items: center;
      justify-content: center;
    ">
                        <img src="{{ asset('img/bg.jpg') }}"
                            class="absolute inset-0 left-0 top-0 z-10 h-full w-full object-cover"
                            style="position: absolute; inset: 0; left: 0; top: 0; z-index: 10; height: 100%; width: 100%; object-fit: cover;" />
                        <div
                            style="
  position: absolute;
  inset: 0;
  left: 0;
  top: 0;
  z-index: 10;
  background-color: rgba(0, 0, 0, 0.6);
">
                        </div>

                        <h1
                            style="
  color: #f8fafc; /* kira-kira warna text-accent-white */
  position: relative;
  z-index: 30;
  text-align: center;
  font-weight: 700;
  font-size: 1.25rem; /* text-xl */
  /* Responsive font sizes (md:text-5xl) dan ftnews-1:text-3xl harus di-handle di CSS atau JS terpisah */
">
                            Tulis Judul Halaman di Sini
                        </h1>

                    </div>
                </div>
                <div
                    style="
  display: flex;
  aspect-ratio: 16 / 9;
  max-height: 420px;
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  align-items: center;
  justify-content: center;
  border-radius: 0.5rem;
  background-color: #d1d5db; /* bg-gray-300 */
  /* dark mode tidak bisa langsung inline CSS, harus via JS atau media query CSS */
">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 18"
                        style="
      height: 100%;
      width: 100%;
      color: #e5e7eb; /* text-gray-200 */
      /* dark mode untuk text-gray-600 harus handle terpisah */
    ">
                        <path
                            d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                    </svg>

                </div>

                <style>

                    @keyframes pulse {

                        0%,
                        100% {
                            opacity: 1;
                        }

                        50% {
                            opacity: 0.5;
                        }
                    }
                </style>

            </div>
        </div>
    </div>

</div>
<script>
    function scriptEditPage() {
        return {
            stopEditor: false,
            openMenu: true,
            error: @entangle('error'),
            apiToken: null,
            pageId: @entangle('pageId'),
            page: @entangle('page').live,
            menus: @entangle('menus'),
            pageTitle: "",
            pageLink: "",
            pageKeywords: "",
            pageDescription: "",
            pageRelease: "",
            pageIsRelease: false,
            pageParentLink: null,
            loadingPublish: false,
            stopInitDateTime: false,
            minDate: new Date().toISOString().slice(0, 16),
            saving: false,
            async handlePublish() {
                try {
                    const check = await this.savePage();
                    if (!check) return;
                    this.loadingPublish = true;
                    await this.$wire.publishPage(this.pageIsRelease);
                } catch (error) {
                    this.$dispatch('failed', [{
                        message: `Error saat mempublikasikan halaman: ${error.message}`
                    }]);
                } finally {
                    this.loadingPublish = false;
                }
            },
            async savePage() {
                try {
                    let parentLink = "";
                    if (this.pageParentLink) {
                        parentLink = this.pageParentLink.id;
                    } else {
                        parentLink = null;
                    }

                    const data = await this.$wire.savePage(this.pageTitle, this.pageLink, this.pageRelease, this.pageKeywords,
                        this
                        .pageDescription, parentLink);
                        return data.status;
                } catch (error) {
                    this.$dispatch('failed', [{
                        message: `Error saat menyimpan halaman: ${error.message}`
                    }]);
                    return false;
                }
            },
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
                this.initData();
            },
            initData() {
                this.pageTitle = this.page.name || "";
                this.pageLink = this.page.path || "";
                this.pageKeywords = this.page.keywords || "";
                this.pageDescription = this.page.description || "";
                this.pageRelease = this.page.release || "";
                this.pageIsRelease = this.page.isReleased == '1' ? true : false;
                this.pageParentLink = this.page.menu || null;
                this.$watch('page', (newValue) => {
                    this.pageTitle = this.page.name || "";
                    this.pageLink = this.page.path || "";
                    this.pageKeywords = this.page.keywords || "";
                    this.pageDescription = this.page.description || "";
                    this.pageRelease = this.page.release || "";
                    this.pageIsRelease = this.page.isReleased == '1' ? true : false;
                    this.pageParentLink = this.page.menu || null;
                });
            },
            initDateTime() {
                if (this.stopInitDateTime) return;
                this.stopInitDateTime = true;
                this.$nextTick(() => {
                    const input = document.getElementById('datetime');
                    const now = new Date();
                    const pad = (n) => n.toString().padStart(2, '0');
                    const formattedNow = [
                        now.getFullYear(),
                        pad(now.getMonth() + 1),
                        pad(now.getDate())
                    ].join('-') + 'T' + [
                        pad(now.getHours()),
                        pad(now.getMinutes())
                    ].join(':');
                    input.value = formattedNow;
                    input.min = formattedNow;
                });
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
                const editor = grapesjs.init({
                    plugins: [newsletter, block_basic, gjsForms, customCodePlugin, pluginCountdown,
                        navbarGrapes, grapesjsIcons
                    ],
                    pluginsOpts: {
                        [newsletter]: {
                            blocks: [
                                // 'sect100',
                                // 'sect50',
                                // 'sect30',
                                // 'sect37',
                                'button',
                                'divider',
                                'text',
                                'text-sect',
                                'image',
                                'quote',
                                'grid-items',
                                'list-items'
                            ]
                        },
                        [block_basic]: {
                            blocks: ['column1', 'column2', 'column3', 'column3-7', 'text', 'link', 'image',
                                'video', 'map'
                            ]
                        },
                        [gjsForms]: {
                            blocks: ['form', 'input', 'textarea', 'select', 'button', 'label', 'checkbox',
                                'radio'
                            ]
                        },
                        [customCodePlugin]: {
                            blocks: ['custom-code']
                        },
                        [pluginCountdown]: {
                            blocks: []
                        },
                        [navbarGrapes]: {
                            blocks: []
                        },
                        [imageTui]: {
                            blocks: [],
                            upload: true,
                        },
                        [grapesjsIcons]: {
                            collections: [
                                'ri', // Remix Icon by Remix Design
                                'mdi', // Material Design Icons by Pictogrammers
                                'uim', // Unicons Monochrome by Iconscout
                                'streamline-emojis' // Streamline Emojis by Streamline
                            ],
                        }
                    },
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
                                urlStore: this.saveDataURL(this.pageId),
                                urlLoad: this.getPageURL(this.pageId),
                                headers: {
                                    'Authorization': `Bearer ${this.apiToken}`,
                                    'Content-Type': 'application/json',
                                },
                            },
                        },

                    },
                    blockManager: {
                        blocks: [

                        ],
                    },
                    assetManager: {
                        upload: '{{ route('upload-image') }}',
                        uploadName: 'file',
                        headers: {
                            'Authorization': `Bearer ${this.apiToken}`,
                            'Jenis-File' : 'page',
                            'page-id': this.pageId,
                        },
                    }
                });
                let saveButton = null;
                let isDirty = false;
                editor.Panels.addButton('options', {
                    id: 'save-db',
                    className: 'btn-save',
                    label: '💾 Save',
                    command(editor) {
                        if (!isDirty) return;
                        editor.store();
                        const html = editor.getHtml();
                        const css = editor.getCss();
                        saveCompile(html, css);
                        savePage();
                    },
                    attributes: {
                        title: 'Save to Server'
                    }
                });
                const changeOpen = () => {
                    this.openMenu = !this.openMenu;
                }
                const buttons = editor.Panels.getPanel('devices-c').get('buttons').models;
                const barButton = {
                    id: 'openBar',
                    className: 'fa fa-bars',
                    command(editor) {
                        changeOpen();
                    },
                    attributes: {
                        title: 'Menu'
                    }
                };

                const savePage = () => {
                    this.savePage();
                }

                const markDirty = () => {
                    if (!isDirty && saveButton) {
                        isDirty = true;
                        saveButton.set('label', '🔴 Unsaved');
                        saveButton.set('attributes', {
                            title: 'Changes not saved'
                        });
                        this.saving = true;
                    }
                };

                this.$watch('pageTitle', markDirty);
                this.$watch('pageLink', markDirty);
                this.$watch('pageKeywords', markDirty);
                this.$watch('pageDescription', markDirty);
                this.$watch('pageRelease', markDirty);
                this.$watch('pageIsRelease', markDirty);
                this.$watch('pageParentLink', markDirty);

                editor.Panels.getPanel('devices-c').get('buttons').reset([
                    barButton,
                    ...buttons.map(btn => btn.attributes)
                ]);

                document.addEventListener('keydown', function(event) {
                    if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 's') {
                        event.preventDefault();
                        if (!isDirty) return;
                        editor.store();
                        const html = editor.getHtml();
                        const css = editor.getCss();
                        saveCompile(html, css);
                        savePage();
                    }
                });

                editor.on('load', () => {
                    saveButton = editor.Panels.getButton('options', 'save-db');
                    const previewBtn = editor.Panels.getButton('options', 'preview');
                    if (previewBtn) {
                        previewBtn.set('command', () => {
                            const pageId = 'some-id';
                            const previewUrl = `/preview/${pageId}`;
                            window.open(previewUrl, '_blank');
                        });
                        previewBtn.set('attributes', {
                            title: 'Open Preview in New Tab'
                        });
                    }
                });
                editor.on('component:add', markDirty);
                editor.on('component:remove', markDirty);
                editor.on('component:update', markDirty);
                editor.on('styleManager:change', markDirty);
                editor.on('asset:upload:start', markDirty);


                editor.on('storage:start', () => {
                    if (saveButton) {
                        saveButton.set('label', '⏳ Saving...');
                        saveButton.set('attributes', {
                            title: 'Saving in progress...'
                        });
                    }
                });
                editor.on('storage:end', () => {
                    isDirty = false;
                    if (saveButton) {
                        saveButton.set('label', '✅ Saved');
                        saveButton.set('attributes', {
                            title: 'Saved successfully'
                        });

                        setTimeout(() => {
                            if (!isDirty) {
                                saveButton.set('label', '💾 Save');
                                saveButton.set('attributes', {
                                    title: 'Save to Server'
                                });
                            }
                        }, 3000);
                    }
                });
                const saveCompile = (html, css) => {
                    try {
                        fetch(this.getCompileURL(this.pageId), {
                                method: 'POST',
                                headers: {
                                    'Authorization': `Bearer ${this.apiToken}`,
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    html: html,
                                    css: css
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`Server error: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {})
                            .catch(error => {
                                this.$dispatch('failed', [{
                                    message: `Gagal menyimpan HTML: ${error.message}`
                                }]);

                            });
                    } catch (error) {
                        this.$dispatch('failed', [{
                            message: `Error dalam fungsi saveCompile: ${error.message}`
                        }]);
                    }
                }
            },
            saveDataURL(page) {
                const urlStoreTemplate = `{{ route('saveData', ['id' => '__ID__']) }}`;
                const finalUrlStore = urlStoreTemplate.replace('__ID__', page);
                return finalUrlStore;
            },
            getPageURL(page) {
                const urlStoreTemplate = `{{ route('getPage', ['id' => '__ID__']) }}`;
                const finalUrlStore = urlStoreTemplate.replace('__ID__', page);
                return finalUrlStore;
            },
            getCompileURL(page) {
                const urlStoreTemplate = `{{ route('saveCompile', ['id' => '__ID__']) }}`;
                const finalUrlStore = urlStoreTemplate.replace('__ID__', page);
                return finalUrlStore;
            },
        }
    }
</script>
