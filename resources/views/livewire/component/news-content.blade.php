<?php

use Livewire\Volt\Component;
use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $data;
    public function search($page = 0, $coloumns)
    {
        try {
            $this->data = News::with('categories', 'user')
                ->orderBy('created_at', 'desc')
                ->paginate($coloumns, ['*'], 'page', $page)
                ->toArray();
        } catch (\Throwable $th) {
            $this->dispatch('failed', [
                'message' => __('news.error'),
            ]);
        }
    }

    public function createNews()
    {
        try {
            $user = User::get()->first();
            $news = new News();
            $news->user_id = $user->id;
            $news->title = 'New Title';
            $news->content = 'New Content';
            $news->image = 'https://i.ytimg.com/vi/9zB01qk3M-w/maxresdefault.jpg';
            $news->save();

            $categories = Category::inRandomOrder()->limit(3)->get();

            $categoryIds = $categories->pluck('id')->toArray();
            foreach ($categoryIds as $categoryId) {
                DB::table('category_news')->insert([
                    'id' => (string) Str::uuid(), // Menghasilkan UUID secara manual
                    'news_id' => $news->id,
                    'category_id' => $categoryId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $th) {
            Log::error('Error creating news: ' . $th->getMessage());
            // Handle the error as needed
        }
    }

    public function makeCategory()
    {
        $category = new Category();
        $category->name = 'Unimed';
        $category->save();
    }

    public function addLikes($id)
    {
        $news = News::find($id);
        if ($news) {
            $news->increment('likes');
        } else {
            $this->dispatch('failed', [
                'message' => __('news.error'),
            ]);
        }
    }

    public function removeLikes($id)
    {
        $news = News::find($id);
        if ($news) {
            $news->decrement('likes');
        } else {
            $this->dispatch('failed', [
                'message' => __('news.error'),
            ]);
        }
    }
}; ?>

<div x-data="initNewsContent" x-init="init" class="mx-auto max-w-[var(--max-width)] px-10 mt-10">
    @vite(['resources/js/moment.js'])
    <!-- <button wire:click="makeCategory">Make Category</button>
    <button wire:click="createNews">Create News</button> -->

    <h2
        class="text-primary text-primary relative inline-block text-4xl font-bold after:absolute after:-bottom-1 after:left-0 after:block after:h-[4px] after:w-1/4 after:rounded-full after:bg-green-400 after:transition-all after:duration-300 hover:after:w-full">
        {{ __('news.breaking_news') }}</h2>
    <div class="mt-10 flex w-full flex-row items-start">
        <div x-ref="content" class="flex w-[80%] flex-row justify-start gap-x-7" x-intersect="shown = true">
            <div class="animate-fade flex items-center"
                x-bind:class="news && news.current_page && news.current_page != '1' ? 'visible' : 'invisible'">
                <div class="bg-primary/20 hover:bg-primary/50 flex cursor-pointer items-center px-2 py-8 transition-colors"
                    @click="prevPage">
                    <svg viewBox="0 0 24 24" fill="none" class="text-accent-white w-[40px]"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M16.1795 3.26875C15.7889 2.87823 15.1558 2.87823 14.7652 3.26875L8.12078 9.91322C6.94952 11.0845 6.94916 12.9833 8.11996 14.155L14.6903 20.7304C15.0808 21.121 15.714 21.121 16.1045 20.7304C16.495 20.3399 16.495 19.7067 16.1045 19.3162L9.53246 12.7442C9.14194 12.3536 9.14194 11.7205 9.53246 11.33L16.1795 4.68297C16.57 4.29244 16.57 3.65928 16.1795 3.26875Z"
                                fill="currentColor"></path>
                        </g>
                    </svg>
                </div>
            </div>
            <template x-if="!news || (Array.isArray(news) && news.length === 0) || loadPage">
                <template x-for="index in itemsPerRow" :key="index">
                    <div x-show="shown" class="animate-fade w-[350px] rounded-xl border border-gray-300 shadow-sm">
                        <div
                            class="flex h-[200px] w-full animate-pulse items-center justify-center rounded-t-xl bg-gray-300 dark:bg-gray-700">
                            <svg class="h-10 w-10 text-gray-200 dark:text-gray-600" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                <path
                                    d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                            </svg>
                        </div>
                        <div class="mt-3 px-5">
                            <div class="mb-4 h-4 w-48 animate-pulse rounded-full bg-gray-200 dark:bg-gray-700">
                            </div>
                            <div
                                class="mb-2.5 h-3 max-w-[480px] animate-pulse rounded-full bg-gray-200 dark:bg-gray-700">
                            </div>
                            <div class="mb-2.5 h-3 animate-pulse rounded-full bg-gray-200 dark:bg-gray-700"></div>
                        </div>
                        <div class="my-5 h-[1px] w-full overflow-hidden rounded-full px-5">
                            <div class="h-full w-full bg-gray-200"></div>
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
                    </div>
                </template>
            </template>

            <template x-if="news && Array.isArray(news.data) && news.data.length > 0 && !loadPage">
                <template x-for="(data, index) in news.data" :key="index">
                    <div x-show="shown"
                        class="animate-fade animate-fade w-[350px] rounded-xl border border-gray-300 shadow-sm">
                        <div x-data="{ isLoaded: false }" @click="goToNews(data.id)"
                            class="h-[200px] w-full cursor-pointer overflow-hidden rounded-t-xl">
                            <div x-show="!isLoaded"
                                class="flex animate-pulse items-center justify-center bg-gray-300 dark:bg-gray-700">
                                <svg class="h-10 w-10 text-gray-200 dark:text-gray-600" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                    <path
                                        d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                                </svg>
                            </div>
                            <img x-bind:src="data.image" x-on:load="isLoaded = true"
                                class="h-full w-full brightness-75 transition-all hover:scale-125 hover:brightness-100" />
                        </div>
                        <div class="mt-3 px-5">
                            <div class="flex flex-wrap gap-3">
                                <template x-if="data.categories && data.categories.length > 0">
                                    <template x-for="(item, key) in data.categories">
                                        <div x-text="item.name"
                                            class="cursor-pointer bg-gray-200 px-4 py-1 text-sm text-gray-400 transition-all hover:scale-110">
                                        </div>
                                    </template>
                                </template>
                            </div>
                            <div class="truncate-line-clamp-2 mt-4 text-gray-500" x-text="data.title"></div>
                        </div>
                        <div @click="goToNews(data.id)" class="my-4 h-[1px] w-full overflow-hidden rounded-full px-5">
                            <div class="h-full w-full bg-gray-200"></div>
                        </div>
                        <div class="mb-5 flex flex-row items-center justify-between px-5">
                            <div class="flex flex-row items-center" @click="goToNews(data.id)">
                                <template x-if="!data.user.image">
                                    <div class="flex items-center">
                                        <svg class="me-3 h-9 w-9 text-gray-200 dark:text-gray-700" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                                        </svg>
                                    </div>
                                </template>
                                <template x-if="data.user.image">
                                    <div class="me-3">
                                        <img x-bind:src="data.user.image" :alt="data.user.name"
                                            class="h-9 w-9 rounded-full" />
                                    </div>
                                </template>
                                <p class="max-w-[100px] truncate text-base text-gray-500" x-text="data.user.name">
                                </p>
                                <p class="mx-1 text-sm text-gray-300"> - </p>
                                <p class="text-sm text-gray-300" x-text="changeDate(data.created_at)"></p>
                            </div>
                            <div class="flex flex-row items-center gap-x-1">
                                <p class="text-sm text-gray-300" x-text="formatLikes(data.likes)"></p>
                                <svg class="animate-fade w-[20px] cursor-pointer text-gray-400 transition-all hover:opacity-70"
                                    x-show="!isLiked(data.id)" @click="giveLikes(data.id)" viewBox="0 -2.5 21 21"
                                    version="1.1" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" fill="currentColor">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                    </g>
                                    <g id="SVGRepo_iconCarrier">
                                        <title>love [#1489]</title>
                                        <desc>Created with Sketch.</desc>
                                        <defs> </defs>
                                        <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                            fill-rule="evenodd">
                                            <g id="Dribbble-Light-Preview"
                                                transform="translate(-99.000000, -362.000000)" fill="currentColor">
                                                <g id="icons" transform="translate(56.000000, 160.000000)">
                                                    <path
                                                        d="M55.5929644,215.348992 C55.0175653,215.814817 54.2783665,216.071721 53.5108177,216.071721 C52.7443189,216.071721 52.0030201,215.815817 51.4045211,215.334997 C47.6308271,212.307129 45.2284309,210.70073 45.1034811,207.405962 C44.9722313,203.919267 48.9832249,202.644743 51.442321,205.509672 C51.9400202,206.088455 52.687619,206.420331 53.4940177,206.420331 C54.3077664,206.420331 55.0606152,206.084457 55.5593644,205.498676 C57.9649106,202.67973 62.083004,203.880281 61.8950543,207.507924 C61.7270546,210.734717 59.2322586,212.401094 55.5929644,215.348992 M53.9066671,204.31012 C53.8037672,204.431075 53.6483675,204.492052 53.4940177,204.492052 C53.342818,204.492052 53.1926682,204.433074 53.0918684,204.316118 C49.3717243,199.982739 42.8029348,202.140932 43.0045345,207.472937 C43.1651842,211.71635 46.3235792,213.819564 50.0426732,216.803448 C51.0370217,217.601149 52.2739197,218 53.5108177,218 C54.7508657,218 55.9898637,217.59915 56.9821122,216.795451 C60.6602563,213.815565 63.7787513,211.726346 63.991901,207.59889 C64.2754005,202.147929 57.6173611,199.958748 53.9066671,204.31012">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                <svg x-show="isLiked(data.id)" @click="giveLikes(data.id)"
                                    class="animate-fade w-[20px] cursor-pointer text-red-400 transition-all hover:opacity-70"
                                    viewBox="0 -2.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" fill="currentColor">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                    </g>
                                    <g id="SVGRepo_iconCarrier">
                                        <title>love [#1488]</title>
                                        <desc>Created with Sketch.</desc>
                                        <defs> </defs>
                                        <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                            fill-rule="evenodd">
                                            <g id="Dribbble-Light-Preview"
                                                transform="translate(-139.000000, -361.000000)" fill="currentColor">
                                                <g id="icons" transform="translate(56.000000, 160.000000)">
                                                    <path
                                                        d="M103.991908,206.599878 C103.779809,210.693878 100.744263,212.750878 96.9821188,215.798878 C94.9997217,217.404878 92.0324261,217.404878 90.042679,215.807878 C86.3057345,212.807878 83.1651892,210.709878 83.0045394,206.473878 C82.8029397,201.150878 89.36438,198.971878 93.0918745,203.314878 C93.2955742,203.552878 93.7029736,203.547878 93.9056233,203.309878 C97.6205178,198.951878 104.274358,201.159878 103.991908,206.599878"
                                                        id="love-[#1488]"> </path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            <template x-if="news && news.current_page != news.last_page">
                <div class="animate-fade flex items-center">
                    <div class="bg-primary/20 hover:bg-primary/50 flex cursor-pointer items-center px-2 py-8 transition-colors"
                        @click="nextPage">
                        <svg viewBox="0 0 24 24" fill="none" class="text-accent-white w-[40px] rotate-180"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M16.1795 3.26875C15.7889 2.87823 15.1558 2.87823 14.7652 3.26875L8.12078 9.91322C6.94952 11.0845 6.94916 12.9833 8.11996 14.155L14.6903 20.7304C15.0808 21.121 15.714 21.121 16.1045 20.7304C16.495 20.3399 16.495 19.7067 16.1045 19.3162L9.53246 12.7442C9.14194 12.3536 9.14194 11.7205 9.53246 11.33L16.1795 4.68297C16.57 4.29244 16.57 3.65928 16.1795 3.26875Z"
                                    fill="currentColor"></path>
                            </g>
                        </svg>
                    </div>
                </div>
            </template>
        </div>
        <livewire:component.news-recomendation />
    </div>
</div>

<script>
    function initNewsContent() {
        return {
            news: @entangle('data').live,
            itemsPerRow: 0,
            stopInit: false,
            shown: false,
            likes: JSON.parse(localStorage.getItem('likes')) || [],
            loadPage: false,
            init() {
                if (this.stopInit) return;
                this.stopInit = true;
                this.$watch('itemsPerRow', (newValue) => {
                    this.paginate(0, newValue);
                });

                window.addEventListener('resize', () => {
                    this.calculateColumns();
                });
                this.$nextTick(() => {
                    this.calculateColumns();
                });
            },
            goToNews(id) {
                const dummy = '{{ route('news-page', ['id' => '__DUMMY_ID__']) }}'.replace('__DUMMY_ID__', id);
                goToPage(dummy);
            },
            async nextPage() {
                if (this.loadPage) return;
                this.loadPage = true;
                await this.$wire.search(this.news.current_page + 1, this.itemsPerRow);
                this.loadPage = false;
            },
            async prevPage() {
                if (this.loadPage) return;
                this.loadPage = true;
                await this.$wire.search(this.news.current_page - 1, this.itemsPerRow);
                this.loadPage = false;
            },
            giveLikes(idLikes) {
                if (!this.likes) {
                    this.likes = [];
                }
                const index = this.likes.indexOf(idLikes);
                if (index === -1) {
                    this.$wire.addLikes(idLikes);
                    this.likes.push(idLikes);
                    this.news.data = this.news.data.map(item => {
                        if (item.id === idLikes) {
                            item.likes += 1;
                        }
                        return item;
                    });
                } else {
                    this.$wire.removeLikes(idLikes);
                    this.likes.splice(index, 1);
                    this.news.data = this.news.data.map(item => {
                        if (item.id === idLikes) {
                            item.likes -= 1;
                        }
                        return item;
                    });
                }
                localStorage.setItem('likes', JSON.stringify(this.likes));
            },
            isLiked(id) {
                return this.likes.includes(id);
            },
            changeDate(createdAt) {
                const formattedTime = moment(createdAt).fromNow(); // Menggunakan moment.js yang sudah tersedia
                return formattedTime;
            },
            formatLikes(number) {
                if (number >= 1_000_000) {
                    return (number / 1_000_000).toFixed(1).replace('.', ',') + 'M';
                } else if (number >= 1_000) {
                    return (number / 1_000).toFixed(1).replace('.', ',') + 'K';
                } else {
                    return number.toString();
                }
            },
            calculateColumns() {
                let container = this.$refs.content;
                let containerWidth = container.clientWidth;
                let itemWidth = 350;
                let gap = 28;
                let arrow = 60;
                let totalItemWidth = itemWidth + gap + arrow;
                this.itemsPerRow = Math.floor((containerWidth) / totalItemWidth);
            },
            paginate(page = 0, itemsPerRow) {
                this.$wire.search(page, itemsPerRow);
            },

        }
    }
</script>
