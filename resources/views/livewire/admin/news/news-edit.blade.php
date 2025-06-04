<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\News;
use App\Models\NewsTranslations;
use Illuminate\Support\Facades\Log;
use App\Models\Categories;
use App\Models\Category_news;

new #[Layout('components.layouts.edit')] class extends Component {
    public $pageId;
    public $error;
    public $data;
    public $category = [];
    public $saveCategory = [];

    public function mount($id)
    {
        $this->pageId = $id;
        $this->getData();
        $this->saveUserCategory();
    }

    public function getData()
    {
        try {
            $locale = app()->getLocale();
            $data = News::where('id', $this->pageId)
                ->with([
                    'translations' => function ($query) use ($locale) {
                        $query->where('locale', $locale);
                    },
                ])
                ->first();

            if (!$data) {
                Log::info('News not found', ['id' => $this->pageId]);
                $this->error = __('news.news.not_found');
                $this->data = [];
                return;
            }

            $translation = $data->translations->first();

            if (!$translation) {
                $fallbackTranslation = NewsTranslations::where('news_id', $this->pageId)->first();

                if ($fallbackTranslation) {
                    $newTranslation = $fallbackTranslation->replicate();
                    $newTranslation->locale = $locale;
                    $oldPath = $fallbackTranslation->content;
                    if ($oldPath) {
                        if (Storage::exists($oldPath)) {
                            $jsonContent = Storage::get($oldPath);
                            $newPath = 'grapesjs/' . Str::random(20) . '.json';
                            Storage::put($newPath, $jsonContent);
                            $newTranslation->content = $newPath;
                        }
                        $htmlPath = $fallbackTranslation->html;
                        if ($htmlPath) {
                            if (Storage::disk('public')->exists($htmlPath)) {
                                $htmlContent = Storage::disk('public')->get($htmlPath);
                                $newHtmlPath = 'grapesjs_html/' . Str::random(20) . '.html';
                                Storage::disk('public')->put($newHtmlPath, $htmlContent);
                                // Storage::put($newHtmlPath, $htmlContent);
                                $newTranslation->html = $newHtmlPath;
                            }
                        } else {
                            $newTranslation->html = null;
                        }
                        $newTranslation->save();
                        $data = News::where('id', $this->pageId)
                            ->with([
                                'translations' => function ($query) use ($locale) {
                                    $query->where('locale', $locale);
                                },
                            ])
                            ->first();
                        $translation = $data->translations->first();
                        if (!$translation) {
                            $this->error = __('news.news.not_found');
                            $this->data = [];
                            return;
                        }
                        $this->data = $data->toArray();
                    } else {
                        $newTranslation->save();
                        $data = News::where('id', $this->pageId)
                            ->with([
                                'translations' => function ($query) use ($locale) {
                                    $query->where('locale', $locale);
                                },
                            ])
                            ->first();
                        $translation = $data->translations->first();
                        if (!$translation) {
                            $this->error = __('news.news.not_found');
                            $this->data = [];
                            return;
                        }
                    }
                } else {
                    $this->error = __('news.news.not_found');
                    $this->data = [];
                    return;
                }
            }

            $this->data = $data->toArray();
        } catch (\Throwable $th) {
            $this->error = __('news.news.error');
            $this->data = [];
            Log::error('Error fetching news data: ' . $th->getMessage(), [
                'id' => $this->pageId,
                'trace' => $th->getTraceAsString(),
            ]);
        }
    }

    public function addTitle($title)
    {
        try {
            $locale = app()->getLocale();
            $translation = NewsTranslations::where('news_id', $this->pageId)->where('locale', $locale)->first();

            if (!$translation) {
                $translation = new NewsTranslations();
                $translation->news_id = $this->pageId;
                $translation->locale = $locale;
            }

            $translation->title = $title;
            $translation->save();

            $this->getData();
        } catch (\Throwable $th) {
            Log::error('Error adding title: ' . $th->getMessage(), [
                'id' => $this->pageId,
                'trace' => $th->getTraceAsString(),
            ]);
            $this->dispatch('failed', [
                'message' => __('news.news.error'),
            ]);
        }
    }

    public function addPathImage($path)
    {
        try {
            if (!$path) {
                Log::error('Path is empty', ['id' => $this->pageId]);
                $this->dispatch('failed', [
                    'message' => __('news.news.error'),
                ]);
                return;
            }
            News::where('id', $this->pageId)->update([
                'image' => $path,
            ]);
            $this->getData();
        } catch (\Throwable $th) {
            Log::error('Error adding image path: ' . $th->getMessage(), [
                'id' => $this->pageId,
                'trace' => $th->getTraceAsString(),
            ]);
            $this->dispatch('failed', [
                'message' => __('news.news.error'),
            ]);
        }
    }

    public function handlePublish()
    {
        try {
            $data = News::find($this->pageId);
            Log::info('Changing status for news', ['id' => $this->pageId, 'current_status' => $data->status]);
            if (!$data) {
                Log::error('News not found', ['id' => $this->pageId]);
                $this->dispatch('failed', [
                    'message' => __('news.news.not_found'),
                ]);
                return;
            }
            $data->status = $data->status == 'draft' ? 'published' : 'draft';
            $data->save();

            $this->getData();
        } catch (\Throwable $th) {
            Log::error('Error changing status: ' . $th->getMessage(), [
                'id' => $this->pageId,
                'trace' => $th->getTraceAsString(),
            ]);
            $this->dispatch('failed', [
                'message' => __('news.news.error'),
            ]);
        }
    }

    public function getCategory()
    {
        try {
            $locale = app()->getLocale();
            $category = Categories::with([
                'translations' => function ($query) use ($locale) {
                    $query->where('locale', $locale);
                },
            ])->get();
            Log::info('Fetched categories for News', [
                'locale' => $locale,
                'count' => $category->count(),
            ]);
            return $category->toArray();
        } catch (\Throwable $th) {
            Log::error('Error fetching categories: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString(),
            ]);
            $this->dispatch('failed', [
                'message' => __('news.news.error'),
            ]);
        }
    }

    public function saveUserCategory()
    {
        try {
            $locale = app()->getLocale();
            $userCategories = News::with([
                'categories.translations' => function ($query) use ($locale) {
                    $query->where('locale', $locale);
                },
            ])->find($this->pageId)?->categories;

            Log::info('Fetched user categories for News', [
                'id' => $this->pageId,
                'locale' => $locale,
                'count' => $userCategories->count(),
                'data' => $userCategories->toArray(),
            ]);

            if ($userCategories->isEmpty()) {
                Log::info('No user categories found for News');
                $this->saveCategory = [];
                return;
            }

            $this->saveCategory = $userCategories->toArray();
        } catch (\Throwable $th) {
            Log::error('Error fetching user categories: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString(),
            ]);
            $this->dispatch('failed', [
                'message' => __('news.news.error'),
            ]);
        }
    }

    public function addNewsCategory($categoryId)
    {
        try {
            Category_news::create([
                'news_id' => $this->pageId,
                'category_id' => $categoryId,
            ]);
            $this->saveUserCategory();
        } catch (\Throwable $th) {
            Log::error('Error adding category: ' . $th->getMessage(), [
                'id' => $categoryId,
                'trace' => $th->getTraceAsString(),
            ]);
            $this->dispatch('failed', [
                'message' => __('news.add.error.category'),
            ]);
        }
    }

    public function removeCategory($categoryId)
    {
        try {
            Category_news::where('news_id', $this->pageId)
                ->where('category_id', $categoryId)
                ->delete();
            $this->saveUserCategory();
        } catch (\Throwable $th) {
            Log::error('Error removing category: ' . $th->getMessage(), [
                'id' => $categoryId,
                'trace' => $th->getTraceAsString(),
            ]);
            $this->dispatch('failed', [
                'message' => __('news.remove.error.category'),
            ]);
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
    <style>
        .gjs-pn-devices-c {
            left: 50px;
            top: -5px;
        }
    </style>
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
                    <a href="{{ route('news.index') }}" type="button"
                        class="ms-3 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <div x-data="dataNewsEdit" x-init="initNews" class="relative h-screen min-h-[600px] w-full min-w-[600px]">
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
                <a href="{{ route('news.index') }}"
                    class="hover:text-secondary-warn absolute left-5 top-5 cursor-pointer text-blue-500 transition-all">
                    {{ __('news.back') }}
                </a>
                <div class="mt-10 flex flex-row items-center gap-x-5 text-white">
                    <p class="text-primary">{{ __('news.language') }}</p>
                    <div x-cloak x-data="{ locale: '{{ Cookie::get('locale', 'id') }}', lang_id: '{{ route('change.lang', ['lang' => 'id']) }}', lang_en: '{{ route('change.lang', ['lang' => 'en']) }}', enter: false, clicked: false }"
                        @click="window.location.href = locale == 'id' ? lang_en : lang_id; clicked=true"
                        class="nav-3:flex animate-fade hidden h-[25px] w-[51px] cursor-pointer items-center justify-center"
                        @mouseenter="enter = true" @mouseleave="if (!clicked) enter = false">
                        <template x-cloak x-if="locale == 'en'">
                            <div
                                class="relative flex h-[25px] w-[51px] flex-row items-center gap-x-1 rounded-full bg-green-400">
                                <p class="absolute left-2 m-0 text-base">Id</p>
                                <div class="h-[25px] w-[25px] overflow-hidden rounded-full shadow-2xl transition-all"
                                    :class="{
                                        'translate-x-[28px]': enter,
                                        'translate-x-[2px]': !enter
                                    }">

                                    <svg class="w-[35px]" x-show="!enter" xmlns="http://www.w3.org/2000/svg"
                                        id="flag-icons-us" viewBox="0 0 640 480">
                                        <path fill="#bd3d44" d="M0 0h640v480H0" />
                                        <path stroke="#fff" stroke-width="37"
                                            d="M0 55.3h640M0 129h640M0 203h640M0 277h640M0 351h640M0 425h640" />
                                        <path fill="#192f5d" d="M0 0h364.8v258.5H0" />
                                        <marker id="us-a" markerHeight="30" markerWidth="30">
                                            <path fill="#fff" d="m14 0 9 27L0 10h28L5 27z" />
                                        </marker>
                                        <path fill="none" marker-mid="url(#us-a)"
                                            d="m0 0 16 11h61 61 61 61 60L47 37h61 61 60 61L16 63h61 61 61 61 60L47 89h61 61 60 61L16 115h61 61 61 61 60L47 141h61 61 60 61L16 166h61 61 61 61 60L47 192h61 61 60 61L16 218h61 61 61 61 60z" />
                                    </svg>
                                    <svg x-show="enter" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                        class="iconify iconify--emojione" preserveAspectRatio="xMidYMid meet"
                                        fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path d="M31.8 62c16.6 0 30-13.4 30-30h-60c0 16.6 13.4 30 30 30"
                                                fill="#f9f9f9">
                                            </path>
                                            <path d="M31.8 2c-16.6 0-30 13.4-30 30h60c0-16.6-13.4-30-30-30"
                                                fill="#ed4c5c">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                                <p class="m-0 !mr-1 text-base">En</p>
                            </div>
                        </template>
                        <template x-cloak x-if="locale == 'id'">
                            <div
                                class="relative flex h-[25px] w-[51px] flex-row items-center gap-x-1 rounded-full bg-green-400">
                                <p class="m-0 !ml-2 text-base">Id</p>
                                <div class="z-20 h-[25px] w-[25px] overflow-hidden rounded-full shadow-2xl transition-all"
                                    :class="{
                                        '-translate-x-[25px]': enter,
                                        'translate-x-0': !enter
                                    }">
                                    <svg x-show="!enter" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                        class="iconify iconify--emojione" preserveAspectRatio="xMidYMid meet"
                                        fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path d="M31.8 62c16.6 0 30-13.4 30-30h-60c0 16.6 13.4 30 30 30"
                                                fill="#f9f9f9">
                                            </path>
                                            <path d="M31.8 2c-16.6 0-30 13.4-30 30h60c0-16.6-13.4-30-30-30"
                                                fill="#ed4c5c">
                                            </path>
                                        </g>
                                    </svg>
                                    <svg class="w-[35px]" x-show="enter" xmlns="http://www.w3.org/2000/svg"
                                        id="flag-icons-us" viewBox="0 0 640 480">
                                        <path fill="#bd3d44" d="M0 0h640v480H0" />
                                        <path stroke="#fff" stroke-width="37"
                                            d="M0 55.3h640M0 129h640M0 203h640M0 277h640M0 351h640M0 425h640" />
                                        <path fill="#192f5d" d="M0 0h364.8v258.5H0" />
                                        <marker id="us-a" markerHeight="30" markerWidth="30">
                                            <path fill="#fff" d="m14 0 9 27L0 10h28L5 27z" />
                                        </marker>
                                        <path fill="none" marker-mid="url(#us-a)"
                                            d="m0 0 16 11h61 61 61 61 60L47 37h61 61 60 61L16 63h61 61 61 61 60L47 89h61 61 60 61L16 115h61 61 61 61 60L47 141h61 61 60 61L16 166h61 61 61 61 60L47 192h61 61 60 61L16 218h61 61 61 61 60z" />
                                    </svg>
                                </div>
                                <p class="absolute right-1 m-0 text-base">En</p>
                            </div>
                        </template>
                    </div>
                </div>
                <h2 class="text-primary mt-2 text-2xl font-bold">{{ __('news.option.page') }}</h2>
                <div class="mt-5">
                    <label for="titlePage" class="text-primary ml-2">{{ __('news.title.news') }}</label>
                    <input id="titlePage" type="text" class="w-full rounded-md border border-gray-300 p-2"
                        placeholder="{{ __('news.enter.title') }}" x-model="pageTitle">
                </div>

                <div class="relative mt-5" x-data="{ open: false }">
                    <h2 class="text-primary ml-3">{{ __('news.news_category') }}</h2>
                    <div class="border-primary rounded-md border p-2">
                        <template x-if="saveCategory && Array.isArray(saveCategory) && saveCategory.length > 0">
                            <div class="flex flex-row flex-wrap gap-3">
                                <template x-for="(item, index) in saveCategory" :key="item.id">
                                    <div class="bg-primary/15 rounded-md p-2 flex flex-row gap-x-3 items-center">
                                        <p
                                            x-text="item.translations[0].name ? item.translations[0].name : '{{ __('news.none.translate') }}'" class="text-primary">
                                        </p>
                                        <div class="flex items-center justify-center text-red-500" @click="$wire.removeCategory(item.id)">
                                            <svg class="w-[20px]" fill="currentColor" viewBox="0 0 32 32"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                    stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path
                                                        d="M18.8,16l5.5-5.5c0.8-0.8,0.8-2,0-2.8l0,0C24,7.3,23.5,7,23,7c-0.5,0-1,0.2-1.4,0.6L16,13.2l-5.5-5.5 c-0.8-0.8-2.1-0.8-2.8,0C7.3,8,7,8.5,7,9.1s0.2,1,0.6,1.4l5.5,5.5l-5.5,5.5C7.3,21.9,7,22.4,7,23c0,0.5,0.2,1,0.6,1.4 C8,24.8,8.5,25,9,25c0.5,0,1-0.2,1.4-0.6l5.5-5.5l5.5,5.5c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2.1,0-2.8L18.8,16z">
                                                    </path>
                                                </g>
                                            </svg>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <div @click="getCategory(); open = true"
                            class="text-primary cursor-pointer text-sm transition-all hover:underline mt-3">+
                            {{ __('news.add.category') }}</div>
                    </div>
                    <div @click.away="open=false" x-transition x-show="open"
                        class="border-primary absolute top-full mt-4 w-full rounded-xl border bg-white p-2 max-h-[240px] overflow-y-auto">
                        <template
                            x-if="!loadingCategory && category && Array.isArray(category) && category.length > 0">
                            <template x-for="(item, index) in category" :key="index">
                                <div x-text="item.translations[0].name ? item.translations[0].name : '{{ __('news.none.translate') }}'"
                                    @click="addCategory(item.id); open = false"
                                    class="bg-primary/10 text-primary/50 hover:text-primary active:text-primary hover:bg-primary/20 active:bg-primary/20 mt-2 w-full cursor-pointer rounded-md p-2 transition-all">
                                </div>
                            </template>
                        </template>
                        <div x-show="loadingCategory" class="flex items-center justify-center">
                            <svg aria-hidden="true"
                                class="h-5 w-5 animate-spin fill-blue-600 text-gray-200 dark:text-gray-600"
                                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="currentColor" />
                                <path
                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                    fill="currentFill" />
                            </svg>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('category.index') }}" class="text-primary text-sm underline-offset-1"
                                target="_blank" x-show="!loadingCategory">{{ __('news.category_new') }}</a>
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex flex-col items-center justify-center gap-y-3">
                    <h2 class="text-primary text-xl">{{ __('news.bg.image') }}</h2>
                    <div x-data="{ imageError: false }">
                        <template x-if="!imageError && imagePath">
                            <img :src="imagePath" :alt="titlePage"
                                @@error="imageError = true"
                                class="h-48 w-full rounded-sm object-cover sm:w-96" />
                        </template>

                        <template x-if="imageError || !imagePath">
                            <div
                                class="flex h-48 w-full items-center justify-center rounded-sm bg-gray-300 sm:w-96 dark:bg-gray-700">
                                <svg class="h-10 w-10 text-gray-200 dark:text-gray-600" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                    <path
                                        d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                                </svg>
                            </div>
                        </template>
                    </div>
                    <!-- Tombol untuk memicu pemilihan file -->
                    <button @click="$refs.imageInput.click()"
                        class="group relative mb-2 me-2 inline-flex w-[200px] items-center justify-center overflow-hidden rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 p-0.5 text-sm font-medium text-gray-900 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 group-hover:from-purple-600 group-hover:to-blue-500">
                        <span
                            class="relative rounded-md bg-white px-5 py-2.5 transition-all duration-75 ease-in group-hover:bg-transparent">
                            {{ __('news.change.image') }}
                        </span>
                    </button>

                    <input type="file" accept="image/*" x-ref="imageInput" class="hidden" @change="saveImage" />

                </div>

                <div class="mt-5 text-center">
                    <button type="button" @click="handlePublish"
                        x-bind:class="pageStatus ? 'bg-red-600 focus:ring-red-600 hover:bg-red-800' :
                            'bg-blue-700 focus:ring-blue-300 hover:bg-blue-800'"
                        class="mb-2 me-2 rounded-lg px-5 py-2.5 text-sm font-medium text-white focus:ring-4">
                        <span x-show="!loadingPublish" x-text="pageStatus ? 'Draf' : 'Publikasi'"></span>
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
        <div id="gjs" class="!h-full" wire:ignore>
            <p>lawak</p>
        </div>
    </div>
