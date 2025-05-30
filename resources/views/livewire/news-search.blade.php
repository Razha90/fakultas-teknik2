<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Content;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

new #[Layout('components.layouts.home')] class extends Component {
    public $category;
    public $search;
    public $sort;
    public $dateStart;
    public $dateWhen;
    public $data;
    public $page;
    public $loading = true;

    public function mount()
    {
        $page = request()->query('page', 1);
        $page = is_numeric($page) && intval($page) > 0 ? intval($page) : 1;
        if (request()->query('page') != $page) {
            return redirect()->to(request()->fullUrlWithQuery(['page' => $page]));
        }
        $this->page = $page;

        $this->category = request()->query('category') ?? '';
        $this->search = request()->query('search') ?? '';
        $this->sort = request()->query('sort') ?? 'asc';
        $this->dateStart = request()->query('dateStart') ?? '';
        $this->dateWhen = request()->query('dateWhen') ?? '';
    }

    public function fullSearch($search = '', $sort = 'asc', $page = 1, $limit = 10, $category = '', $dateStart = '', $dateWhen = '')
{
    try {
        $this->loading = true;

        // Validasi arah sorting
        $sortDirection = in_array($sort, ['asc', 'desc']) ? $sort : 'asc';

        $this->data = Content::with('categories') // ganti News:: menjadi Content::
            ->when($search !== '', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })
            ->when($category !== '', function ($q) use ($category) {
                $q->whereHas('categories', function ($query) use ($category) {
                    $query->where('name', 'like', "%{$category}%");
                });
            })
            ->when($dateStart !== '', function ($q) use ($dateStart) {
                $q->whereDate('created_at', '>=', $dateStart);
            })
            ->when($dateWhen !== '', function ($q) use ($dateWhen) {
                $rangeDate = match ($dateWhen) {
                    'day' => now()->subDay(),
                    'week' => now()->subWeek(),
                    'month' => now()->subMonth(),
                    'year' => now()->subYear(),
                    default => null,
                };
                if ($rangeDate) {
                    $q->whereDate('created_at', '>=', $rangeDate);
                }
            })
            ->orderBy('title', $sortDirection) // Sorting berdasarkan judul A-Z atau Z-A
            ->paginate($limit, ['*'], 'page', $page)
            ->toArray();

        $this->loading = false;

    } catch (\Throwable $th) {
        Log::error($th);
        $this->error = __('content.not_found'); // pastikan ada key ini di file terjemahan
    }
}

    public function allCategory()
    {
        try {
            $data = Category::all();
            if ($data->isEmpty()) {
                return [
                    'error' => false,
                ];
            } else {
                return [
                    'data' => $data->toArray(),
                    'error' => true,
                ];
            }
        } catch (\Throwable $th) {
            $this->dispatch('failed', [
                'message' => __('news.failed_category'),
            ]);
            return [
                'error' => false,
            ];
        }
    }
}; ?>

