<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\News;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoryNews;
use App\Models\Content;

new #[Layout('components.layouts.home')] class extends Component {
    public $news;
    public $mbkm;
    public $events;
    public $pengumuman;
    public function running()
    {
        $this->getNews();
        $this->getMBKM();
        $this->getEvent();
        $this->getPengumuman();
    }
private function getNews()
{
    try {
        $newsCollection = Content::with('type') // ambil relasi jenis konten
            ->whereHas('type', function ($query) {
                $query->whereIn('name', ['Berita', 'FT News', 'MBKM News']);
            })
            ->where('status', 'published') // hanya status published
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // dd($newsCollection);

        if ($newsCollection->isEmpty()) {
            $this->dispatch('failed', ['message' => __('news.not_found')]);
        } else {
            $this->news = $newsCollection->toArray();
        }
    } catch (\Throwable $th) {
        // dd('Error:', $th->getMessage(), $th->getLine(), $th->getFile());
        $this->dispatch('failed', ['message' => 'Failed Get News']);
        Log::error('Failed Get News', [
            'error' => $th->getMessage(),
            'line' => $th->getLine(),
            'file' => $th->getFile(),
        ]);
    }
}


private function getMBKM()
{
    try {
        $mbkmCollection = Content::with('type')
            ->whereHas('type', function ($query) {
                $query->where('name', 'MBKM News');
            })
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        if ($mbkmCollection->isEmpty()) {
            $this->dispatch('failed', ['message' => __('news.not_found')]);
        } else {
            $this->mbkm = $mbkmCollection->toArray();
        }
    } catch (\Throwable $th) {
        $this->dispatch('failed', ['message' => 'Failed Get MBKM News']);
        Log::error('Failed Get MBKM News', [
            'error' => $th->getMessage(),
            'line' => $th->getLine(),
            'file' => $th->getFile(),
        ]);
    }
}

private function getEvent()
{
    try {
        $eventCollection = Content::with('type')
            ->whereHas('type', function ($query) {
                $query->where('name', 'FT Event');
            })
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        if ($eventCollection->isEmpty()) {
            $this->dispatch('failed', ['message' => __('news.not_found')]);
        } else {
            $this->events = $eventCollection->toArray();
        }
    } catch (\Throwable $th) {
        $this->dispatch('failed', ['message' => 'Failed Get Event News']);
        Log::error('Failed Get Event News', [
            'error' => $th->getMessage(),
            'line' => $th->getLine(),
            'file' => $th->getFile(),
        ]);
    }
}

