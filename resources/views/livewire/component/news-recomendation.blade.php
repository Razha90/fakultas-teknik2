<?php

use Livewire\Volt\Component;
use App\Models\Content;
use App\Models\News;
use Illuminate\Support\Facades\Log;

new class extends Component {
    public $data;
    public $datas = [];
    public $recom = [];
    public $pageId;

    public function mount()
    {
        $path = request()->path();

        // Pecah path jadi array berdasarkan "/"
        $segments = explode('/', $path);

        // Ambil ID ke-3 jika tersedia
        $this->pageId = $segments[2] ?? null;
        $this->newsGet();
    }

    public function search()
    {
        try {
            // Ambil 4 berita terpopuler dari Content berdasarkan views
            // $this->data = Content::with('categories', 'user')
            //     ->orderBy('views', 'desc')
            //     ->limit(4)
            //     ->get()
            //     ->toArray();
            $locale = app()->getLocale();
            $eventCollection = News::with([
                'translations' => function ($query) use ($locale) {
                    $query->where('locale', $locale);
                },
                'categories.translations' => function ($query) use ($locale) {
                    $query->where('locale', $locale);
                },
            ])
                ->where('id', $this->pageId)
                ->where('status', 'published')
                ->first();

            if (is_null($eventCollection)) {
                // $this->dispatch('failed', [
                //     'message' => __('news.not_found'),
                // ]);
            } else {
                $this->data = $eventCollection->toArray();
            }
        } catch (\Throwable $th) {
            $this->dispatch('failed', [
                'message' => __('news.error'),
            ]);
            Log::error('Error fetching news data: ' . $th->getMessage());
        }
    }

    public function newsGet()
    {
        try {
            $this->recom = News::with([
                'translations' => function ($query) {
                    $query->where('locale', app()->getLocale());
                },
                'categories.translations' => function ($query) {
                    $query->where('locale', app()->getLocale());
                },
            ])
                ->where('status', 'published')
                ->orderBy('views', 'desc')
                ->limit(6)
                ->get()
                ->toArray();
        } catch (\Throwable $th) {
            $this->dispatch('failed', [
                'message' => __('news.error'),
            ]);
            Log::error('Error fetching news recommendations: ' . $th->getMessage());
        }
    }
};
?>
<div x-data="initRecomendation" x-init="init" x-intersect="shown = true"
    class="bg-accent-white w-full min-w-[350px] lg:w-[20%]">
    <div class="flex flex-row justify-between border-b-2 border-gray-200 px-5 pb-3 pt-5">
        <h2 class="text-primary w-full text-xl font-bold">{{ __('news.most_views') }}</h2>
        <svg @click="goToPage('{{ route('news-search') }}')"
            class="text-primary w-[35px] cursor-pointer transition-all hover:opacity-60" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
                <path d="M4 12H20M20 12L16 8M20 12L16 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </g>
        </svg>
    </div>
    <div class="flex w-full flex-wrap gap-y-4 px-5 py-3 lg:flex-col">
        <template x-if="!recom || (Array.isArray(recom) && recom.length === 0)">
            <template x-for="(data, index) in recom" :key="index">
                <div x-show="shown" class="animate-fade flex w-full flex-row gap-x-2">
                    <div
                        class="flex h-[60px] w-[70px] min-w-[70px] animate-pulse items-center justify-center overflow-hidden rounded-xl bg-gray-300 dark:bg-gray-700">
                        <svg class="object-cover text-gray-200 dark:text-gray-600" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                            <path
                                d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                        </svg>
                    </div>
                    <div class="w-full">
                        <div class="mb-2 h-4 w-20 animate-pulse rounded-full bg-gray-200 dark:bg-gray-700">
                        </div>
                        <div class="h-4 w-full animate-pulse rounded-full bg-gray-200 dark:bg-gray-700">
                        </div>
                        <div class="mt-1 h-4 w-full animate-pulse rounded-full bg-gray-200 dark:bg-gray-700">
                        </div>
                    </div>
                </div>
            </template>
        </template>

        <template x-if="recom && Array.isArray(recom) && recom.length > 0">
            <template x-for="(data, index) in recom" :key="index">
                <div x-show="shown"
                    class="animate-fade flex w-[350px] cursor-pointer flex-row gap-x-2 rounded-xl transition-all hover:bg-gray-200"
                    x-data="{ mouse: false }" @mouseenter="mouse = true" @mouseleave="mouse = false"
                    @click="goToNews(data.id)">
                    <template x-if="!data.image">
                        <div
                            class="flex h-[60px] w-[70px] min-w-[70px] items-center justify-center overflow-hidden rounded-xl bg-gray-300 dark:bg-gray-700">
                            <svg class="object-cover text-gray-200 dark:text-gray-600" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                <path
                                    d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                            </svg>
                        </div>
                    </template>
                    <template x-if="data.image">
                        <div
                            class="flex h-[60px] w-[70px] min-w-[70px] items-center justify-center overflow-hidden rounded-xl bg-gray-300 transition-all dark:bg-gray-700">
                            <img class="h-full w-full transition-all" x-bind:class="mouse ? 'scale-100' : 'scale-125'"
                                x-bind:src="data.image" :alt="data.title" />
                        </div>
                    </template>
                    <div class="flex w-full flex-col overflow-hidden">
                        <div>
                            <p class="text-sm text-gray-400"
                                x-text="data.categories && data.categories.length && data.categories[0].translations.length ? data.categories[0].translations[0].name : '—'">
                            </p>

                        </div>
                        <p class="line-clamp-2 w-full text-sm text-gray-500"
                            x-text="data.translations && data.translations.length > 0 && data.translations[0].title ?
                                        data.translations[0].title :
                                        '{{ __('news.none.translate') }}'">
                        </p>
                    </div>
                </div>
            </template>
        </template>
    </div>
</div>
<script>
    function initRecomendation() {
        return {
            datas: @entangle('data').live,
            recom: @entangle('recom').live,
            shown: false,
            goToNews(id) {
                const dummy = '{{ route('news-page', ['id' => '__DUMMY_ID__']) }}'.replace('__DUMMY_ID__', id);
                goToPage(dummy);
            },
            init() {
                this.$wire.search();
                console.log('initRecomendation initialized', this.recom);
            },
        }
    }
</script>
