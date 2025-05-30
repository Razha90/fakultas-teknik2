<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Content;

new #[Layout('components.layouts.home')] class extends Component {
    public $id;
    public $error;
    public $data;

    // Properti tambahan untuk popular dan related news
    public $popularNews = [];
    public $relatedNews = [];

    public function mount($id)
    {
        $this->id = $id;
    }

    public function search()
    {
        try {
            $this->data = Content::with(['categories', 'user.department', 'type'])
                ->find($this->id)?->toArray();

            if ($this->data) {
                $this->popularNews = $this->getPopularNews()->toArray();
                $this->relatedNews = $this->getRelatedNews($this->id)->toArray();
            }
        } catch (\Throwable $th) {
            $this->error = __('news.not_found');
        }
    }

    public function addView($id)
    {
        $content = Content::find($id);

        if (!$content) {
            dd("Content with ID $id not found");
        }

        $before = $content->views;
        $content->increment('views');
        $after = $content->fresh()->views;

        // dd([
        //     'id' => $id,
        //     'views_before' => $before,
        //     'views_after' => $after,
        // ]);
    }

    // Ambil 4 berita terpopuler berdasarkan views dan status published
    public function getPopularNews()
    {
        return Content::where('status', 'published')
            ->orderByDesc('views')
            ->limit(4)
            ->get();
    }

    // Ambil 3 berita terkait dengan kategori yang sama kecuali berita utama
    public function getRelatedNews($contentId)
    {
        $content = Content::find($contentId);
        if (!$content) {
            return collect();
        }

        return Content::where('categories_id', $content->categories_id)
            ->where('id', '!=', $content->id)
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();
    }
};

?>