</div>

<script>
    function dataNewsEdit() {
        return {
            pageId: @entangle('pageId'),
            data: @entangle('data').live,
            pageTitle: '',
            apiToken: '',
            saving: false,
            openMenu: true,
            error: @entangle('error'),
            initStop: false,
            titlePage: '',
            imagePath: '',
            pageStatus: false,
            locale: "{{ Cookie::get('locale', 'id') }}",
            loadingPublish: false,
            loadingCategory: false,
            category: [],
            saveCategory: @entangle('saveCategory').live,
            addCategory(id) {
                if (!this.saveCategory.includes(id)) {
                    this.$wire.addNewsCategory(id);
                }
            },
            async initData() {
                this.pageTitle = this.data.translations[0].title || '';
                this.imagePath = this.data.image || null;
                this.pageStatus = this.data.status == 'draft' ? false : true;
                this.$watch('data', (newData) => {
                    this.pageTitle = newData.translations[0].title || '';
                    this.imagePath = newData.image || null;
                    this.pageStatus = newData.status == 'draft' ? false : true;
                });
            },
            async initNews() {
                if (this.initStop) return;
                this.initStop = true;
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
                this.initData();
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
                            console.log('Token received:', data.token);
                            this.apiToken = data.token;
                        }
                    });

            },
            runEditor() {
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
                                urlStore: this.saveDataURL(this.pageId),
                                urlLoad: this.getNewsURL(this.pageId),
                                headers: {
                                    'Authorization': `Bearer ${this.apiToken}`,
                                    'Content-Type': 'application/json',
                                    'locale': this.locale
                                },
                            },
                        },

                    },
                    blockManager: {
                        blocks: [{
                            id: 'section', // id is mandatory
                            label: '<b>Section</b>', // You can use HTML/SVG inside labels
                            attributes: {
                                class: 'gjs-block-section'
                            },
                            content: `<section>
          <h1>This is a simple title</h1>
          <div>This is just a Lorem text: Lorem ipsum dolor sit amet</div>
        </section>`,
                        }, ],
                    },
                    assetManager: {
                        upload: '{{ route('upload-image') }}',
                        uploadName: 'file',
                        headers: {
                            'Authorization': `Bearer ${this.apiToken}`,
                            'Jenis-File': 'news',
                            'page-id': this.pageId,
                            'locale': this.locale
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
                // const buttons = editor.Panels.getPanel('devices-c').get('devices-c');
                // const barButton = {
                //     id: 'openBar',
                //     className: 'fa fa-bars',
                //     command(editor) {
                //         changeOpen();
                //     },
                //     attributes: {
                //         title: 'Menu'
                //     }
                // };

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
                this.$watch('imagePath', markDirty);
                // this.$watch('pageLink', markDirty);
                // this.$watch('pageKeywords', markDirty);
                // this.$watch('pageDescription', markDirty);
                // this.$watch('pageRelease', markDirty);
                // this.$watch('pageIsRelease', markDirty);
                // this.$watch('pageParentLink', markDirty);

                // editor.Panels.getPanel('devices-c').get('devices-c').reset([
                //     barButton,
                //     ...buttons.map(btn => btn.attributes)
                // ]);
                const panelManager = editor.Panels;
                panelManager.addPanel({
                    id: 'myPanel',
                    visible: true,
                    buttons: [ // Isi button di dalam panel
                        {
                            id: 'openBar',
                            className: 'fa fa-bars',
                            command(editor) {
                                changeOpen(); // Fungsi kamu sendiri
                            },
                            attributes: {
                                title: 'Menu'
                            }
                        }
                    ]
                });

                // document.addEventListener('keydown', function(event) {
                //     if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 's') {
                //         event.preventDefault();
                //         if (!isDirty) return;
                //         editor.store();
                //         const html = editor.getHtml();
                //         const css = editor.getCss();
                //         saveCompile(html, css);
                //         savePage();
                //     }
                // });

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
                                    'locale': this.locale
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
                const urlStoreTemplate = `{{ route('newsData', ['id' => '__ID__']) }}`;
                const finalUrlStore = urlStoreTemplate.replace('__ID__', page);
                return finalUrlStore;
            },
            getNewsURL(page) {
                const urlStoreTemplate = `{{ route('getNews', ['id' => '__ID__']) }}`;
                const finalUrlStore = urlStoreTemplate.replace('__ID__', page);
                return finalUrlStore;
            },
            getCompileURL(page) {
                const urlStoreTemplate = `{{ route('newsCompile', ['id' => '__ID__']) }}`;
                const finalUrlStore = urlStoreTemplate.replace('__ID__', page);
                return finalUrlStore;
            },
            async savePage() {
                try {
                    if (!this.pageTitle || this.pageTitle.length < 3) {
                        this.$dispatch('failed', [{
                            message: 'Judul halaman tidak boleh kurang dari 3.'
                        }]);
                        return;
                    }
                    this.$wire.addTitle(this.pageTitle);
                } catch (error) {
                    this.$dispatch('failed', [{
                        message: 'Gagal menyimpan judul halaman.'
                    }]);
                }
            },
            saveImage() {
                const file = this.$refs.imageInput.files[0];
                if (!file) return;
                const formData = new FormData();
                formData.append('file', file);
                fetch('{{ route('upload-image') }}', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${this.apiToken}`,
                            'Jenis-File': 'news',
                            'page-id': this.pageId,
                            'locale': this.locale
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        console.log('Upload berhasil:', result);
                        this.$wire.addPathImage(result.data[0]);
                    })
                    .catch(error => {
                        console.error('Upload gagal:', error);
                    });
            },
            async handlePublish() {
                try {
                    this.loadingPublish = true;
                    await this.$wire.handlePublish();
                } catch (error) {
                    this.$dispatch('failed', [{
                        message: `Gagal melakukan publikasi: ${error.message}`
                    }]);
                } finally {
                    this.loadingPublish = false;
                }
            },
            async getCategory() {
                try {
                    this.loadingCategory = true;
                    const data = await this.$wire.getCategory();
                    this.category = [];
                    data.forEach(item => {
                        const alreadySaved = this.saveCategory.some(saved => saved.id === item.id);
                        if (!alreadySaved) {
                            this.category.push(item);
                        }
                    });
                } catch (error) {

                } finally {
                    this.loadingCategory = false;
                }
            }
        }
    }
</script>
