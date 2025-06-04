<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades;
use App\Models\Menu;
use Carbon\Carbon;

new class extends Component {
    public $menus;
    public function mount()
    {
        $this->getMenu();
    }

    public function getMenu()
    {
        try {
            // $menu = Menu::where('isActive', true)->with('pages')->get();
            // $menu = Menu::where('isActive', true)
            //     ->with([
            //         'pages' => function ($query) {
            //             $query->where('isReleased', true)->whereDate('release', '<=', Carbon::today());
            //         },
            //     ])
            //     ->get();
            $menu = Menu::where('isActive', true)
                ->with([
                    'pages' => function ($query) {
                        $query->where('isReleased', true)->where(function ($q) {
                            $q->whereNull('release')->orWhereDate('release', '<=', Carbon::today());
                        });
                    },
                ])
                ->get();

            $this->menus = $menu->toArray();
        } catch (\Exception $e) {
            Log::error('Error fetching menus: ' . $e->getMessage());
            $this->menus = [];
        }
    }
}; ?>

<header class="animate-fade-down nav-3:p-0 fixed top-0 z-50 w-full py-2" x-data="{
    scrolled: false,
    must_open: false,
    nav: false,
    get tinyLayer() {
        const match = this.listTran.find(item => item.name === this.path);
        if (match) {
            return window.innerWidth > 600 ? match.xl : match.sm;
        }
        return window.innerWidth > 600 ? 400 : 50; // default fallback
    },
    path: window.location.pathname,
    listTran: [{ name: '/', xl: 600, sm: 50 },
        { name: '/search', xl: 300, sm: 50 },
    ],
    get isInList() {
        return this.listTran.some(item => item.name === this.path);
    },
    blackScreen: false,
    menus: @entangle('menus'),
}"
    @scroll.window="scrolled = window.scrollY > tinyLayer" x-init="scrolled = window.scrollY > 400">
    <div x-cloak
        class="bg-primary border-primary-dark absolute left-0 top-0 z-10 h-full w-full border-b-2 shadow-2xl transition-all"
        x-show="scrolled || must_open || !isInList" x-transition:enter-start="opacity-0 -translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"></div>
    <div x-cloak class="absolute left-0 top-0 h-full w-full bg-black/30 shadow-2xl transition-all"
        x-show="!scrolled || !must_open || isInList" x-transition:enter-start="opacity-0 -translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"></div>

    <div class="nav-2:px-10 mx-auto flex w-full max-w-[var(--max-width)] flex-row px-5 transition-all">
        <div class="text-primary z-10 flex w-[350px] cursor-pointer flex-row items-center justify-center gap-x-2 rounded-xl"
            @click.prevent="
    if (window.location.pathname != '/') {
        goToPage('{{ route('home') }}')
    }