<div>
    @push('meta')
        <meta name="keywords" content="universitas, pendidikan, Medan, kampus, unimed, mahasiswa, akademik">
        <meta name="description"
            content="Website resmi Universitas Negeri Medan - informasi akademik, berita kampus, dan layanan mahasiswa.">
    @endpush
    @vite(['resources/js/moment.js'])

    <div x-data="initNewsSearch" x-init="initSearch">
        <div x-data="{
            image: false,
            init() {
                const img = new Image();
                img.src = '{{ asset('img/bg.jpg') }}';
                img.onload = () => {
                    this.image = true;
                };
            },
        }" class="relative overflow-hidden">
            <div x-show="image" class="animate-fade absolute left-0 top-0 z-10 h-full w-full">
                <div class="relative flex h-full w-full items-center justify-center">
                    <img src="{{ asset('img/bg.jpg') }}"
                        class="absolute inset-0 left-0 top-0 z-10 h-full w-full object-cover" />
                    <div class="absolute inset-0 left-0 top-0 z-10 bg-black/60"></div>
                    <h1 x-cloak
                        class="text-accent-white ftnews-1:text-3xl relative z-30 text-center text-xl font-bold md:text-5xl">
                        {{ __('news.portal_news_tenik') }}
                    </h1>
                </div>
            </div>
            <div
                class="flex aspect-video max-h-[420px] animate-pulse items-center justify-center rounded-lg bg-gray-300 dark:bg-gray-700">
                <svg class="h-10 w-10 text-gray-200 dark:text-gray-600" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                    <path
                        d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <div
            class="bg-accent-white nav-3:top-[60px] nav-2:top-[100px] linked-1:h-[73px] sticky top-[65px] z-40 h-[65px] w-full border-2 border-gray-100 py-2">
            <div class="mx-auto flex max-w-[var(--max-width)] flex-row justify-between px-10">
                <div class="linked-1:flex hidden w-full flex-row flex-wrap justify-center gap-x-4 gap-y-3">
                    <div class="relative flex h-[53px] flex-row gap-x-2 rounded-xl border border-gray-400 p-2">
                        <div class="rounded-xl border border-gray-400 p-1">
                            <svg class="w-[25px] rotate-90 text-gray-400" viewBox="0 -0.5 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.30524 15.7137C6.4404 14.8306 5.85381 13.7131 5.61824 12.4997C5.38072 11.2829 5.50269 10.0233 5.96924 8.87469C6.43181 7.73253 7.22153 6.75251 8.23924 6.05769C10.3041 4.64744 13.0224 4.64744 15.0872 6.05769C16.105 6.75251 16.8947 7.73253 17.3572 8.87469C17.8238 10.0233 17.9458 11.2829 17.7082 12.4997C17.4727 13.7131 16.8861 14.8306 16.0212 15.7137C14.8759 16.889 13.3044 17.5519 11.6632 17.5519C10.0221 17.5519 8.45059 16.889 7.30524 15.7137V15.7137Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M11.6702 7.20292C11.2583 7.24656 10.9598 7.61586 11.0034 8.02777C11.0471 8.43968 11.4164 8.73821 11.8283 8.69457L11.6702 7.20292ZM13.5216 9.69213C13.6831 10.0736 14.1232 10.2519 14.5047 10.0904C14.8861 9.92892 15.0644 9.4888 14.9029 9.10736L13.5216 9.69213ZM16.6421 15.0869C16.349 14.7943 15.8741 14.7947 15.5815 15.0879C15.2888 15.381 15.2893 15.8559 15.5824 16.1485L16.6421 15.0869ZM18.9704 19.5305C19.2636 19.8232 19.7384 19.8228 20.0311 19.5296C20.3237 19.2364 20.3233 18.7616 20.0301 18.4689L18.9704 19.5305ZM11.8283 8.69457C12.5508 8.61801 13.2384 9.02306 13.5216 9.69213L14.9029 9.10736C14.3622 7.83005 13.0496 7.05676 11.6702 7.20292L11.8283 8.69457ZM15.5824 16.1485L18.9704 19.5305L20.0301 18.4689L16.6421 15.0869L15.5824 16.1485Z"
                                        fill="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <input type="text" x-model.debounce.500="search" class="text-primary focus:outline-none"
                            placeholder="{{ __('news.search_news') }}" />
                        <button
                            class="absolute right-1 top-1/2 -translate-y-1/2 cursor-pointer rounded-md p-2 text-gray-500 hover:bg-gray-100"
                            @click="search=''">
                            <svg class="h-[20px] w-[20px]" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path fill="currentColor"
                                        d="M195.2 195.2a64 64 0 0 1 90.496 0L512 421.504 738.304 195.2a64 64 0 0 1 90.496 90.496L602.496 512 828.8 738.304a64 64 0 0 1-90.496 90.496L512 602.496 285.696 828.8a64 64 0 0 1-90.496-90.496L421.504 512 195.2 285.696a64 64 0 0 1 0-90.496z">
                                    </path>
                                </g>
                            </svg>
                        </button>
                    </div>
                    <div class="relative" x-data="{
                        opened: false,
                        clicked(val) {
                            sort = val;
                            this.opened = false;
                        }
                    }" @click.away="opened=false">
                        <div @click="opened=!opened"
                            class="flex cursor-pointer select-none flex-row items-center gap-x-1 rounded-xl border border-gray-400 p-2 transition-all hover:bg-gray-100">
                            <div class="rounded-xl border border-gray-400 p-1 text-gray-400">
                                <svg class="w-[25px]" fill="currentColor" x-show="sort == 'asc'" viewBox="0 0 32 32"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M30,11.67H29L25.71.71s0,0-.05-.08a.61.61,0,0,0-.09-.18c0-.06-.08-.1-.12-.15L25.31.19a.69.69,0,0,0-.19-.1L25,0h-.58l-.08,0a.69.69,0,0,0-.19.1.69.69,0,0,0-.13.11l-.13.15a1,1,0,0,0-.09.18s0,.05-.05.08l-3.28,11h-1a1,1,0,0,0,0,2H23a1,1,0,0,0,0-2h-.41l.9-3H26l.9,3H26.5a1,1,0,0,0,0,2H30a1,1,0,0,0,0-2Zm-5.91-5,.66-2.19.66,2.19Z">
                                        </path>
                                        <path
                                            d="M7.25,0a1,1,0,0,0-1,1V28.67l-3.56-3.4a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41l5.25,5c0,.05.1.06.15.1a.86.86,0,0,0,.16.1.94.94,0,0,0,.76,0,1.51,1.51,0,0,0,.17-.1s.1-.06.14-.1l5.25-5a1,1,0,0,0,0-1.41,1,1,0,0,0-1.42,0l-3.56,3.4V1A1,1,0,0,0,7.25,0Z">
                                        </path>
                                        <path
                                            d="M30,28.33a1,1,0,0,0-1,1V30H21.75l9-10a1,1,0,0,0,.17-1.07,1,1,0,0,0-.91-.6H19.5a1,1,0,0,0-1,1V21a1,1,0,0,0,2,0v-.67h7.26l-9,10A1,1,0,0,0,19.5,32H30a1,1,0,0,0,1-1V29.33A1,1,0,0,0,30,28.33Z">
                                        </path>
                                    </g>
                                </svg>
                                <svg class="w-[25px]" fill="currentColor" x-show="sort == 'desc'" viewBox="0 0 32 32"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M30,29.88H29L25.71,18.93s0-.06-.05-.09a.76.76,0,0,0-.09-.18l-.12-.14-.14-.12-.19-.1-.08,0H25l-.2,0-.2,0h-.09l-.08,0-.19.1a.74.74,0,0,0-.13.12.64.64,0,0,0-.13.15.91.91,0,0,0-.09.17s0,.05-.05.09l-3.28,11h-1a1,1,0,0,0,0,2H23a1,1,0,0,0,0-2h-.41l.9-3H26l.9,3H26.5a1,1,0,0,0,0,2H30a1,1,0,0,0,0-2Zm-5.91-5,.66-2.18.66,2.18Z">
                                        </path>
                                        <path
                                            d="M2.69,6.72,6.25,3.33V31a1,1,0,0,0,2,0V3.33l3.56,3.39A1,1,0,0,0,12.5,7a1,1,0,0,0,.73-.31,1,1,0,0,0,0-1.42L7.94.27A1.1,1.1,0,0,0,7.8.18a1.51,1.51,0,0,0-.17-.1,1,1,0,0,0-.76,0,.86.86,0,0,0-.16.1.75.75,0,0,0-.15.09l-5.25,5A1,1,0,1,0,2.69,6.72Z">
                                        </path>
                                        <path
                                            d="M30,10.12a1,1,0,0,0-1,1v.67H21.75l9-10A1,1,0,0,0,30.91.71,1,1,0,0,0,30,.12H19.5a1,1,0,0,0-1,1V2.79a1,1,0,0,0,2,0V2.12h7.26l-9,10a1,1,0,0,0-.17,1.07,1,1,0,0,0,.91.6H30a1,1,0,0,0,1-1V11.12A1,1,0,0,0,30,10.12Z">
                                        </path>
                                    </g>
                                </svg>
                            </div>
                            <p class="text-gray-400">{{ __('news.sort') }}: </p>
                            <p class="text-gray-400" x-show="sort == 'asc'">{{ __('news.asc') }}</p>
                            <p class="text-gray-400" x-show="sort == 'desc'">{{ __('news.desc') }}</p>
                        </div>
                        <div class="bg-accent-white absolute -bottom-[95px] flex w-full flex-col items-center justify-center gap-y-2 rounded-xl border border-gray-400 p-2"
                            x-show="opened" x-transition>
                            <p @click="clicked('asc')" class="w-full rounded-xl py-1 text-center"
                                x-bind:class="sort == 'asc' ? 'bg-gray-200 text-gray-600' :
                                    'hover:bg-gray-200 text-gray-600 cursor-pointer'">
                                {{ __('news.asc') }}</p>
                            <p @click="clicked('desc')" class="w-full rounded-xl py-1 text-center"
                                x-bind:class="sort == 'desc' ? 'bg-gray-200 text-gray-600' :
                                    'hover:bg-gray-200 text-gray-600 cursor-pointer'">
                                {{ __('news.desc') }}</p>
                        </div>
                    </div>
                    <div x-data="{
                        openedCat: false,
                        message: '{{ __('news.search_category') }}',
                        categories: allCategory,
                        searchCat: '',
                        initFirst: false,
                        initFilter() {
                            if (this.initFirst) return;
                            this.initFirst = true;
                            this.$watch('searchCat', (val) => {
                                this.categoryFilter(val);
                            });
                        },
                        categoryFilter(val) {
                            if (val.length > 0) {
                                this.categories = allCategory.filter((item) => {
                                    return item.name.toLowerCase().includes(val.toLowerCase());
                                });
                                console.log(this.categories)
                            } else {
                                this.categories = allCategory;
                            }
                        },
                        clickedCat(val) {
                            try {
                                this.openedCat = false;
                                category = val;
                                this.searchCat = '';
                            } catch (error) {
                                console.error(error);
                            }

                        }
                    }"
                        class="bg-accent-white border-accent-white overflow-hidden rounded-xl border"
                        x-bind:class="openedCat ? 'border-gray-400' : ''" @click.away="openedCat=false">
                        <div x-init="initFilter" @click="openedCat=!openedCat"
                            class="relative flex w-[250px] flex-row items-center gap-x-2 rounded-xl border border-gray-400 p-2">
                            <div class="rounded-xl border border-gray-400 p-1">
                                <svg class="w-[25px] rotate-90 text-gray-400" fill="currentColor" viewBox="0 0 24 24"
                                    data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <title></title>
                                        <path
                                            d="M16,17.9a5,5,0,0,0,1.75-.73l3.54,3.54,1.41-1.41-3.54-3.54A5,5,0,0,0,16,8.1V3H6.59L2,7.59V21H16ZM18,13a3,3,0,1,1-3-3A3,3,0,0,1,18,13ZM4,19V8.41L7.41,5H14V8.1A5,5,0,0,0,11,10H6v2h4.1a5,5,0,0,0,0,2H6v2h5v0a5,5,0,0,0,3,1.93V19Z">
                                        </path>
                                    </g>
                                </svg>
                            </div>
                            <p x-text="category ? category : message"
                                x-bind:class="category ? 'text-primary' : 'text-gray-400'"></p>
                            <button
                                class="absolute right-1 top-1/2 -translate-y-1/2 cursor-pointer rounded-md p-2 text-gray-500 hover:bg-gray-100"
                                @click="category=''">
                                <svg class="h-[20px] w-[20px]" viewBox="0 0 1024 1024"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill="currentColor"
                                            d="M195.2 195.2a64 64 0 0 1 90.496 0L512 421.504 738.304 195.2a64 64 0 0 1 90.496 90.496L602.496 512 828.8 738.304a64 64 0 0 1-90.496 90.496L512 602.496 285.696 828.8a64 64 0 0 1-90.496-90.496L421.504 512 195.2 285.696a64 64 0 0 1 0-90.496z">
                                        </path>
                                    </g>
                                </svg>
                            </button>
                        </div>
                        <div x-show="openedCat" class="max-h-[250px] overflow-y-auto px-2 pb-2">
                            <div class="border-primary-light mb-2 border-b px-2 py-2">
                                <input type="text" x-model="searchCat" class="text-primary focus:outline-none"
                                    placeholder="{{ __('news.search_category') }}" />
                            </div>
                            <div>
                                <template x-if="categories && categories.length > 0">
                                    <template x-for="(data, i) in categories" :key="i">
                                        <p x-text="data.name" @click="clickedCat(data.name)"
                                            class="text-primary-dark hover:bg-primary-light/20 w-full cursor-pointer rounded-md px-2 py-1 transition-colors">
                                        </p>
                                    </template>
                                </template>
                                <template x-if="!categories || categories.length === 0">
                                    <p class="text-primary-dark w-full rounded-md px-2 py-1 transition-colors">Tidak
                                        ada kategori.</p>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div @click="$dispatch('searching-filter')"
                    class="animate-fade bg-accent-white linked-1:hidden flex cursor-pointer flex-row items-center gap-x-1 rounded-md border border-gray-400 py-1 pl-1 pr-3 transition-all hover:bg-gray-100">
                    <div class="rounded-xl border border-gray-400 p-1">
                        <svg class="w-[25px] rotate-90 text-gray-400" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M21 6H19M21 12H16M21 18H16M7 20V13.5612C7 13.3532 7 13.2492 6.97958 13.1497C6.96147 13.0615 6.93151 12.9761 6.89052 12.8958C6.84431 12.8054 6.77934 12.7242 6.64939 12.5617L3.35061 8.43826C3.22066 8.27583 3.15569 8.19461 3.10948 8.10417C3.06849 8.02393 3.03853 7.93852 3.02042 7.85026C3 7.75078 3 7.64677 3 7.43875V5.6C3 5.03995 3 4.75992 3.10899 4.54601C3.20487 4.35785 3.35785 4.20487 3.54601 4.10899C3.75992 4 4.03995 4 4.6 4H13.4C13.9601 4 14.2401 4 14.454 4.10899C14.6422 4.20487 14.7951 4.35785 14.891 4.54601C15 4.75992 15 5.03995 15 5.6V7.43875C15 7.64677 15 7.75078 14.9796 7.85026C14.9615 7.93852 14.9315 8.02393 14.8905 8.10417C14.8443 8.19461 14.7793 8.27583 14.6494 8.43826L11.3506 12.5617C11.2207 12.7242 11.1557 12.8054 11.1095 12.8958C11.0685 12.9761 11.0385 13.0615 11.0204 13.1497C11 13.2492 11 13.3532 11 13.5612V17L7 20Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </g>
                        </svg>
                    </div>
                    <p class="text-gray-500">{{ __('news.search') }}</p>
                </div>

            </div>
        </div>

        <div class="mx-auto flex max-w-[var(--max-width)] flex-row items-start ftnews-1:px-10 px-0">
            <div class="sticky top-[160px] hidden h-full xl:block">
                <livewire:component.news-recomendation />
            </div>
            <div class="mb-10 mt-5 w-full select-none" x-ref="box">
                <div class="flex w-full flex-wrap justify-center gap-5 text-center">
                    <template x-if="datas && Array.isArray(datas.data) && datas.data.length > 0 && !loading">
                        <template x-for="(data, index) in datas.data" :key="index">
                            <div x-data="{ hovered: false }"
                                class="relative aspect-[16/9] ftnews-1:w-[405px] w-full cursor-pointer overflow-hidden ftnews-1:rounded-md rounded-none"
                                @click="goToNews(data.id)" @mouseenter="hovered=true" @mouseleave="hovered=false">
                                <img x-bind:class="hovered ? 'scale-110' : 'scale-100'" x-bind:src="`/storage/${data.image}`"
                                    alt="data.title"
                                    class="absolute inset-0 left-0 top-0 h-full w-full object-cover transition-all" />
                                <div x-bind:class="hovered ? 'opacity-0' : 'opacity-100'"
                                    class="absolute bottom-0 left-0 right-0 z-10 h-full bg-gradient-to-t from-black/80 to-black/10 transition-all">
                                </div>

                                <div x-bind:class="hovered ? 'opacity-0' : 'opacity-100'"
                                    class="absolute bottom-2 right-0 z-20 flex h-[85px] w-full flex-col justify-between px-2 text-center shadow-xl transition-all">
                                    <h3 x-text="data.title"
                                        class="text-accent-white font-semi-bold line-clamp-2 text-xl">
                                    </h3>
                                    <div class="flex flex-row items-center justify-start gap-x-1 text-[10px]">
                                        <p class="text-accent-white">{{ __('news.posted') }}</p>
                                        <p x-text="changeDate(data.created_at)" class="text-accent-white"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>

                    <template x-cloak
                        x-if="!loading && (!datas || (Array.isArray(datas.data) && datas.data.length == 0))">
                        <p class="mt-10 w-full text-center text-lg text-gray-400">
                            {{ __('news.not_found') }}
                        </p>
                    </template>

                    <template x-cloak x-if="loading">
                        <template x-for="i in skeletonLimit" :key="i">
                            <div class="relative">
                                <div
                                    class="flex h-64 w-[405px] items-center justify-center rounded-sm bg-gray-100 dark:bg-gray-700">
                                    <svg class="h-10 w-10 text-gray-200 dark:text-gray-600" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                        <path
                                            d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                                    </svg>
                                </div>
                                <div
                                    class="absolute bottom-3 right-1 z-10 mt-2 h-5 w-[360px] rounded-full bg-gray-300 dark:bg-gray-700">
                                </div>
                            </div>
                        </template>
                    </template>

                </div>

                <div class="mt-10 flex justify-center">
                    <template x-if="pagination && pagination.length > 0">
                        <div class="animate-fade inline-flex h-10 -space-x-px text-center text-base">
                            <button
                                @click.prevent="datas.current_page == 1 ? '' : page = page - 1"
                                class="ms-0 flex h-10 items-center justify-center rounded-s-lg border border-e-0 border-gray-300 px-4 leading-tight"
                                x-bind:class="datas.current_page == 1 ? 'cursor-not-allowed text-gray-700 bg-gray-100' :
                                    'cursor-pointer hover:bg-gray-100 hover:text-gray-700 bg-white text-gray-500'">
                                <span class="sr-only">Previous</span>
                                <svg class="h-3 w-3 rtl:rotate-180" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 1 1 5l4 4" />
                                </svg>
                            </button>
                            <template x-for="(val, key) in pagination" :key="key">
                                <button @click.prevent="page !== val && (page = val)" x-text="val"
                                    class="flex h-10 items-center justify-center px-4 leading-tight transition-all"
                                    x-bind:class="datas.current_page == val ?
                                        'cursor-not-allowed text-primary/60 hover:text-primary bg-green-100 hover:bg-green-200 border border-green-300' :
                                        'cursor-pointer text-gray-500 hover:text-gray-700 bg-white hover:bg-gray-100 border border-gray-300'">
                                </button>
                            </template>
                            <button @click.prevent="datas.current_page == datas.last_page ? '' : page = page + 1"
                                class="flex h-10 items-center justify-center rounded-e-lg border border-gray-300 px-4 leading-tight"  x-bind:class="datas.current_page == datas.last_page ? 'cursor-not-allowed text-gray-700 bg-gray-100' :
                                    'cursor-pointer hover:bg-gray-100 hover:text-gray-700 bg-white text-gray-500'">
                                <span class="sr-only">Next</span>
                                <svg class="h-3 w-3 rtl:rotate-180" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <nav x-cloak x-data="{ shown: false, haveOne: '' }" x-on:searching-filter.window="(event) => {
        shown = true;
    }"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0 bg-secondary-accent/0"
            x-transition:enter-end="translate-x-0 opacity-100 bg-secondary-accent/30"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0"
            class="bg-secondary-accent/30 fixed inset-0 z-50 flex justify-end" x-show="shown">
            <div class="bg-accent-white relative h-full max-w-[300px] overflow-y-auto px-3" @click.away="shown=false">
                <h2 class="text-primary mt-16 text-right text-xl font-bold">{{ __('news.search') }}</h2>
                <div class="relative mt-5 flex h-[53px] flex-row gap-x-2 rounded-xl border border-gray-400 p-2">
                    <div class="rounded-xl border border-gray-400 p-1">
                        <svg class="w-[25px] rotate-90 text-gray-400" viewBox="0 -0.5 25 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.30524 15.7137C6.4404 14.8306 5.85381 13.7131 5.61824 12.4997C5.38072 11.2829 5.50269 10.0233 5.96924 8.87469C6.43181 7.73253 7.22153 6.75251 8.23924 6.05769C10.3041 4.64744 13.0224 4.64744 15.0872 6.05769C16.105 6.75251 16.8947 7.73253 17.3572 8.87469C17.8238 10.0233 17.9458 11.2829 17.7082 12.4997C17.4727 13.7131 16.8861 14.8306 16.0212 15.7137C14.8759 16.889 13.3044 17.5519 11.6632 17.5519C10.0221 17.5519 8.45059 16.889 7.30524 15.7137V15.7137Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path
                                    d="M11.6702 7.20292C11.2583 7.24656 10.9598 7.61586 11.0034 8.02777C11.0471 8.43968 11.4164 8.73821 11.8283 8.69457L11.6702 7.20292ZM13.5216 9.69213C13.6831 10.0736 14.1232 10.2519 14.5047 10.0904C14.8861 9.92892 15.0644 9.4888 14.9029 9.10736L13.5216 9.69213ZM16.6421 15.0869C16.349 14.7943 15.8741 14.7947 15.5815 15.0879C15.2888 15.381 15.2893 15.8559 15.5824 16.1485L16.6421 15.0869ZM18.9704 19.5305C19.2636 19.8232 19.7384 19.8228 20.0311 19.5296C20.3237 19.2364 20.3233 18.7616 20.0301 18.4689L18.9704 19.5305ZM11.8283 8.69457C12.5508 8.61801 13.2384 9.02306 13.5216 9.69213L14.9029 9.10736C14.3622 7.83005 13.0496 7.05676 11.6702 7.20292L11.8283 8.69457ZM15.5824 16.1485L18.9704 19.5305L20.0301 18.4689L16.6421 15.0869L15.5824 16.1485Z"
                                    fill="currentColor"></path>
                            </g>
                        </svg>
                    </div>
                    <input type="text" x-model.debounce.500="search" class="text-primary focus:outline-none"
                        placeholder="{{ __('news.search_news') }}" />
                    <button
                        class="absolute right-1 top-1/2 -translate-y-1/2 cursor-pointer rounded-md p-2 text-gray-500 hover:bg-gray-100"
                        @click="search=''">
                        <svg class="h-[20px] w-[20px]" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill="currentColor"
                                    d="M195.2 195.2a64 64 0 0 1 90.496 0L512 421.504 738.304 195.2a64 64 0 0 1 90.496 90.496L602.496 512 828.8 738.304a64 64 0 0 1-90.496 90.496L512 602.496 285.696 828.8a64 64 0 0 1-90.496-90.496L421.504 512 195.2 285.696a64 64 0 0 1 0-90.496z">
                                </path>
                            </g>
                        </svg>
                    </button>
                </div>
                <div class="relative mt-5" x-data="{
                    opened: false,
                    clicked(val) {
                        sort = val;
                        this.opened = false;
                    }
                }" @click.away="opened=false">
                    <div @click="opened=!opened"
                        class="flex cursor-pointer select-none flex-row items-center gap-x-1 rounded-xl border border-gray-400 p-2 transition-all hover:bg-gray-100">
                        <div class="rounded-xl border border-gray-400 p-1 text-gray-400">
                            <svg class="w-[25px]" fill="currentColor" x-show="sort == 'asc'" viewBox="0 0 32 32"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M30,11.67H29L25.71.71s0,0-.05-.08a.61.61,0,0,0-.09-.18c0-.06-.08-.1-.12-.15L25.31.19a.69.69,0,0,0-.19-.1L25,0h-.58l-.08,0a.69.69,0,0,0-.19.1.69.69,0,0,0-.13.11l-.13.15a1,1,0,0,0-.09.18s0,.05-.05.08l-3.28,11h-1a1,1,0,0,0,0,2H23a1,1,0,0,0,0-2h-.41l.9-3H26l.9,3H26.5a1,1,0,0,0,0,2H30a1,1,0,0,0,0-2Zm-5.91-5,.66-2.19.66,2.19Z">
                                    </path>
                                    <path
                                        d="M7.25,0a1,1,0,0,0-1,1V28.67l-3.56-3.4a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41l5.25,5c0,.05.1.06.15.1a.86.86,0,0,0,.16.1.94.94,0,0,0,.76,0,1.51,1.51,0,0,0,.17-.1s.1-.06.14-.1l5.25-5a1,1,0,0,0,0-1.41,1,1,0,0,0-1.42,0l-3.56,3.4V1A1,1,0,0,0,7.25,0Z">
                                    </path>
                                    <path
                                        d="M30,28.33a1,1,0,0,0-1,1V30H21.75l9-10a1,1,0,0,0,.17-1.07,1,1,0,0,0-.91-.6H19.5a1,1,0,0,0-1,1V21a1,1,0,0,0,2,0v-.67h7.26l-9,10A1,1,0,0,0,19.5,32H30a1,1,0,0,0,1-1V29.33A1,1,0,0,0,30,28.33Z">
                                    </path>
                                </g>
                            </svg>
                            <svg class="w-[25px]" fill="currentColor" x-show="sort == 'desc'" viewBox="0 0 32 32"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M30,29.88H29L25.71,18.93s0-.06-.05-.09a.76.76,0,0,0-.09-.18l-.12-.14-.14-.12-.19-.1-.08,0H25l-.2,0-.2,0h-.09l-.08,0-.19.1a.74.74,0,0,0-.13.12.64.64,0,0,0-.13.15.91.91,0,0,0-.09.17s0,.05-.05.09l-3.28,11h-1a1,1,0,0,0,0,2H23a1,1,0,0,0,0-2h-.41l.9-3H26l.9,3H26.5a1,1,0,0,0,0,2H30a1,1,0,0,0,0-2Zm-5.91-5,.66-2.18.66,2.18Z">
                                    </path>
                                    <path
                                        d="M2.69,6.72,6.25,3.33V31a1,1,0,0,0,2,0V3.33l3.56,3.39A1,1,0,0,0,12.5,7a1,1,0,0,0,.73-.31,1,1,0,0,0,0-1.42L7.94.27A1.1,1.1,0,0,0,7.8.18a1.51,1.51,0,0,0-.17-.1,1,1,0,0,0-.76,0,.86.86,0,0,0-.16.1.75.75,0,0,0-.15.09l-5.25,5A1,1,0,1,0,2.69,6.72Z">
                                    </path>
                                    <path
                                        d="M30,10.12a1,1,0,0,0-1,1v.67H21.75l9-10A1,1,0,0,0,30.91.71,1,1,0,0,0,30,.12H19.5a1,1,0,0,0-1,1V2.79a1,1,0,0,0,2,0V2.12h7.26l-9,10a1,1,0,0,0-.17,1.07,1,1,0,0,0,.91.6H30a1,1,0,0,0,1-1V11.12A1,1,0,0,0,30,10.12Z">
                                    </path>
                                </g>
                            </svg>
                        </div>
                        <p class="text-gray-400">{{ __('news.sort') }}: </p>
                        <p class="text-gray-400" x-show="sort == 'asc'">{{ __('news.asc') }}</p>
                        <p class="text-gray-400" x-show="sort == 'desc'">{{ __('news.desc') }}</p>
                    </div>
                    <div class="bg-accent-white flex w-full flex-col items-center justify-center gap-y-2 rounded-xl border border-gray-400 p-2"
                        x-show="opened" x-transition>
                        <p @click="clicked('asc')" class="w-full rounded-xl py-1 text-center"
                            x-bind:class="sort == 'asc' ? 'bg-gray-200 text-gray-600' :
                                'hover:bg-gray-200 text-gray-600 cursor-pointer'">
                            {{ __('news.asc') }}</p>
                        <p @click="clicked('desc')" class="w-full rounded-xl py-1 text-center"
                            x-bind:class="sort == 'desc' ? 'bg-gray-200 text-gray-600' :
                                'hover:bg-gray-200 text-gray-600 cursor-pointer'">
                            {{ __('news.desc') }}</p>
                    </div>
                </div>
                <div x-data="{
                    openedCat: false,
                    message: '{{ __('news.search_category') }}',
                    categories: allCategory,
                    searchCat: '',
                    initFirst: false,
                    initFilter() {
                        if (this.initFirst) return;
                        this.initFirst = true;
                        this.$watch('searchCat', (val) => {
                            this.categoryFilter(val);
                        });
                    },
                    categoryFilter(val) {
                        if (val.length > 0) {
                            this.categories = allCategory.filter((item) => {
                                return item.name.toLowerCase().includes(val.toLowerCase());
                            });
                            console.log(this.categories)
                        } else {
                            this.categories = allCategory;
                        }
                    },
                    clickedCat(val) {
                        try {
                            this.openedCat = false;
                            category = val;
                            this.searchCat = '';
                        } catch (error) {
                            console.error('Error in clickedCat:', error);
                        }

                    }
                }"
                    class="bg-accent-white border-accent-white mt-5 select-none overflow-hidden rounded-xl border"
                    x-bind:class="openedCat ? 'border-gray-400' : ''" @click.away="openedCat=false">
                    <div x-init="initFilter" @click="openedCat=!openedCat"
                        class="relative flex w-full flex-row items-center gap-x-2 rounded-xl border border-gray-400 p-2">
                        <div class="rounded-xl border border-gray-400 p-1">
                            <svg class="w-[25px] rotate-90 text-gray-400" fill="currentColor" viewBox="0 0 24 24"
                                data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <title></title>
                                    <path
                                        d="M16,17.9a5,5,0,0,0,1.75-.73l3.54,3.54,1.41-1.41-3.54-3.54A5,5,0,0,0,16,8.1V3H6.59L2,7.59V21H16ZM18,13a3,3,0,1,1-3-3A3,3,0,0,1,18,13ZM4,19V8.41L7.41,5H14V8.1A5,5,0,0,0,11,10H6v2h4.1a5,5,0,0,0,0,2H6v2h5v0a5,5,0,0,0,3,1.93V19Z">
                                    </path>
                                </g>
                            </svg>
                        </div>
                        <p x-text="category ? category : message"
                            x-bind:class="category ? 'text-primary' : 'text-gray-400'"></p>
                        <button
                            class="absolute right-1 top-1/2 -translate-y-1/2 cursor-pointer rounded-md p-2 text-gray-500 hover:bg-gray-100"
                            @click="category=''">
                            <svg class="h-[20px] w-[20px]" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path fill="currentColor"
                                        d="M195.2 195.2a64 64 0 0 1 90.496 0L512 421.504 738.304 195.2a64 64 0 0 1 90.496 90.496L602.496 512 828.8 738.304a64 64 0 0 1-90.496 90.496L512 602.496 285.696 828.8a64 64 0 0 1-90.496-90.496L421.504 512 195.2 285.696a64 64 0 0 1 0-90.496z">
                                    </path>
                                </g>
                            </svg>
                        </button>
                    </div>
                    <div x-show="openedCat" class="max-h-[250px] overflow-y-auto px-2 pb-2">
                        <div class="border-primary-light mb-2 border-b px-2 py-2">
                            <input type="text" x-model="searchCat" class="text-primary focus:outline-none"
                                placeholder="{{ __('news.search_category') }}" />
                        </div>
                        <div>
                            <template x-if="categories && categories.length > 0">
                                <template x-for="(data, i) in categories" :key="i">
                                    <p x-text="data.name" @click="clickedCat(data.name)"
                                        class="text-primary-dark hover:bg-primary-light/20 w-full cursor-pointer rounded-md px-2 py-1 transition-colors">
                                    </p>
                                </template>
                            </template>
                            <template x-if="!categories || categories.length === 0">
                                <p class="text-primary-dark w-full rounded-md px-2 py-1 transition-colors">Tidak
                                    ada kategori.</p>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="hover:text-secondary-warn absolute right-5 top-5 cursor-pointer text-red-500 transition-all hover:rotate-90"
                    @click="shown=false">
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
            </div>
        </nav>
    </div>
