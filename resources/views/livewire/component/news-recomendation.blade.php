<?php

use Livewire\Volt\Component;
use App\Models\Content;

new class extends Component {
    public $data;

    public function search()
    {
        try {
            // Ambil 4 berita terpopuler dari Content berdasarkan views
            $this->data = Content::with('categories', 'user')
                ->orderBy('views', 'desc')
                ->limit(4)
                ->get()
                ->toArray();
        } catch (\Throwable $th) {
            $this->dispatch('failed', [
                'message' => __('news.error'),
            ]);
        }
    }
};
?>
<div x-data="initRecomendation" x-init="init" x-intersect="shown = true"
    class="lg:w-[20%] w-full min-w-[350px] bg-accent-white">
    <div class="flex flex-row justify-between border-b-2 border-gray-200 px-5 pb-3 pt-5">
        <h2 class="text-primary w-full text-xl font-bold">{{ __('news.most_views') }}</h2>
        <svg @click="goToPage('{{ route('news-search') }}')" class="text-primary w-[35px] transition-all hover:opacity-60 cursor-pointer" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
                <path d="M4 12H20M20 12L16 8M20 12L16 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </g>
        </svg>
    </div>
    <div class="flex w-full lg:flex-col flex-wrap gap-y-4 px-5 py-3">
        <template x-if="!datas || (Array.isArray(datas) && datas.length === 0)">
            <template x-for="(data, index) in 4" :key="index">
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

        <template x-if="datas && Array.isArray(datas) && datas.length > 0">
            <template x-for="(data, index) in datas" :key="index">
                <div x-show="shown" class="animate-fade cursor-pointer hover:bg-gray-200 transition-all rounded-xl flex flex-row gap-x-2 w-[350px]" x-data="{ mouse: false }"
                    @mouseenter="mouse = true" @mouseleave="mouse = false" @click="goToNews(data.id)">
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
                                x-bind:src="`/storage/${data.image}`" :alt="data.title" />
                        </div>
                    </template>
                    <div class="flex w-full flex-col overflow-hidden">
                        <div>
                            <template x-if="data.categories && data.categories.length > 0">
                                <p class="text-sm text-gray-400" x-text="data.categories[0].name">

                                </p>
                            </template>
                            <template x-if="!data.categories || data.categories.length === 0">
                                <p class="text-sm text-gray-400">{{ __('news.not_found') }}</p>
                            </template>
                        </div>
                        <p class="w-full text-sm text-gray-500 line-clamp-2">alsndlnlsandlks andlksandlksandlks andlksalknsad sasasasasas sdasdsadsadsadsaddsad sadsa dsad sa a </p>
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
            shown: false,
            goToNews(id) {
                const dummy = '{{ route('news-page' , ['id' => '__DUMMY_ID__']) }}'.replace('__DUMMY_ID__', id);
                goToPage(dummy);
            },
            init() {
                this.$wire.search();
            },
        }
    }
</script>
