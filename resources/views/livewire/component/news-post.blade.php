<?php

use Livewire\Volt\Component;
use App\Models\Content;
use Illuminate\Support\Facades\Log;

new class extends Component {
    public $news;
    public $relatedNews = [];
    public $popularNews = [];

    public function running()
    {
        $this->getNews();
    }

    private function getNews()
    {
        try {
            // Ambil 3 content terbaru dari kategori yang namanya mengandung "News"
            $newsCollection = Content::with('categories')
                ->whereHas('categories', function ($query) {
                    $query->where('name', 'like', '%News%');
                })
                ->orderBy('published_at', 'desc') // gunakan published_at jika itu tanggal publikasi
                ->take(3)
                ->get();

            if ($newsCollection->isEmpty()) {
                $this->dispatch('failed', ['message' => __('news.not_found')]);
            } else {
                $this->news = $newsCollection->toArray();
            }

            // Ambil 3 related posts dari kategori yang sama dengan first content
            if ($this->news && count($this->news) > 0) {
                $firstContentId = $this->news[0]['id'];
                $firstContent = Content::with('categories')->find($firstContentId);

                if ($firstContent) {
                    $categoryIds = $firstContent->categories->pluck('id')->toArray();

                    $this->relatedNews = Content::whereHas('categories', function ($q) use ($categoryIds) {
                        $q->whereIn('id', $categoryIds);
                    })
                    ->where('id', '!=', $firstContentId)
                    ->orderBy('published_at', 'desc')
                    ->take(3)
                    ->get()
                    ->toArray();
                }
            }

            // Ambil 4 content terpopuler berdasarkan views
            $this->popularNews = Content::orderBy('views', 'desc')
                ->take(4)
                ->get()
                ->toArray();

        } catch (\Throwable $th) {
            $this->dispatch('failed', ['message' => 'Failed Get News']);
            Log::error('Failed Get News', [
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ]);
        }
    }
};
?>

<section class="mb-10" x-data="initHome">
    <div class="linked-1:mt-14 mx-auto mt-8 max-w-[--max-width]" x-data="{ scrolled: false }">
        <template x-cloak x-if="!news || (Array.isArray(news) && news.length === 0)">
            <div class="flex flex-row flex-wrap items-center justify-center gap-x-7 gap-y-7 px-10">
                <template x-cloak x-for="i in [1,2,3]" :key="i">
                    <div class="animate-pulse">
                        <div
                            class="flex h-64 w-[405px] items-center justify-center rounded-sm bg-gray-300 dark:bg-gray-700">
                            <svg class="h-10 w-10 text-gray-200 dark:text-gray-600" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                <path
                                    d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                            </svg>
                        </div>
                        <div class="mt-2 h-5 max-w-[360px] rounded-full bg-gray-200 dark:bg-gray-700"></div>
                    </div>
                </template>
            </div>
        </template>
        <template x-cloak x-if="news && Array.isArray(news) && news.length > 0">
            <div class="ftnews-1:px-5 mx-auto flex flex-row flex-wrap items-start justify-center gap-x-7 gap-y-7 px-0"
                x-intersect="scrolled=true">
                <template x-cloak x-for="(data, i) in news" :key="i">
                    <div class="ftnews-1:w-[405px] group w-full cursor-pointer"
                        x-bind:class="scrolled ? `animate-delay-${i*2}00 animate-fade` : 'opacity-0'"
                        @click="goToNews(data.id)">
                        <div
                            class="flex h-64 w-full items-center justify-center overflow-hidden rounded-sm bg-gray-300">
                            <img :src="`/storage/${data.image}`" alt="data.title"
                                class="h-full w-full object-cover transition-all group-hover:scale-125" />
                        </div>
                        <div class="px-2">
                            <p class="text-primary-dark group-hover:text-primary-light my-2 line-clamp-2 text-xl"
                                x-text="data.title"></p>
                            <div
                                class="text-primary-dark group-hover:text-primary-light flex flex-row items-center justify-start gap-x-1">
                                <svg class="h-[25px] w-[25px]" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                    </g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M8 12C7.44772 12 7 12.4477 7 13C7 13.5523 7.44772 14 8 14H16C16.5523 14 17 13.5523 17 13C17 12.4477 16.5523 12 16 12H8Z"
                                            fill="currentColor"></path>
                                        <path
                                            d="M7 17C7 16.4477 7.44772 16 8 16H12C12.5523 16 13 16.4477 13 17C13 17.5523 12.5523 18 12 18H8C7.44772 18 7 17.5523 7 17Z"
                                            fill="currentColor"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8 3C8 2.44772 7.55228 2 7 2C6.44772 2 6 2.44772 6 3V4.10002C3.71776 4.56329 2 6.58104 2 9V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V9C22 6.58104 20.2822 4.56329 18 4.10002V3C18 2.44772 17.5523 2 17 2C16.4477 2 16 2.44772 16 3V4H8V3ZM20 10H4V17C4 18.6569 5.34315 20 7 20H17C18.6569 20 20 18.6569 20 17V10ZM4.17071 8C4.58254 6.83481 5.69378 6 7 6H17C18.3062 6 19.4175 6.83481 19.8293 8H4.17071Z"
                                            fill="currentColor"></path>
                                    </g>
                                </svg>
                                <p class="text-sm" x-text="formatDate(data.created_at)"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>
</section>

<script>
        function initHome() {
        return {
            news: @entangle('news').live,
            stopRun: false,
            init() {
                if (this.stopRun) return;
                this.stopRun = true;
                this.$wire.running();

            },
            changeDate(createdAt) {
                const formattedTime = moment(createdAt).fromNow();
                return formattedTime;
            },
            formatDate(tanggal) {
                const date = new Date(tanggal);
                const locale = "{{ app()->getLocale() }}"; // 'en' atau 'id'
                const formatted = date.toLocaleDateString(locale === 'id' ? 'id-ID' : 'en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                return formatted;
            },
            formatMonthYear(tanggal) {
                const date = new Date(tanggal);
                const locale = "{{ app()->getLocale() }}"; // 'id' atau 'en'

                const formatted = date.toLocaleDateString(locale === 'id' ? 'id-ID' : 'en-US', {
                    month: 'short',
                    year: 'numeric'
                });
                return formatted.toUpperCase(); // Ubah jadi huruf kapital semua
            },
            formatDay(tanggal) {
                const date = new Date(tanggal);
                if (isNaN(date)) return '';

                return date.getDate().toString();
            },

            goToNews(id) {
                const dummy = '{{ route('news-page', ['id' => '__DUMMY_ID__']) }}'.replace('__DUMMY_ID__', id);
                goToPage(dummy);
            }
        }
    }

</script>