</div>
<script>
    function initNewsSearch() {
        return {
            search: @entangle('search').live,
            sort: @entangle('sort').live,
            category: @entangle('category').live,
            dateStart: @entangle('dateStart').live,
            dateWhen: @entangle('dateWhen').live,
            page: @entangle('page').live,
            allCategory: null,
            initStop: false,
            datas: @entangle('data').live,
            loading: @entangle('loading').live,
            limit: 0,
            skeletonLimit: [],
            pagination: [],
            getPagination(currentPage, lastPage) {
                const delta = 2;
                let start = Math.max(1, currentPage - delta);
                let end = Math.min(lastPage, currentPage + delta);
                while (end - start < 4) {
                    if (start > 1) {
                        start--;
                    } else if (end < lastPage) {
                        end++;
                    } else {
                        break;
                    }
                }
                const pages = [];
                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }
                return pages;
            },
            calculate() {
                const wrapper = this.$refs.box;
                const wrapperWidth = wrapper.offsetWidth;
                const totalPerItem = 405 + 10;
                const rawLimit = Math.floor((wrapperWidth + 10) / totalPerItem);
                this.limit = Math.max(rawLimit, 1) * 5;
                this.skeletonLimit = Array.from({
                    length: this.limit
                }, (_, i) => i + 1)
            },
            goToNews(id) {
                const dummy = '{{ route('news-page', ['id' => '__DUMMY_ID__']) }}'.replace('__DUMMY_ID__', id);
                goToPage(dummy);
            },
            initSearch() {
                if (this.initStop) return;
                this.initStop = true;
                this.initCategory();
                this.calculate();
                this.$watch('datas', (value) => {
                    if (
                        value &&
                        Array.isArray(value.data) &&
                        value.data.length > 0 &&
                        typeof value.current_page === 'number' &&
                        typeof value.last_page === 'number'
                    ) {
                        const tempPagination = this.getPagination(value.current_page, value.last_page);
                        Alpine.nextTick(() => {
                            this.pagination = tempPagination;
                        });
                    } else {
                        this.pagination = [];
                    }

                });
                this.fullSearch();
                this.$watch('category', (value) => {
                    this.addParameter('category', value);
                });
                this.$watch('page', (value) => {
                    this.addParameter('page', value);
                });
                this.$watch('sort', (value) => {
                    this.addParameter('sort', value);
                });
                this.$watch('search', (value) => {
                    this.addParameter('search', value);
                });
                this.$watch('dateStart', (value) => {
                    this.addParameter('dateStart', value);
                });
                this.$watch('dateWhen', (value) => {
                    this.addParameter('dateWhen', value);
                });
            },
            addParameter(parameter, value) {
                const params = new URLSearchParams(window.location.search);
                if (value) {
                    params.set(parameter, value);
                } else {
                    params.delete(parameter);
                }
                const newUrl = params.toString() ?
                    `${window.location.pathname}?${params.toString()}` :
                    window.location.pathname;

                window.history.replaceState({}, '', newUrl);
                this.fullSearch();
            },
            initCategory() {
                const cachedCategory = sessionStorage.getItem('allCategory');

                if (cachedCategory) {
                    this.allCategory = JSON.parse(cachedCategory);
                    return;
                }

                this.$wire.allCategory().then((res) => {
                    if (res.error && res.data) {
                        this.allCategory = res.data;
                        sessionStorage.setItem('allCategory', JSON.stringify(res.data));
                    } else {
                        this.allCategory = null;
                    }
                });
            },
            fullSearch() {
                this.$wire.fullSearch(
                    this.search,
                    this.sort,
                    this.page,
                    this.limit,
                    this.category,
                    this.dateStart,
                    this.dateWhen
                );
            },
            changeDate(createdAt) {
                const formattedTime = moment(createdAt).fromNow();
                return formattedTime;
            }

        }
    }
</script>