">
            <img class="nav-2:w-[65px] w-[50px] min-w-[40px] max-w-[80px]" src="{{ asset('img/unimed.png') }}"
                alt="{{ config('app.name') }}">
            <div class="flex flex-col">
                <p class="text-accent-white font-merriweather nav-2:text-xl w-[192px] text-lg font-bold">FAKULTAS TEKNIK
                </p>
                <p class="text-accent-white font-merriweather nav-2:text-3xl text-xl font-bold">UNIMED</p>
            </div>
        </div>
        <div class="z-10 flex w-full flex-row items-center justify-end gap-x-2 text-lg font-bold text-white">
            <nav x-cloak class="nav-1:flex animate-fade-down relative hidden flex-row items-center gap-x-5"
                x-data="{
                    open: '',
                    opened(con) {
                        must_open = true;
                        this.open = con;
                    },
                    closed() {
                        must_open = false;
                        this.open = '';
                    },
                    iniNav() {
                        console.log('nav', this.menus);
                    },
                    resolvePath(itemPath, childPath) {
                        try {
                            new URL(childPath);
                            return childPath;
                        } catch (e) {
                            return itemPath + childPath;
                        }
                    }
                
                }" @mouseleave="closed" x-init="iniNav">

                <div
                    class="border-accent-white hover:border-secondary-warn group cursor-pointer overflow-hidden border-b-2">
                    <svg class="animate-fade-up group-hover:text-secondary-warn w-[35px] text-white hover:translate-y-2 hover:animate-bounce"
                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path opacity="0.4"
                                d="M2.09961 21.9998V6.02979C2.09961 4.01979 3.09966 3.00977 5.08966 3.00977H11.3196C13.3096 3.00977 14.2996 4.01979 14.2996 6.02979V21.9998"
                                fill="currentColor"></path>
                            <path
                                d="M10.7508 9H5.80078C5.39078 9 5.05078 8.66 5.05078 8.25C5.05078 7.84 5.39078 7.5 5.80078 7.5H10.7508C11.1608 7.5 11.5008 7.84 11.5008 8.25C11.5008 8.66 11.1608 9 10.7508 9Z"
                                fill="currentColor"></path>
                            <path
                                d="M10.7508 12.75H5.80078C5.39078 12.75 5.05078 12.41 5.05078 12C5.05078 11.59 5.39078 11.25 5.80078 11.25H10.7508C11.1608 11.25 11.5008 11.59 11.5008 12C11.5008 12.41 11.1608 12.75 10.7508 12.75Z"
                                fill="currentColor"></path>
                            <path
                                d="M8.25 22.75C7.84 22.75 7.5 22.41 7.5 22V18.25C7.5 17.84 7.84 17.5 8.25 17.5C8.66 17.5 9 17.84 9 18.25V22C9 22.41 8.66 22.75 8.25 22.75Z"
                                fill="currentColor"></path>
                            <path
                                d="M23 21.2501H20.73V18.2501C21.68 17.9401 22.37 17.0501 22.37 16.0001V14.0001C22.37 12.6901 21.3 11.6201 19.99 11.6201C18.68 11.6201 17.61 12.6901 17.61 14.0001V16.0001C17.61 17.0401 18.29 17.9201 19.22 18.2401V21.2501H1C0.59 21.2501 0.25 21.5901 0.25 22.0001C0.25 22.4101 0.59 22.7501 1 22.7501H19.93C19.95 22.7501 19.96 22.7601 19.98 22.7601C20 22.7601 20.01 22.7501 20.03 22.7501H23C23.41 22.7501 23.75 22.4101 23.75 22.0001C23.75 21.5901 23.41 21.2501 23 21.2501Z"
                                fill="currentColor"></path>
                        </g>
                    </svg>
                </div>
                <template x-if="menus && menus.length > 0">
                    <template x-for="(item, index) in menus" :key="item.id">
                        <div class="relative cursor-pointer py-10">
                            <template x-if="item.pages && item.pages.length > 0">
                                <div class="flex flex-row items-center gap-x-1 transition-colors"
                                    x-bind:class="open == item.id ? 'text-secondary-warn' : 'text-accent-white'"
                                    @mouseenter="opened(item.id)">
                                    <span x-text="item.name"></span>
                                    <div class="flex flex-row">
                                        <div class="bg-accent-white h-[2px] w-[10px] rounded-full transition-all"
                                            x-bind:class="open == item.id ? 'rotate-0 bg-secondary-warn' : 'rotate-45 bg-accent-white'">
                                        </div>
                                        <div class="bg-accent-white relative right-1 h-[2px] w-[10px] rounded-full transition-all"
                                            x-bind:class="open == item.id ? 'rotate-0 bg-secondary-warn' :
                                                '-rotate-45 bg-accent-white'">
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!item.pages || item.pages.length == 0">
                                <div @click="goToPage(item.path)"
                                    class="relative cursor-pointer px-2 text-xl before:absolute before:bottom-0 before:left-0 before:h-full before:w-full before:origin-bottom before:scale-y-[0.35] before:bg-green-500 before:transition-transform before:duration-500 before:ease-in-out hover:before:scale-y-100">
                                    <span class="relative" x-text="item.name"></span>
                                </div>
                            </template>
                        </div>
                    </template>
                </template>

                <!-- <div class="relative cursor-pointer py-10">
                    <div class="flex flex-row items-center gap-x-1 transition-colors"
                        x-bind:class="open == 'about' ? 'text-secondary-warn' : 'text-accent-white'"
                        @mouseenter="opened('about')">
                        {{ __('nav.about') }}
                        <div class="flex flex-row">
                            <div class="bg-accent-white h-[2px] w-[10px] rounded-full transition-all"
                                x-bind:class="open == 'about' ? 'rotate-0 bg-secondary-warn' : 'rotate-45 bg-accent-white'">
                            </div>
                            <div class="bg-accent-white relative right-1 h-[2px] w-[10px] rounded-full transition-all"
                                x-bind:class="open == 'about' ? 'rotate-0 bg-secondary-warn' : '-rotate-45 bg-accent-white'">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative cursor-pointer py-10">
                    <div class="flex flex-row items-center gap-x-1"
                        x-bind:class="open == 'jurusan' ? 'text-secondary-warn' : 'text-accent-white'"
                        @mouseenter="opened('jurusan')">
                        {{ __('nav.jurusan_dan_program_studi') }}
                        <div class="flex flex-row">
                            <div class="bg-accent-white h-[2px] w-[10px] rounded-full transition-all"
                                x-bind:class="open == 'jurusan' ? 'rotate-0' : 'rotate-45'"></div>
                            <div class="bg-accent-white relative right-1 h-[2px] w-[10px] rounded-full transition-all"
                                x-bind:class="open == 'jurusan' ? 'rotate-0' : '-rotate-45'"></div>
                        </div>
                    </div>
                </div>
                <div class="relative cursor-pointer py-10">
                    <div class="flex flex-row items-center gap-x-1" @mouseenter="opened('campus')">
                        {{ __('nav.campus_alive') }}
                        <div class="flex flex-row">
                            <div class="bg-accent-white h-[2px] w-[10px] rounded-full transition-all"
                                x-bind:class="open == 'campus' ? 'rotate-0' : 'rotate-45'"></div>
                            <div class="bg-accent-white relative right-1 h-[2px] w-[10px] rounded-full transition-all"
                                x-bind:class="open == 'campus' ? 'rotate-0' : '-rotate-45'"></div>
                        </div>
                    </div>
                </div> -->

                <template x-if="menus && menus.length > 0">
                    <template x-for="(item, index) in menus" :key="">
                        <div class="bg-primary-dark absolute left-0 top-full grid w-full grid-cols-2 flex-wrap gap-x-[10px]"
                            x-show="open==item.id" x-transition x-cloak>
                            <template x-if="item.pages && item.pages.length > 0">
                                <template x-for="(child, col) in item.pages" :key="child.id">
                                    <div class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all"
                                        @click="goToPage(resolvePath(item.path, child.path)); " x-text="child.name">
                                    </div>
                                </template>
                            </template>
                            <template x-if="!item.pages || item.pages.length === 0">
                                <div class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all"
                                    @click="goToPage(item.path)" x-text="item.name">
                                </div>
                            </template>
                        </div>
                    </template>
                </template>
                <!-- <div class="bg-primary-dark absolute left-0 top-full grid w-full grid-cols-2 flex-wrap gap-x-[10px]"
                    x-show="open=='about'" x-transition x-cloak>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        {{ __('nav.profile_short') }}
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        {{ __('nav.symbols.mottos.logos') }}</div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        {{ __('nav.hymne.mars') }}</div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        {{ __('nav.cooperation') }}
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        {{ __('nav.performance') }}
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        {{ __('nav.statistic') }}</div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        {{ __('nav.history') }}</div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        {{ __('nav.vision.misi') }}
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        {{ __('nav.organize') }}</div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        {{ __('nav.akreditasi') }}
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        {{ __('nav.documents') }}</div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        {{ __('nav.info.money') }}
                    </div>
                </div>

                <div x-cloak
                    class="bg-primary-dark absolute left-0 top-full grid w-full grid-cols-2 flex-wrap gap-x-[10px]"
                    x-show="open=='jurusan'" x-transition>
                    <div class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all"
                        @Click="goToPage('https://tatabusana.unimed.ac.id/')">
                        Pendidikan Tata Busana
                    </div>
                    <div class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all"
                        @click="goToPage('https://ptik.unimed.ac.id/')">
                        Pendidikan Teknik Informatika dan Komputer</div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        Pendidikan Tata Boga</div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        Pendidikan Teknik Bangunan
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        Manajemen Konstruksi
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        Arsitektur</div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        Pendidikan Tata Rias</div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        Gizi
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        Pendidikan Teknik Mesin</div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        Pendidikan Teknik Elektro
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        Teknik Elektro</div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        Pendidikan Teknik Otomotif
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        Profesi Insinyur
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        D3 Teknik Sipil
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        D3 Teknik Mesin
                    </div>
                    <div
                        class="hover:text-secondary-warn border-accent-white/20 hover:border-secondary-warn min-w-[200px] cursor-pointer border-b-2 border-dotted px-4 pb-2 pt-2 text-base transition-all">
                        S2-Pendidikan Guru Vokasi
                    </div>
                </div> -->


                <!-- <div @click="goToPage('{{ route('profile') }}')">{{ __('nav.profile') }}</div>
            <div>PPID</div>
            <div>{{ __('nav.mutu_internal') }}</div>
            <div
                class="relative cursor-pointer px-2 text-2xl before:absolute before:bottom-0 before:left-0 before:h-full before:w-full before:origin-bottom before:scale-y-[0.35] before:bg-green-400 before:transition-transform before:duration-500 before:ease-in-out hover:before:scale-y-100">
                <span class="relative">{{ __('nav.zona_integritas') }}</span>
            </div>
            <div
                class="relative cursor-pointer px-2 text-2xl before:absolute before:bottom-0 before:left-0 before:h-full before:w-full before:origin-bottom before:scale-y-[0.35] before:bg-green-400 before:transition-transform before:duration-500 before:ease-in-out hover:before:scale-y-100">
                <span class="relative">{{ __('nav.Hubungi Kami') }}</span>
            </div> -->
            </nav>

            <div class="nav-3:block nav-2:py-10 hover:text-secondary-warn ml-3 hidden cursor-pointer py-5 text-white"
                @click="$dispatch('searching')">
                <svg class="h-[25px] w-[25px] transition-all" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </g>
                </svg>
            </div>
            <div x-cloak x-data="{ locale: '{{ Cookie::get('locale', 'id') }}', lang_id: '{{ route('change.lang', ['lang' => 'id']) }}', lang_en: '{{ route('change.lang', ['lang' => 'en']) }}', enter: false, clicked: false }" @click="goToPage(locale == 'id' ? lang_en : lang_id); clicked=true"
                class="nav-3:flex nav-2:py-10 animate-fade hidden cursor-pointer items-center justify-center py-5"
                @mouseenter="enter = true" @mouseleave="if (!clicked) enter = false">
                <template x-cloak x-if="locale == 'en'">
                    <div class="relative flex flex-row items-center gap-x-1 rounded-full bg-green-400">
                        <p class="absolute left-2 text-base">Id</p>
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
                                class="iconify iconify--emojione" preserveAspectRatio="xMidYMid meet" fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M31.8 62c16.6 0 30-13.4 30-30h-60c0 16.6 13.4 30 30 30" fill="#f9f9f9">
                                    </path>
                                    <path d="M31.8 2c-16.6 0-30 13.4-30 30h60c0-16.6-13.4-30-30-30" fill="#ed4c5c">
                                    </path>
                                </g>
                            </svg>
                        </div>
                        <p class="mr-1 text-base">En</p>
                    </div>
                </template>
                <template x-cloak x-if="locale == 'id'">
                    <div class="relative flex flex-row items-center gap-x-1 rounded-full bg-green-400">
                        <p class="ml-2 text-base">Id</p>
                        <div class="z-20 h-[25px] w-[25px] overflow-hidden rounded-full shadow-2xl transition-all"
                            :class="{
                                '-translate-x-[25px]': enter,
                                'translate-x-0': !enter
                            }">
                            <svg x-show="!enter" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                class="iconify iconify--emojione" preserveAspectRatio="xMidYMid meet" fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M31.8 62c16.6 0 30-13.4 30-30h-60c0 16.6 13.4 30 30 30" fill="#f9f9f9">
                                    </path>
                                    <path d="M31.8 2c-16.6 0-30 13.4-30 30h60c0-16.6-13.4-30-30-30" fill="#ed4c5c">
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
                        <p class="absolute right-1 text-base">En</p>
                    </div>
                </template>
            </div>
            <div class="nav-1:hidden text-accent-white bg-primary-dark hover:text-secondary-warn ml-3 block cursor-pointer overflow-hidden rounded-md p-2"
                @click="$dispatch('navigation', {menus})">
                <svg class="h-[25px] w-[25px] transition-all hover:scale-150" viewBox="-0.5 0 25 25" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M19 3.32001H16C14.8954 3.32001 14 4.21544 14 5.32001V8.32001C14 9.42458 14.8954 10.32 16 10.32H19C20.1046 10.32 21 9.42458 21 8.32001V5.32001C21 4.21544 20.1046 3.32001 19 3.32001Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path
                            d="M8 3.32001H5C3.89543 3.32001 3 4.21544 3 5.32001V8.32001C3 9.42458 3.89543 10.32 5 10.32H8C9.10457 10.32 10 9.42458 10 8.32001V5.32001C10 4.21544 9.10457 3.32001 8 3.32001Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path
                            d="M19 14.32H16C14.8954 14.32 14 15.2154 14 16.32V19.32C14 20.4246 14.8954 21.32 16 21.32H19C20.1046 21.32 21 20.4246 21 19.32V16.32C21 15.2154 20.1046 14.32 19 14.32Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path
                            d="M8 14.32H5C3.89543 14.32 3 15.2154 3 16.32V19.32C3 20.4246 3.89543 21.32 5 21.32H8C9.10457 21.32 10 20.4246 10 19.32V16.32C10 15.2154 9.10457 14.32 8 14.32Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </g>
                </svg>
            </div>
        </div>
    </div>
</header>