private function getPengumuman()
{
    try {
        $pengumumanCollection = Content::with('type')
            ->whereHas('type', function ($query) {
                $query->where('name', 'Pengumuman FT');
            })
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        if ($pengumumanCollection->isEmpty()) {
            $this->dispatch('failed', ['message' => __('news.not_found')]);
        } else {
            $this->pengumuman = $pengumumanCollection->toArray();
        }
    } catch (\Throwable $th) {
        $this->dispatch('failed', ['message' => 'Failed Get Pengumuman']);
        Log::error('Failed Get Pengumuman', [
            'error' => $th->getMessage(),
            'line' => $th->getLine(),
            'file' => $th->getFile(),
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

            $category = Category::orderBy('created_at', 'desc')->first();

            CategoryNews::create([
                'news_id' => $news->id,
                'category_id' => $category->id,
            ]);
        } catch (\Throwable $th) {
            Log::error('Error creating news: ' . $th->getMessage());
        }
    }

    public function makeCategory()
    {
        $category = new Category();
        $category->name = 'MBKM';
        $category->save();
    }
}; ?>

<div x-data="initHome" x-init="init">
    @vite(['resources/js/moment.js'])

    <section class="bg-primary pt-18 relative w-full bg-gradient-to-b sm:pt-24 lg:pt-0">
        <img class="absolute left-0 top-0 z-0 h-full w-full" src="{{ asset('img/bg-primary.png') }}" />
        @push('meta')
            <meta name="keywords" content="universitas, pendidikan, Medan, kampus, unimed, mahasiswa, akademik">
            <meta name="description"
                content="Website resmi Universitas Negeri Medan - informasi akademik, berita kampus, dan layanan mahasiswa.">
        @endpush
        <div class="mx-auto max-w-[var(--max-width)]" x-data="{
            play: false,
            svgLoaded: false,
            blackScreen: false,
            initVid() {
                const img = new Image();
                img.src = '{{ asset('img/mask.svg') }}';
                img.onload = () => {
                    this.svgLoaded = true;
                };
                setTimeout(() => {
                    this.play = true;
                }, 3000);
            },
        }">
            <div class="relative">
                <div x-init="initVid" style="will-change: opacity;"
                    class="shadow-3xl animate-fade relative aspect-video overflow-hidden">
                    <video x-intersect="blackScreen=true" x-cloak x-show="svgLoaded" autoplay muted loop playsinline
                        class="animate-fade absolute inset-0 z-10 h-full w-full object-cover" @canplay="play = true"
                        x-init="play = false" :class="{ 'hidden': !play }">
                        <source src="{{ asset('vid/ft (1).mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div x-show="!blackScreen" class="animate-fade absolute left-0 top-0 z-30 h-[120%] w-full bg-black/40">
                    </div>
                    <div class="absolute inset-0 z-0 flex items-center justify-center">
                        <div class="rounded-tr-2xl bg-transparent px-2 py-2 text-white" x-data="{ shown: false }"
                            x-intersect="shown = true">
                            <div x-show="shown" class="text-center sm:text-8xl text-4xl font-bold">
                                <p class="animate-fade tracking-widest">FAKULTAS</p>
                                <p class="animate-delay-200 animate-fade tracking-widest">TEKNIK</p>
                            </div>
                            <p x-show="shown" class="animate-delay-400 animate-fade max-w-xl text-center sm:text-xl text-sm">
                                {{ __('home.welcome') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-2 w-full"></div>
            <div class="mb-[3%] mt-[4%] h-[4px] w-full max-w-[var(--max-width)] overflow-hidden rounded-full px-[10%]"
                x-data="{ shown: false }" x-intersect="shown = true">
                <div x-show="shown" x-transition:enter="transition-all duration-700 ease-out"
                    x-transition:enter-start="w-0 opacity-0" x-transition:enter-end="w-full opacity-100"
                    class="bg-primary-dark relative z-10 h-full rounded-full">
                </div>
            </div>

            <div class="text-primary">.</div>
        </div>

    </section>

    <section class="bg-primary linked-1:py-16 relative w-full overflow-hidden py-10 transition-all"
        x-data="{ animate: false }" x-intersect:enter="setTimeout(()=>{
            animate=true
        }, 250)">
        <img class="absolute left-0 top-0 -z-0 h-full w-full object-cover" src="{{ asset('img/bg-linked.svg') }}" />
        <div class="relative z-10 mx-auto max-w-7xl px-10">
            <div class="text-center">
                <h2 x-data="{ hoverS: false }"
                    class="text-accent-white after:bg-accent-white/60 linked-1:text-4xl relative inline-block text-2xl font-bold after:absolute after:-bottom-1 after:left-1/2 after:block after:h-[4px] after:translate-x-[-50%] after:rounded-full after:transition-all after:duration-[1500ms]"
                    x-intersect:enter="hoverS=true" x-intersect:leave="hoverS=false"
                    x-bind:class="hoverS ? 'after:w-full' : 'after:w-1/4'">
                    {{ __('home.important_links') }}
                </h2>
            </div>
            <div
                class="linked-1:mt-14 linked-1:gap-x-5 mx-auto mt-8 flex max-w-[--max-width] flex-wrap items-center justify-center gap-x-2 gap-y-5">
                <div class="linked-1:w-[350px] flex w-[300px] flex-col items-center"
                    x-bind:class="animate ? 'animate-fade-up animate-delay-200' : 'opacity-0'">
                    <div
                        class="bg-accent-white border-primary hover:bg-primary-light border-primary hover:border-accent-white linked-1:gap-x-2 linked-1:p-5 group flex w-full cursor-pointer flex-row items-center justify-center gap-x-1 rounded-xl border-2 p-3 text-center transition-colors">
                        <svg class="group-hover:text-accent-white text-primary-light h-[35px] w-[35px] transition-colors"
                            fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M22.372,5.071l-10-4A1,1,0,0,0,11,2V6H2A1,1,0,0,0,1,7V22a1,1,0,0,0,1,1H22a1,1,0,0,0,1-1V6A1,1,0,0,0,22.372,5.071ZM3,8h8V21H3ZM13,21V3.477l8,3.2V8H15v2h6v2H15v2h6v2H15v2h6v3ZM7,12h3v2H7ZM4,12H6v2H4ZM7,9h3v2H7ZM4,9H6v2H4Zm3,6h3v2H7ZM4,15H6v2H4Zm3,3h3v2H7ZM4,18H6v2H4Z">
                                </path>
                            </g>
                        </svg>
                        <p
                            class="group-hover:text-accent-white text-primary-light linked-1:text-lg text-base font-bold transition-colors">
                            Governnment Public
                            Relations</p>
                    </div>
                </div>

                <div class="linked-1:w-[350px] flex w-[300px] flex-col items-center"
                    x-bind:class="animate ? 'animate-fade-up animate-delay-400' : 'opacity-0'">
                    <div
                        class="bg-accent-white border-primary hover:bg-primary-light border-primary hover:border-accent-white linked-1:gap-x-2 linked-1:p-5 group flex w-full cursor-pointer flex-row items-center justify-center gap-x-1 rounded-xl border-2 p-3 text-center transition-colors">
                        <svg class="group-hover:text-accent-white text-primary-light h-[35px] w-[35px] transition-colors"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path opacity="0.5" d="M8.7838 21.9999C7.0986 21.2478 5.70665 20.0758 4.79175 18.5068"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path opacity="0.5"
                                    d="M14.8252 2.18595C16.5021 1.70882 18.2333 2.16305 19.4417 3.39724"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path
                                    d="M4.0106 8.36655L3.63846 7.71539L4.0106 8.36655ZM6.50218 8.86743L7.15007 8.48962L6.50218 8.86743ZM3.2028 10.7531L2.55491 11.1309H2.55491L3.2028 10.7531ZM7.69685 3.37253L8.34474 2.99472V2.99472L7.69685 3.37253ZM8.53873 4.81624L7.89085 5.19405L8.53873 4.81624ZM10.4165 9.52517C10.6252 9.88299 11.0844 10.0039 11.4422 9.79524C11.8 9.58659 11.9209 9.12736 11.7123 8.76955L10.4165 9.52517ZM7.53806 12.1327C7.74672 12.4905 8.20594 12.6114 8.56376 12.4027C8.92158 12.1941 9.0425 11.7349 8.83384 11.377L7.53806 12.1327ZM4.39747 5.25817L3.74958 5.63598L4.39747 5.25817ZM11.8381 2.9306L12.486 2.55279V2.55279L11.8381 2.9306ZM14.3638 7.26172L15.0117 6.88391L14.3638 7.26172ZM16.0475 10.1491L16.4197 10.8003C16.5934 10.701 16.7202 10.5365 16.772 10.3433C16.8238 10.15 16.7962 9.94413 16.6954 9.77132L16.0475 10.1491ZM17.6632 5.37608L17.0153 5.75389L17.6632 5.37608ZM20.1888 9.7072L20.8367 9.32939V9.32939L20.1888 9.7072ZM6.99128 17.2497L7.63917 16.8719L6.99128 17.2497ZM16.9576 19.2533L16.5854 18.6021L16.9576 19.2533ZM13.784 15.3C13.9927 15.6578 14.4519 15.7787 14.8097 15.5701C15.1676 15.3614 15.2885 14.9022 15.0798 14.5444L13.784 15.3ZM4.38275 9.0177C5.01642 8.65555 5.64023 8.87817 5.85429 9.24524L7.15007 8.48962C6.4342 7.26202 4.82698 7.03613 3.63846 7.71539L4.38275 9.0177ZM3.63846 7.71539C2.44761 8.39597 1.83532 9.8969 2.55491 11.1309L3.85068 10.3753C3.64035 10.0146 3.75139 9.37853 4.38275 9.0177L3.63846 7.71539ZM7.04896 3.75034L7.89085 5.19405L9.18662 4.43843L8.34474 2.99472L7.04896 3.75034ZM7.89085 5.19405L10.4165 9.52517L11.7123 8.76955L9.18662 4.43843L7.89085 5.19405ZM8.83384 11.377L7.15007 8.48962L5.85429 9.24524L7.53806 12.1327L8.83384 11.377ZM7.15007 8.48962L5.04535 4.88036L3.74958 5.63598L5.85429 9.24524L7.15007 8.48962ZM5.57742 3.5228C6.21109 3.16065 6.8349 3.38327 7.04896 3.75034L8.34474 2.99472C7.62887 1.76712 6.02165 1.54123 4.83313 2.22048L5.57742 3.5228ZM4.83313 2.22048C3.64228 2.90107 3.02999 4.40199 3.74958 5.63598L5.04535 4.88036C4.83502 4.51967 4.94606 3.88363 5.57742 3.5228L4.83313 2.22048ZM11.1902 3.30841L13.7159 7.63953L15.0117 6.88391L12.486 2.55279L11.1902 3.30841ZM13.7159 7.63953L15.3997 10.5269L16.6954 9.77132L15.0117 6.88391L13.7159 7.63953ZM9.71869 3.08087C10.3524 2.71872 10.9762 2.94134 11.1902 3.30841L12.486 2.55279C11.7701 1.32519 10.1629 1.0993 8.9744 1.77855L9.71869 3.08087ZM8.9744 1.77855C7.78355 2.45914 7.17126 3.96006 7.89085 5.19405L9.18662 4.43843C8.97629 4.07774 9.08733 3.4417 9.71869 3.08087L8.9744 1.77855ZM17.0153 5.75389L19.5409 10.085L20.8367 9.32939L18.311 4.99827L17.0153 5.75389ZM15.5437 5.52635C16.1774 5.1642 16.8012 5.38682 17.0153 5.75389L18.311 4.99827C17.5952 3.77068 15.988 3.54478 14.7994 4.22404L15.5437 5.52635ZM14.7994 4.22404C13.6086 4.90462 12.9963 6.40555 13.7159 7.63953L15.0117 6.88391C14.8013 6.52322 14.9124 5.88718 15.5437 5.52635L14.7994 4.22404ZM2.55491 11.1309L6.34339 17.6276L7.63917 16.8719L3.85068 10.3753L2.55491 11.1309ZM16.5854 18.6021C13.2185 20.5264 9.24811 19.631 7.63917 16.8719L6.34339 17.6276C8.45414 21.2472 13.4079 22.1458 17.3297 19.9045L16.5854 18.6021ZM19.5409 10.085C21.1461 12.8377 19.9501 16.6792 16.5854 18.6021L17.3297 19.9045C21.2539 17.6618 22.9512 12.9554 20.8367 9.32939L19.5409 10.085ZM15.0798 14.5444C14.4045 13.3863 14.8772 11.6818 16.4197 10.8003L15.6754 9.49797C13.5735 10.6993 12.5995 13.2687 13.784 15.3L15.0798 14.5444Z"
                                    fill="currentColor"></path>
                            </g>
                        </svg>
                        <p
                            class="group-hover:text-accent-white text-primary-light linked-1:text-lg text-base font-bold transition-colors">
                            FT Care</p>
                    </div>
                </div>

                <div class="linked-1:w-[350px] flex w-[300px] flex-col items-center"
                    x-bind:class="animate ? 'animate-fade-up animate-delay-600' : 'opacity-0'">
                    <div
                        class="bg-accent-white border-primary hover:bg-primary-light border-primary hover:border-accent-white linked-1:gap-x-2 linked-1:p-5 group flex w-full cursor-pointer flex-row items-center justify-center gap-x-1 rounded-xl border-2 p-3 text-center transition-colors">
                        <svg class="group-hover:text-accent-white text-primary-light h-[35px] w-[35px] transition-colors"
                            viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" fill="currentColor">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <title>report-text</title>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                    fill-rule="evenodd">
                                    <g id="add" fill="currentColor"
                                        transform="translate(42.666667, 85.333333)">
                                        <path
                                            d="M341.333333,1.42108547e-14 L426.666667,85.3333333 L426.666667,341.333333 L3.55271368e-14,341.333333 L3.55271368e-14,1.42108547e-14 L341.333333,1.42108547e-14 Z M330.666667,42.6666667 L42.6666667,42.6666667 L42.6666667,298.666667 L384,298.666667 L384,96 L330.666667,42.6666667 Z M149.333333,234.666667 L149.333333,266.666667 L85.3333333,266.666667 L85.3333333,234.666667 L149.333333,234.666667 Z M341.333333,234.666667 L341.333333,266.666667 L192,266.666667 L192,234.666667 L341.333333,234.666667 Z M149.333333,170.666667 L149.333333,202.666667 L85.3333333,202.666667 L85.3333333,170.666667 L149.333333,170.666667 Z M341.333333,170.666667 L341.333333,202.666667 L192,202.666667 L192,170.666667 L341.333333,170.666667 Z M149.333333,106.666667 L149.333333,138.666667 L85.3333333,138.666667 L85.3333333,106.666667 L149.333333,106.666667 Z M341.333333,106.666667 L341.333333,138.666667 L192,138.666667 L192,106.666667 L341.333333,106.666667 Z"
                                            id="Combined-Shape"> </path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                        <p
                            class="group-hover:text-accent-white text-primary-light linked-1:text-lg text-base font-bold transition-colors">
                            {{ __('home.money_report') }}</p>
                    </div>
                </div>

                <div class="linked-1:w-[350px] flex w-[300px] flex-col items-center"
                    x-bind:class="animate ? 'animate-fade-up animate-delay-800' : 'opacity-0'">
                    <div
                        class="bg-accent-white border-primary hover:bg-primary-light border-primary hover:border-accent-white linked-1:gap-x-2 linked-1:p-5 group flex w-full cursor-pointer flex-row items-center justify-center gap-x-1 rounded-xl border-2 p-3 text-center transition-colors">
                        <svg class="group-hover:text-accent-white text-primary-light h-[35px] w-[35px] transition-colors"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M14 4C14 5.10457 13.1046 6 12 6C10.8954 6 10 5.10457 10 4C10 2.89543 10.8954 2 12 2C13.1046 2 14 2.89543 14 4Z"
                                    stroke="currentColor" stroke-width="1.5"></path>
                                <path
                                    d="M6.04779 10.849L6.28497 10.1375H6.28497L6.04779 10.849ZM8.22309 11.5741L7.98592 12.2856H7.98592L8.22309 11.5741ZM9.01682 13.256L8.31681 12.9868H8.31681L9.01682 13.256ZM7.77003 16.4977L8.47004 16.7669H8.47004L7.77003 16.4977ZM17.9522 10.849L17.715 10.1375H17.715L17.9522 10.849ZM15.7769 11.5741L16.0141 12.2856H16.0141L15.7769 11.5741ZM14.9832 13.256L15.6832 12.9868L14.9832 13.256ZM16.23 16.4977L15.53 16.7669L16.23 16.4977ZM10.4242 17.7574L11.0754 18.1295L10.4242 17.7574ZM12 14.9997L12.6512 14.6276C12.5177 14.394 12.2691 14.2497 12 14.2497C11.7309 14.2497 11.4823 14.394 11.3488 14.6276L12 14.9997ZM17.1465 7.8969L16.9894 7.16355L17.1465 7.8969ZM15.249 8.30353L15.4061 9.03688V9.03688L15.249 8.30353ZM8.75102 8.30353L8.90817 7.57018V7.57018L8.75102 8.30353ZM6.85345 7.89691L6.69631 8.63026L6.85345 7.89691ZM13.5758 17.7574L12.9246 18.1295V18.1295L13.5758 17.7574ZM15.0384 8.34826L14.8865 7.61381L14.8865 7.61381L15.0384 8.34826ZM8.96161 8.34826L8.80969 9.08272L8.80969 9.08272L8.96161 8.34826ZM15.2837 11.7666L15.6777 12.4048L15.2837 11.7666ZM14.8182 12.753L15.5613 12.6514V12.6514L14.8182 12.753ZM8.71625 11.7666L8.3223 12.4048H8.3223L8.71625 11.7666ZM9.18177 12.753L9.92485 12.8546V12.8546L9.18177 12.753ZM5.81062 11.5605L7.98592 12.2856L8.46026 10.8626L6.28497 10.1375L5.81062 11.5605ZM8.31681 12.9868L7.07002 16.2284L8.47004 16.7669L9.71683 13.5252L8.31681 12.9868ZM17.715 10.1375L15.5397 10.8626L16.0141 12.2856L18.1894 11.5605L17.715 10.1375ZM14.2832 13.5252L15.53 16.7669L16.93 16.2284L15.6832 12.9868L14.2832 13.5252ZM11.0754 18.1295L12.6512 15.3718L11.3488 14.6276L9.77299 17.3853L11.0754 18.1295ZM16.9894 7.16355L15.0918 7.57017L15.4061 9.03688L17.3037 8.63026L16.9894 7.16355ZM8.90817 7.57018L7.0106 7.16355L6.69631 8.63026L8.59387 9.03688L8.90817 7.57018ZM11.3488 15.3718L12.9246 18.1295L14.227 17.3853L12.6512 14.6276L11.3488 15.3718ZM15.0918 7.57017C14.9853 7.593 14.9356 7.60366 14.8865 7.61381L15.1903 9.08272C15.2458 9.07123 15.3016 9.05928 15.4061 9.03688L15.0918 7.57017ZM8.59387 9.03688C8.6984 9.05928 8.75416 9.07123 8.80969 9.08272L9.11353 7.61381C9.06443 7.60366 9.01468 7.593 8.90817 7.57018L8.59387 9.03688ZM14.8865 7.61381C12.9823 8.00768 11.0177 8.00768 9.11353 7.61381L8.80969 9.08272C10.9143 9.51805 13.0857 9.51805 15.1903 9.08272L14.8865 7.61381ZM9.14506 19.2497C9.94287 19.2497 10.6795 18.8222 11.0754 18.1295L9.77299 17.3853C9.64422 17.6107 9.40459 17.7497 9.14506 17.7497V19.2497ZM15.53 16.7669C15.7122 17.2406 15.3625 17.7497 14.8549 17.7497V19.2497C16.4152 19.2497 17.4901 17.6846 16.93 16.2284L15.53 16.7669ZM15.5397 10.8626C15.3178 10.9366 15.0816 11.01 14.8898 11.1283L15.6777 12.4048C15.6688 12.4102 15.6763 12.4037 15.7342 12.3818C15.795 12.3588 15.877 12.3313 16.0141 12.2856L15.5397 10.8626ZM15.6832 12.9868C15.6313 12.8519 15.6004 12.7711 15.5795 12.7095C15.5596 12.651 15.5599 12.6411 15.5613 12.6514L14.0751 12.8546C14.1057 13.0779 14.1992 13.3069 14.2832 13.5252L15.6832 12.9868ZM14.8898 11.1283C14.3007 11.492 13.9814 12.1687 14.0751 12.8546L15.5613 12.6514C15.5479 12.5534 15.5935 12.4567 15.6777 12.4048L14.8898 11.1283ZM18.25 9.39526C18.25 9.73202 18.0345 10.031 17.715 10.1375L18.1894 11.5605C19.1214 11.2499 19.75 10.3777 19.75 9.39526H18.25ZM7.07002 16.2284C6.50994 17.6846 7.58484 19.2497 9.14506 19.2497V17.7497C8.63751 17.7497 8.28784 17.2406 8.47004 16.7669L7.07002 16.2284ZM7.98592 12.2856C8.12301 12.3313 8.20501 12.3588 8.26583 12.3818C8.32371 12.4037 8.33115 12.4102 8.3223 12.4048L9.1102 11.1283C8.91842 11.01 8.68219 10.9366 8.46026 10.8626L7.98592 12.2856ZM9.71683 13.5252C9.80081 13.3069 9.89432 13.0779 9.92485 12.8546L8.43868 12.6514C8.44009 12.6411 8.4404 12.6509 8.42051 12.7095C8.3996 12.7711 8.36869 12.8519 8.31681 12.9868L9.71683 13.5252ZM8.3223 12.4048C8.40646 12.4567 8.45208 12.5534 8.43868 12.6514L9.92485 12.8546C10.0186 12.1687 9.69929 11.492 9.1102 11.1283L8.3223 12.4048ZM4.25 9.39526C4.25 10.3777 4.87863 11.2499 5.81062 11.5605L6.28497 10.1375C5.96549 10.031 5.75 9.73202 5.75 9.39526H4.25ZM5.75 9.39526C5.75 8.89717 6.20927 8.52589 6.69631 8.63026L7.0106 7.16355C5.58979 6.8591 4.25 7.9422 4.25 9.39526H5.75ZM12.9246 18.1295C13.3205 18.8222 14.0571 19.2497 14.8549 19.2497V17.7497C14.5954 17.7497 14.3558 17.6107 14.227 17.3853L12.9246 18.1295ZM19.75 9.39526C19.75 7.9422 18.4102 6.85909 16.9894 7.16355L17.3037 8.63026C17.7907 8.52589 18.25 8.89717 18.25 9.39526H19.75Z"
                                    fill="currentColor"></path>
                                <path opacity="0.5"
                                    d="M19.4537 14.5C21.0372 15.2961 22 16.3475 22 17.5C22 19.9853 17.5228 22 12 22C6.47715 22 2 19.9853 2 17.5C2 16.3475 2.96285 15.2961 4.54631 14.5"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </g>
                        </svg>
                        <p
                            class="group-hover:text-accent-white text-primary-light linked-1:text-lg text-base font-bold transition-colors">
                            PLD</p>
                    </div>
                </div>

                <div class="linked-1:w-[350px] flex w-[300px] flex-col items-center"
                    x-bind:class="animate ? 'animate-fade-up animate-delay-1000' : 'opacity-0'">
                    <div
                        class="bg-accent-white border-primary hover:bg-primary-light border-primary hover:border-accent-white linked-1:gap-x-2 linked-1:p-5 group flex w-full cursor-pointer flex-row items-center justify-center gap-x-1 rounded-xl border-2 p-3 text-center transition-colors">
                        <svg class="group-hover:text-accent-white text-primary-light h-[35px] w-[35px] transition-colors"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M16 3.98999H8C6.93913 3.98999 5.92178 4.41135 5.17163 5.1615C4.42149 5.91164 4 6.92912 4 7.98999V17.99C4 19.0509 4.42149 20.0682 5.17163 20.8184C5.92178 21.5685 6.93913 21.99 8 21.99H16C17.0609 21.99 18.0783 21.5685 18.8284 20.8184C19.5786 20.0682 20 19.0509 20 17.99V7.98999C20 6.92912 19.5786 5.91164 18.8284 5.1615C18.0783 4.41135 17.0609 3.98999 16 3.98999Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M9 2V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M15 2V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M8 16H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M8 12H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </g>
                        </svg>
                        <p
                            class="group-hover:text-accent-white text-primary-light linked-1:text-lg text-base font-bold transition-colors">
                            PPID</p>
                    </div>
                </div>

                <div class="linked-1:w-[350px] flex w-[300px] flex-col items-center"
                    x-bind:class="animate ? 'animate-fade-up animate-delay-1200' : 'opacity-0'">
                    <div
                        class="bg-accent-white border-primary hover:bg-primary-light border-primary hover:border-accent-white linked-1:gap-x-2 linked-1:p-5 group flex w-full cursor-pointer flex-row items-center justify-center gap-x-1 rounded-xl border-2 p-3 text-center transition-colors">

                        <svg class="group-hover:text-accent-white text-primary-light h-[35px] w-[35px] transition-colors"
                            fill="currentColor" viewBox="-1 0 19 19" xmlns="http://www.w3.org/2000/svg"
                            class="cf-icon-svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M16.141 7.905c.24.102.24.269 0 .37l-7.204 3.058a1.288 1.288 0 0 1-.874 0L.859 8.276c-.24-.102-.24-.27 0-.371l7.204-3.058a1.287 1.287 0 0 1 .874 0zm-6.833 4.303 3.983-1.69v2.081c0 1.394-2.145 2.524-4.791 2.524s-4.79-1.13-4.79-2.524v-2.082l3.982 1.69a2.226 2.226 0 0 0 1.616 0zm4.94 1.677h1.642v-1.091a.822.822 0 1 0-1.643 0zm.82-3.603a.554.554 0 1 0-.553-.554.554.554 0 0 0 .554.554zm0 1.415a.554.554 0 1 0-.553-.555.554.554 0 0 0 .554.555z">
                                </path>
                            </g>
                        </svg>
                        <p
                            class="group-hover:text-accent-white text-primary-light linked-1:text-lg text-base font-bold transition-colors">
                            Merdeka Belajar</p>
                    </div>
                </div>

                <div class="linked-1:w-[350px] flex w-[300px] flex-col items-center"
                    x-bind:class="animate ? 'animate-fade-up animate-delay-1400' : 'opacity-0'">
                    <div
                        class="bg-accent-white border-primary hover:bg-primary-light border-primary hover:border-accent-white linked-1:gap-x-2 linked-1:p-5 group flex w-full cursor-pointer flex-row items-center justify-center gap-x-1 rounded-xl border-2 p-3 text-center transition-colors">

                        <svg class="group-hover:text-accent-white text-primary-light h-[35px] w-[35px] transition-colors"
                            fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"
                            enable-background="new 0 0 52 52" xml:space="preserve">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <path
                                        d="M22.8,45.7v1c0,1.2-0.9,2.1-2.1,2.1h0H4.1c-1.2,0-2.1-0.9-2.1-2.1l0,0v-1c0-2.5,3-4.1,5.7-5.3 c0.1,0,0.2-0.1,0.3-0.2c0.2-0.1,0.5-0.1,0.7,0c1.1,0.7,2.4,1.1,3.8,1.1c1.3,0,2.6-0.4,3.8-1.1c0.2-0.1,0.4-0.1,0.6,0 c0.1,0,0.2,0.1,0.3,0.2C19.9,41.7,22.8,43.2,22.8,45.7z">
                                    </path>
                                    <ellipse cx="12.4" cy="33.7" rx="5.2" ry="5.7"></ellipse>
                                    <path
                                        d="M34.8,3.2L34.8,3.2c-8.5,0-15.3,6.5-15.3,14.5c0,2.5,0.7,5,2,7.2c0.1,0.2,0.2,0.5,0.2,0.8L20,30.3 c-0.2,0.6,0.2,1.1,0.7,1.3c0.2,0.1,0.4,0.1,0.6,0l4.5-1.6c0.3-0.1,0.6-0.1,0.8,0.1c2.4,1.4,5.2,2.2,8,2.2c8.5,0,15.3-6.6,15.3-14.6 C50,9.7,43.1,3.2,34.8,3.2z M33.7,8.9h3v7l-0.3,4.6H34l-0.2-4.6V8.9z M35.2,26.2c-1.4,0-1.8-0.8-1.8-1.8c0-1,0.4-1.8,1.8-1.8 c1.4,0,1.8,0.8,1.8,1.8C37.1,25.4,36.6,26.2,35.2,26.2z">
                                    </path>
                                </g>
                            </g>
                        </svg>
                        <p
                            class="group-hover:text-accent-white text-primary-light linked-1:text-lg text-base font-bold transition-colors">
                            lapor.go.id</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="linked-1:pt-20 pt-10">
        <div class="text-center">
            <h2 x-data="{ hoverS: false }"
                class="text-primary linked-1:text-4xl relative inline-block text-2xl font-bold after:absolute after:-bottom-1 after:left-1/2 after:block after:h-[4px] after:translate-x-[-50%] after:rounded-full after:bg-green-400 after:transition-all after:duration-[1500ms]"
                x-intersect:enter="hoverS=true" x-intersect:leave="hoverS=false"
                x-bind:class="hoverS ? 'after:w-full' : 'after:w-1/4'">
                FTnews
            </h2>
        </div>
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
                <div class="mx-auto flex flex-row flex-wrap items-start justify-center gap-x-7 gap-y-7 ftnews-1:px-5 px-0"
                    x-intersect="scrolled=true">
                    <template x-cloak x-for="(data, i) in news" :key="i">
                        <div class="group ftnews-1:w-[405px] w-full cursor-pointer"
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
            <!-- <button wire:click="makeCategory">Make Category</button>
            <button wire:click="createNews">Make News</button> -->
        </div>
    </section>

    <section class="relative w-full" x-data="{
        height: 0,
    }">
        <img class="absolute left-0 top-0 z-10 hidden h-full w-full object-cover opacity-20 xl:block"
            src="{{ asset('img/bg-poly.svg') }}" />
        <div class="bg-primary/10 absolute left-0 right-0 h-full w-full"></div>
        <div class="bg-primary absolute left-1/2 right-0 top-0 z-0 w-[405px] -translate-x-1/2 transform xl:h-full">
        </div>

        <div
            class="relative z-20 mx-auto mt-14 flex max-w-[--max-width] flex-wrap items-center justify-center gap-x-7 xl:items-start">
            <div class="event-1:w-[405px] w-[500px] px-5 py-10" x-data="{ scrolled: false }"
                x-intersect="scrolled = true">
                <div class="event-1:text-left text-center">
                    <h2 x-data="{ hoverS: false }"
                        class="text-primary linked-1:text-3xl relative inline-block text-2xl font-bold after:absolute after:-bottom-1 after:left-1/2 after:block after:h-[4px] after:translate-x-[-50%] after:rounded-full after:bg-green-400 after:transition-all after:duration-[1500ms]"
                        x-intersect:enter="hoverS=true" x-intersect:leave="hoverS=false"
                        x-bind:class="hoverS ? 'after:w-full' : 'after:w-1/4'">
                        MBKM News
                    </h2>
                </div>
                <div class="mt-7 flex flex-col gap-y-5">
                    <template x-cloak x-if="!mbkm || (Array.isArray(mbkm) && mbkm.length == 0)">
                        <template x-cloak x-for="i in [1,2,3,4,5]" :key="i">
                            <div class="">
                                <div class="mt-2 h-5 max-w-[250px] rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                <div class="mt-2 h-5 w-full rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                <div class="mt-2 h-5 w-full rounded-full bg-gray-200 dark:bg-gray-700"></div>
                            </div>
                        </template>
                    </template>

                    <template x-cloak x-if="mbkm && Array.isArray(mbkm) && mbkm.length > 0">
                        <template x-cloak x-for="(data, i) in mbkm" :key="i">
                            <div class="cursor-pointer transition-all hover:translate-x-10 hover:scale-110"
                                @click="goToNews(data.id)"
                                x-bind:class="scrolled ? `animate-fade-right animate-delay-${i*2}00 opacity-100` : 'opacity-0'">
                                <p x-text="formatDate(data.created_at)" class="text-primary text-sm"></p>
                                <p x-text="data.title" class="text-primary-dark line-clamp-3 text-lg"></p>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
            <div class="relative bg-primary event-1:w-[405px] w-[500px] px-5 py-10 xl:bg-transparent" x-data="{ scrolled: false }"
                x-intersect="scrolled = true">
                <img class="absolute left-0 top-0 z-10 block h-full w-full object-cover opacity-10 xl:hidden"
                    src="{{ asset('img/bg-poly.svg') }}" />
                <div class="event-1:text-left relative z-20 text-center">
                    <h2 x-data="{ hoverS: false }"
                        class="text-accent-white after:bg-accent-white/60 linked-1:text-3xl relative inline-block text-2xl font-bold after:absolute after:-bottom-1 after:left-1/2 after:block after:h-[4px] after:translate-x-[-50%] after:rounded-full after:transition-all after:duration-[1500ms]"
                        x-intersect:enter="hoverS=true" x-intersect:leave="hoverS=false"
                        x-bind:class="hoverS ? 'after:w-full' : 'after:w-1/4'">
                        FT Events
                    </h2>
                </div>
                <div class="relative z-20 mt-7 flex flex-col gap-y-5">
                    <template x-cloak x-if="!events || (Array.isArray(events) && events.length == 0)">
                        <template x-cloak x-for="i in [1,2,3,4,5]" :key="i">
                            <div>
                                <div class="mt-2 h-5 max-w-[250px] rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                <div class="mt-2 h-5 w-full rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                <div class="mt-2 h-5 w-full rounded-full bg-gray-200 dark:bg-gray-700"></div>
                            </div>
                        </template>
                    </template>

                    <template x-cloak x-if="events && Array.isArray(events) && events.length > 0">
                        <template x-cloak x-for="(data, i) in events" :key="i">
                            <div class="flex cursor-pointer flex-row items-start gap-x-2 transition-all hover:translate-x-2 hover:scale-110"
                                @click="goToNews(data.id)"
                                x-bind:class="scrolled ? `animate-fade-right animate-delay-${i*2}00 opacity-100` : 'opacity-0'">
                                <div class="flex min-w-[70px] flex-col">
                                    <p x-text="formatMonthYear(data.created_at)"
                                        class="text-accent-white bg-red-700/80 p-1 text-center text-[12px] italic"></p>
                                    <p x-text="formatDay(data.created_at)"
                                        class="text-primary-dark bg-accent-white text-center text-2xl font-bold">
                                    </p>
                                </div>
                                <p x-text="data.title" class="text-accent-white line-clamp-3 text-xl"></p>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
            <div class="event-1:w-[405px] w-[500px] px-5 py-10" x-data="{ scrolled: false }"
                x-intersect="scrolled = true">
                <div class="event-1:text-left text-center">
                    <h2 x-data="{ hoverS: false }"
                        class="text-primary linked-1:text-3xl relative inline-block text-2xl font-bold after:absolute after:-bottom-1 after:left-1/2 after:block after:h-[4px] after:translate-x-[-50%] after:rounded-full after:bg-green-400 after:transition-all after:duration-[1500ms]"
                        x-intersect:enter="hoverS=true" x-intersect:leave="hoverS=false"
                        x-bind:class="hoverS ? 'after:w-full' : 'after:w-1/4'">
                        Pengumuman FT
                    </h2>
                </div>
                <div class="mt-7 flex flex-col gap-y-5">
                    <template x-cloak x-if="!pengumuman || (Array.isArray(pengumuman) && pengumuman.length == 0)">
                        <template x-cloak x-for="i in [1,2,3,4,5]" :key="i">
                            <div class="">
                                <div class="mt-2 h-5 max-w-[250px] rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                <div class="mt-2 h-5 w-full rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                <div class="mt-2 h-5 w-full rounded-full bg-gray-200 dark:bg-gray-700"></div>
                            </div>
                        </template>
                    </template>

                    <template x-cloak x-if="pengumuman && Array.isArray(pengumuman) && pengumuman.length > 0">
                        <template x-cloak x-for="(data, i) in pengumuman" :key="i">
                            <div class="cursor-pointer transition-all hover:translate-x-10 hover:scale-110"
                                @click="goToNews(data.id)"
                                x-bind:class="scrolled ? `animate-fade-right animate-delay-${i*2}00 opacity-100` : 'opacity-0'">
                                <p x-text="formatDate(data.created_at)" class="text-primary text-sm"></p>
                                <p x-text="data.title" class="text-primary-dark line-clamp-3 text-lg"></p>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </section>


</div>
<script>
    function initHome() {
        return {
            news: @entangle('news').live,
            mbkm: @entangle('mbkm').live,
            events: @entangle('events').live,
            pengumuman: @entangle('pengumuman').live,
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
                if (isNaN(date)) return ''; // Pastikan tanggal valid

                return date.getDate().toString(); // Ambil hanya tanggal (hari dalam angka)
            },

            goToNews(id) {
                const dummy = '{{ route('news-page', ['id' => '__DUMMY_ID__']) }}'.replace('__DUMMY_ID__', id);
                goToPage(dummy);
            }
        }
    }
</script>