<div x-data="initNewsPage()" x-init="init()">
    @push('meta')
        <meta name="keywords" content="universitas, pendidikan, Medan, kampus, unimed, mahasiswa, akademik">
        <meta name="description"
            content="Website resmi Universitas Negeri Medan - informasi akademik, berita kampus, dan layanan mahasiswa.">
    @endpush
    @vite(['resources/js/moment.js'])
    <div class="mx-auto nav-2:mt-32 mt-20 flex max-w-7xl flex-col gap-x-3 px-5 lg:flex-row lg:px-0">
        <div class="order-2 lg:order-1">
            <template x-if="!datas || (Array.isArray(datas) && datas.length === 0)">
                <div class="flex flex-col items-center justify-center rounded-xl bg-gray-100 px-4 py-2">
                    <div
                        class="animate-fade flex h-[50px] w-[50px] animate-pulse items-center justify-center overflow-hidden rounded-xl rounded-t-xl bg-gray-300 dark:bg-gray-700">
                        <svg class="h-[35px] w-[35px] object-cover text-gray-200 dark:text-gray-600" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                            <path
                                d="M18 0H2a2 2 0 0 0-2 2v14a2  2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                        </svg>
                    </div>
                    <div class="animate-fade mt-1 h-4 w-[35px] animate-pulse rounded-full bg-gray-200 dark:bg-gray-700">
                    </div>
                    <div
                        class="animate-fade mt-3 flex h-[50px] w-[50px] animate-pulse items-center justify-center overflow-hidden rounded-xl rounded-t-xl bg-gray-300 dark:bg-gray-700">
                        <svg class="h-[35px] w-[35px] object-cover text-gray-200 dark:text-gray-600" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                            <path
                                d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                        </svg>
                    </div>
                    <div class="animate-fade mt-1 h-4 w-[35px] animate-pulse rounded-full bg-gray-200 dark:bg-gray-700">
                    </div>
                    <div
                        class="animate-fade mt-3 flex h-[50px] w-[50px] animate-pulse items-center justify-center overflow-hidden rounded-xl rounded-t-xl bg-gray-300 dark:bg-gray-700">
                        <svg class="h-[35px] w-[35px] object-cover text-gray-200 dark:text-gray-600" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                            <path
                                d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                        </svg>
                    </div>
                    <div class="mt-1 h-4 w-[35px] animate-pulse rounded-full bg-gray-200 dark:bg-gray-700"></div>
                </div>
            </template>
            <template x-if="datas">
                <div class="relative h-auto w-auto lg:h-full lg:w-24">
                    <div
                        class="static top-32 zflex flex-row justify-center gap-x-3 rounded-xl py-2 lg:sticky lg:flex-col">
                        <div class="flex flex-col items-center justify-center">
                            <div class="animate-fade flex items-center justify-center">
                                <svg class="w-[35px] text-gray-500" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <circle cx="12" cy="12" r="4" fill="currentColor"></circle>
                                        <path d="M21 12C21 12 20 4 12 4C4 4 3 12 3 12" stroke="currentColor"
                                            stroke-width="2">
                                        </path>
                                    </g>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500" x-text="formatView(datas.views)"></p>
                        </div>
                        <div class="mt-0 flex cursor-pointer select-none flex-col items-center justify-center rounded-xl p-2 transition-all hover:bg-blue-100 lg:mt-3"
                            @click="$dispatch('shared')">
                            <svg class="w-[35px] text-blue-400" viewBox="0 -0.5 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M14.734 15.8974L19.22 12.1374C19.3971 11.9927 19.4998 11.7761 19.4998 11.5474C19.4998 11.3187 19.3971 11.1022 19.22 10.9574L14.734 7.19743C14.4947 6.9929 14.1598 6.94275 13.8711 7.06826C13.5824 7.19377 13.3906 7.47295 13.377 7.78743V9.27043C7.079 8.17943 5.5 13.8154 5.5 16.9974C6.961 14.5734 10.747 10.1794 13.377 13.8154V15.3024C13.3888 15.6178 13.5799 15.8987 13.8689 16.0254C14.158 16.1521 14.494 16.1024 14.734 15.8974Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </g>
                            </svg>
                            <p class="text-sm text-blue-400">{{ __('news.share') }}</p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div class="bg-accent-white order-1 w-full pl-2 lg:order-2">
            <template x-if="!datas || (Array.isArray(datas) && datas.length === 0)">
                <div class="bg-accent-white">
                    <div
                        class="animate-fade flex h-[400px] w-full animate-pulse items-center justify-center overflow-hidden rounded-xl rounded-t-xl bg-gray-300 dark:bg-gray-700">
                        <svg class="h-[35px] w-[35px] object-cover text-gray-200 dark:text-gray-600" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                            <path
                                d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                        </svg>
                    </div>
                    <div class="mb-5 mt-4 flex animate-pulse items-center px-5">
                        <svg class="me-3 h-10 w-10 text-gray-200 dark:text-gray-700" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                        </svg>
                        <div>
                            <div class="mb-2 h-2.5 w-32 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                            <div class="h-2 w-48 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                        </div>
                    </div>
                    <div class="mt-3 flex flex-col items-center justify-center">
                        <div class="mb-4 h-7 w-full animate-pulse rounded-full bg-gray-200 dark:bg-gray-700">
                        </div>
                        <div class="mb-4 h-7 w-56 animate-pulse rounded-full bg-gray-200 dark:bg-gray-700">
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="datas">
                <div>
                    <div
                        class="animate-fade relative flex aspect-video items-center justify-center overflow-hidden rounded-xl rounded-t-xl bg-gray-300 dark:bg-gray-700">
                        <img :src="`/storage/${datas.image}`"
                            class="animate-fade absolute inset-0 z-10 h-full w-full object-cover" />
                    </div>
                    <div class="mb-5 mt-4 flex flex-row gap-x-3 px-5">
                        <template x-if="!datas.user.image">
                            <div class="flex items-center">
                                <svg class="me-3 h-10 w-10 text-gray-200 dark:text-gray-700" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                                </svg>
                            </div>
                        </template>
                            <template x-if="datas && datas.user">
                                <div class="flex items-center gap-2">
                                    <img x-bind:src="`/storage/${datas.user.image}`" :alt="datas.user.name" class="h-10 w-10 rounded-full" />
                                    <span class="text-sm text-gray-600" x-text="datas.user.name"></span>
                                </div>
                            </template>
                                <div>
                                   <p class="text-base text-gray-500" x-text="formatDepartment(datas.user.name, datas.user.department?.name)"></p>
                                    <p class="text-sm text-gray-400">{{ __('news.posted') }} <span
                                            x-text="changeDate(datas.created_at)"></span></p>
                                </div>
                    </div>
                    <div class="ftnews-1:mt-10 mt-5">
                        <h1 class="text-primary text-center text-2xl font-bold lg:text-4xl ftnews-1:text-xl" x-text="datas.title"></h1>
                    </div>
                    <div class="mt-10">
                        <style>
                            #content p {
                                color: var(--color-gray-400);
                                line-height: 30px;
                                font-size: 20px;
                                text-indent: 40px;
                            }

                            #content {
                                display: flex;
                                flex-direction: column;
                                row-gap: 20px;
                            }
                        </style>
                        <div id="content" x-text="plainContent"></div>
                        <div class="mt-3 flex flex-wrap gap-3">
                            <template x-if="datas.categories && datas.categories.length > 0">
                                <template x-for="(item, key) in datas.categories">
                                    <div x-text="item.name"
                                        class="cursor-pointer bg-gray-200 px-4 py-1 text-sm text-gray-400 transition-all hover:scale-110"
                                        @click="goToPage('{{ route('news-search') }}' + '?category=' + item.name)">
                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div class="order-3">
            <div class="static top-32 lg:sticky"><livewire:component.news-recomendation /></div>
        </div>
    </div>


    <div x-cloak x-data="{
        showup: false,
        get getLink() {
            const fullUrl = window.location.href;
            const cleanedUrl = fullUrl.replace(/^https?:\/\//, '');
            return cleanedUrl;
        },
        copyLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url)
                .then(() => {
                    this.$dispatch('success', [{
                        message: '{{ __('news.copy_success') }}'
                    }]);
                })
                .catch(err => {
                    console.error('Gagal menyalin link: ', err);
                    this.$dispatch('failed', [{
                        message: '{{ __('news.copy_failed') }}'
                    }]);
                });
        }
    }" x-show="showup" x-on:shared.window="showup = true"
        class="fixed left-0 top-0 z-30 flex h-screen w-screen items-center justify-center bg-black/15">
        <div class="bg-accent-white min-w-xs w-md animate-fade overflow-hidden rounded-xl px-5 py-5"
            @click.away="showup=false">
            <div class="flex w-full flex-row items-center justify-between border-b-2 border-gray-200 pb-3">
                <h2 class="text-primary text-2xl font-bold">{{ __('news.share') }}</h2>
                <button @click="showup = false" type="button"
                    class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-900 focus:ring-2 focus:ring-gray-300"
                    data-dismiss-target="#toast-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
            <div>
                <h3 class="text-primary my-3 text-base">{{ __('news.share_via') }}</h3>
                <div class="flex flex-row gap-x-3">
                    <div
                        class="cursor-pointer rounded-full border-2 border-blue-200 bg-white p-2 text-blue-200 transition-all hover:border-blue-400 hover:text-blue-400">
                        <svg class="w-[30px]" fill="currentColor" viewBox="0 0 32 32"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M21.95 5.005l-3.306-.004c-3.206 0-5.277 2.124-5.277 5.415v2.495H10.05v4.515h3.317l-.004 9.575h4.641l.004-9.575h3.806l-.003-4.514h-3.803v-2.117c0-1.018.241-1.533 1.566-1.533l2.366-.001.01-4.256z">
                                </path>
                            </g>
                        </svg>
                    </div>
                    <div
                        class="border-accent cursor-pointer rounded-full border-2 p-2 opacity-30 transition-all hover:opacity-100">
                        <svg class="w-[30px]" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path
                                d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
                        </svg>
                    </div>
                    <div
                        class="cursor-pointer rounded-full border-2 border-green-200 bg-white p-2 text-green-200 transition-all hover:border-green-400 hover:text-green-400">
                        <svg class="w-[30px]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M6.014 8.00613C6.12827 7.1024 7.30277 5.87414 8.23488 6.01043L8.23339 6.00894C9.14051 6.18132 9.85859 7.74261 10.2635 8.44465C10.5504 8.95402 10.3641 9.4701 10.0965 9.68787C9.7355 9.97883 9.17099 10.3803 9.28943 10.7834C9.5 11.5 12 14 13.2296 14.7107C13.695 14.9797 14.0325 14.2702 14.3207 13.9067C14.5301 13.6271 15.0466 13.46 15.5548 13.736C16.3138 14.178 17.0288 14.6917 17.69 15.27C18.0202 15.546 18.0977 15.9539 17.8689 16.385C17.4659 17.1443 16.3003 18.1456 15.4542 17.9421C13.9764 17.5868 8 15.27 6.08033 8.55801C5.97237 8.24048 5.99955 8.12044 6.014 8.00613Z"
                                    fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 23C10.7764 23 10.0994 22.8687 9 22.5L6.89443 23.5528C5.56462 24.2177 4 23.2507 4 21.7639V19.5C1.84655 17.492 1 15.1767 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23ZM6 18.6303L5.36395 18.0372C3.69087 16.4772 3 14.7331 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C11.0143 21 10.552 20.911 9.63595 20.6038L8.84847 20.3397L6 21.7639V18.6303Z"
                                    fill="currentColor"></path>
                            </g>
                        </svg>
                    </div>
                    <div
                        class="cursor-pointer rounded-full border-2 border-blue-200 bg-white p-2 text-blue-200 transition-all hover:border-blue-400 hover:text-blue-400">
                        <svg class="w-[30px]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M18.72 3.99997H5.37C5.19793 3.99191 5.02595 4.01786 4.86392 4.07635C4.70189 4.13484 4.55299 4.22471 4.42573 4.34081C4.29848 4.45692 4.19537 4.59699 4.12232 4.75299C4.04927 4.909 4.0077 5.07788 4 5.24997V18.63C4.01008 18.9901 4.15766 19.3328 4.41243 19.5875C4.6672 19.8423 5.00984 19.9899 5.37 20H18.72C19.0701 19.9844 19.4002 19.8322 19.6395 19.5761C19.8788 19.32 20.0082 18.9804 20 18.63V5.24997C20.0029 5.08247 19.9715 4.91616 19.9078 4.76122C19.8441 4.60629 19.7494 4.466 19.6295 4.34895C19.5097 4.23191 19.3672 4.14059 19.2108 4.08058C19.0544 4.02057 18.8874 3.99314 18.72 3.99997ZM9 17.34H6.67V10.21H9V17.34ZM7.89 9.12997C7.72741 9.13564 7.5654 9.10762 7.41416 9.04768C7.26291 8.98774 7.12569 8.89717 7.01113 8.78166C6.89656 8.66615 6.80711 8.5282 6.74841 8.37647C6.6897 8.22474 6.66301 8.06251 6.67 7.89997C6.66281 7.73567 6.69004 7.57169 6.74995 7.41854C6.80986 7.26538 6.90112 7.12644 7.01787 7.01063C7.13463 6.89481 7.2743 6.80468 7.42793 6.74602C7.58157 6.68735 7.74577 6.66145 7.91 6.66997C8.07259 6.66431 8.2346 6.69232 8.38584 6.75226C8.53709 6.8122 8.67431 6.90277 8.78887 7.01828C8.90344 7.13379 8.99289 7.27174 9.05159 7.42347C9.1103 7.5752 9.13699 7.73743 9.13 7.89997C9.13719 8.06427 9.10996 8.22825 9.05005 8.3814C8.99014 8.53456 8.89888 8.6735 8.78213 8.78931C8.66537 8.90513 8.5257 8.99526 8.37207 9.05392C8.21843 9.11259 8.05423 9.13849 7.89 9.12997ZM17.34 17.34H15V13.44C15 12.51 14.67 11.87 13.84 11.87C13.5822 11.8722 13.3313 11.9541 13.1219 12.1045C12.9124 12.2549 12.7546 12.4664 12.67 12.71C12.605 12.8926 12.5778 13.0865 12.59 13.28V17.34H10.29V10.21H12.59V11.21C12.7945 10.8343 13.0988 10.5225 13.4694 10.3089C13.84 10.0954 14.2624 9.98848 14.69 9.99997C16.2 9.99997 17.34 11 17.34 13.13V17.34Z"
                                    fill="currentColor"></path>
                            </g>
                        </svg>
                    </div>
                    <div
                        class="cursor-pointer rounded-full border-2 border-red-200 bg-white p-2 text-red-200 transition-all hover:border-red-400 hover:text-red-400">
                        <svg class="w-[30px]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18ZM12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16Z"
                                    fill="currentColor"></path>
                                <path
                                    d="M18 5C17.4477 5 17 5.44772 17 6C17 6.55228 17.4477 7 18 7C18.5523 7 19 6.55228 19 6C19 5.44772 18.5523 5 18 5Z"
                                    fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M1.65396 4.27606C1 5.55953 1 7.23969 1 10.6V13.4C1 16.7603 1 18.4405 1.65396 19.7239C2.2292 20.8529 3.14708 21.7708 4.27606 22.346C5.55953 23 7.23969 23 10.6 23H13.4C16.7603 23 18.4405 23 19.7239 22.346C20.8529 21.7708 21.7708 20.8529 22.346 19.7239C23 18.4405 23 16.7603 23 13.4V10.6C23 7.23969 23 5.55953 22.346 4.27606C21.7708 3.14708 20.8529 2.2292 19.7239 1.65396C18.4405 1 16.7603 1 13.4 1H10.6C7.23969 1 5.55953 1 4.27606 1.65396C3.14708 2.2292 2.2292 3.14708 1.65396 4.27606ZM13.4 3H10.6C8.88684 3 7.72225 3.00156 6.82208 3.0751C5.94524 3.14674 5.49684 3.27659 5.18404 3.43597C4.43139 3.81947 3.81947 4.43139 3.43597 5.18404C3.27659 5.49684 3.14674 5.94524 3.0751 6.82208C3.00156 7.72225 3 8.88684 3 10.6V13.4C3 15.1132 3.00156 16.2777 3.0751 17.1779C3.14674 18.0548 3.27659 18.5032 3.43597 18.816C3.81947 19.5686 4.43139 20.1805 5.18404 20.564C5.49684 20.7234 5.94524 20.8533 6.82208 20.9249C7.72225 20.9984 8.88684 21 10.6 21H13.4C15.1132 21 16.2777 20.9984 17.1779 20.9249C18.0548 20.8533 18.5032 20.7234 18.816 20.564C19.5686 20.1805 20.1805 19.5686 20.564 18.816C20.7234 18.5032 20.8533 18.0548 20.9249 17.1779C20.9984 16.2777 21 15.1132 21 13.4V10.6C21 8.88684 20.9984 7.72225 20.9249 6.82208C20.8533 5.94524 20.7234 5.49684 20.564 5.18404C20.1805 4.43139 19.5686 3.81947 18.816 3.43597C18.5032 3.27659 18.0548 3.14674 17.1779 3.0751C16.2777 3.00156 15.1132 3 13.4 3Z"
                                    fill="currentColor"></path>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-primary my-3 text-base">{{ __('news.share_link') }}</h3>
                <div class="flex flex-row items-center justify-between rounded-xl border-2 border-gray-200 p-2">
                    <div class="flex flex-row items-center justify-start gap-x-2">
                        <div class="text-primary">
                            <svg class="w-[25px]" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M10.0464 14C8.54044 12.4882 8.67609 9.90087 10.3494 8.22108L15.197 3.35462C16.8703 1.67483 19.4476 1.53865 20.9536 3.05046C22.4596 4.56228 22.3239 7.14956 20.6506 8.82935L18.2268 11.2626"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                    <path opacity="0.5"
                                        d="M13.9536 10C15.4596 11.5118 15.3239 14.0991 13.6506 15.7789L11.2268 18.2121L8.80299 20.6454C7.12969 22.3252 4.55237 22.4613 3.0464 20.9495C1.54043 19.4377 1.67609 16.8504 3.34939 15.1706L5.77323 12.7373"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                </g>
                            </svg>
                        </div>
                        <p class="text-primary w-[280px] truncate text-base" x-text="getLink"></p>
                    </div>
                    <button @click="copyLink"
                        class="bg-primary text-accent-white border-primary hover:text-primary hover:bg-accent-white cursor-pointer rounded-md border-2 px-4 py-1 font-bold transition-all"
                        type="button">{{ __('news.copy') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col mt-10">
        <div class="text-center">
            <h2
                class="text-primary text-primary relative inline-block text-2xl font-bold after:absolute after:-bottom-1 after:left-0 after:block after:h-[4px] after:w-1/4 after:rounded-full after:bg-green-400 after:transition-all after:duration-300 hover:after:w-full">
                {{ __('news.related_post') }}</h2>
        </div>
        <livewire:component.news-post />
    </div>
</div>
<script>
    function initNewsPage() {
        return {
            datas: @entangle('data').live,
            error: @entangle('error').live,
            stopInit: false,
            popularNews: @entangle('popularNews').live,
            relatedNews: @entangle('relatedNews').live,


            // Getter untuk konten tanpa tag HTML
            get plainContent() {
                try {
                    const htmlString = this.datas?.description || '';
                    const temp = document.createElement("div");
                    temp.innerHTML = htmlString;
                    return temp.textContent || temp.innerText || '';
                } catch (e) {
                    console.error('Error parsing plainContent:', e);
                    return '';
                }
            },

            formatDepartment(userName, departmentName) {
                if (!departmentName) return userName;

                const nameLower = departmentName.toLowerCase();

                let finalDept = nameLower.startsWith("staf ") ? departmentName.slice(5) : departmentName;

                return `Staf ${finalDept}`;
            },

            init() {
                if (this.stopInit) return;
                this.stopInit = true;

                console.log("⏳ Running this.$wire.search()...");
                this.$wire.search();

                setTimeout(() => {
                    if (this.error) {
                        console.error("❌ Error from Livewire:", this.error);
                        this.$dispatch('failed', [{
                            message: this.error,
                        }]);
                    }
                }, 1000);

                this.$watch('datas', (value) => {
                    console.log("📡 datas changed:", value);
                    if (value?.id) {
                        console.log("👁️ Calling addView for ID:", value.id);
                        this.addView(value.id);
                    }
                });
            },

            changeDate(createdAt) {
                const formattedTime = moment(createdAt).fromNow();
                return formattedTime;
            },

            addView(id) {
                const data = localStorage.getItem('views');
                let views = data ? JSON.parse(data) : [];

                if (!views.includes(id)) {
                    console.log("➕ Sending addView to Livewire for:", id);
                    this.$wire.addView(id); // Fungsi Livewire (PHP)
                    this.datas.views++;
                    views.push(id);
                    localStorage.setItem('views', JSON.stringify(views));
                    console.log("✅ View added and saved to localStorage:", views);
                } else {
                    console.log("⚠️ View already counted for ID:", id);
                }
            },

            formatView(number) {
                if (number >= 1_000_000) {
                    return (number / 1_000_000).toFixed(1).replace('.', ',') + 'M';
                } else if (number >= 1_000) {
                    return (number / 1_000).toFixed(1).replace('.', ',') + 'K';
                } else {
                    return number.toString();
                }
            }
        }
    }
</script>

