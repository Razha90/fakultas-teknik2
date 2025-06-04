<!DOCTYPE html>
<html lang="{{ Cookie::get('locale', 'id') }}">

<head>
    @include('partials.app')
    <style>
        /* Default style */
        body.nprogress-page #nprogress .bar {
            background: #FCF259;
            height: 4px;
        }

        /* Warna untuk reading progress */
        body.nprogress-reading #nprogress .bar {
            background: #0ea5e9;
            height: 4px;
        }
    </style>
</head>

<body class="max-w-full">
    <livewire:component.nav />

    {{ $slot }}

    <div x-cloak x-data="{ alert: false, message: '' }"
        x-on:success.window="(event) => {
        alert = true;
        message = event.detail[0].message;
        
        setTimeout(() => {
            alert = false;
        }, 4000);
    }"
        x-show="alert" x-transition id="toast-success"
        class="fixed bottom-3 left-3 z-30 mb-4 flex w-full max-w-xs items-center rounded-lg bg-white p-4 text-gray-500 shadow-sm"
        role="alert">
        <div class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-500">
            <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
            </svg>
            <span class="sr-only">Check icon</span>
        </div>
        <div class="ms-3 text-sm font-normal" x-text="message"></div>
        <button @click="alert = false" type="button"
            class="-mx-1.5 -my-1.5 ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-900 focus:ring-2 focus:ring-gray-300"
            data-dismiss-target="#toast-success" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>

    <div x-cloak x-data="{ alert: false, message: '' }"
        x-on:failed.window="(event) => {
        alert = true;
        message = event.detail[0].message;
        
        setTimeout(() => {
            alert = false;
        }, 4000);
    }"
        id="toast-danger" x-show="alert" x-transition
        class="fixed bottom-3 left-3 z-30 mb-4 flex w-full max-w-xs items-center rounded-lg bg-white p-4 text-gray-500 shadow-sm"
        role="alert">
        <div class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-500">
            <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z" />
            </svg>
            <span class="sr-only">Error icon</span>
        </div>
        <div class="ms-3 text-sm font-normal" x-text="message"></div>
        <button @click="alert = false" type="button"
            class="-mx-1.5 -my-1.5 ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-900 focus:ring-2 focus:ring-gray-300"
            data-dismiss-target="#toast-danger" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>

    <div x-cloak x-data="{
        shown: false,
        search: '',
        navigate() {
            goToPage('/search' + '?search=' + this.search);
        }
    
    }"
        x-on:searching.window="(event) => {
        shown = true;
        $nextTick(() => $refs.searchInput.focus());
    }"
        class="fixed inset-0 z-40 flex items-center justify-center bg-black/60" x-transition x-show="shown">
        <div class="w-md h-[100px] max-w-md" @click.away="shown=false">
            <div class="border-primary flex flex-row items-center border-b-2">
                <input x-ref="searchInput" type="text" x-model="search"
                    class="w-full px-4 py-2 text-3xl text-white focus:outline-none focus:ring-0"
                    placeholder="{{ __('nav.seach') }}" @keydown.enter="navigate">
                <div class="text-accent-white bg-primary rounded-full p-2 transition-all" @click="navigate">
                    <svg class="h-[20px] w-[20px]" viewBox="0 0 24 24" fill="none"
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
            </div>
        </div>
        <div class="hover:text-secondary-warn text-accent-white absolute right-7 top-7 cursor-pointer transition-all hover:rotate-90"
            @click="shown=false">
            <svg class="h-[35px] w-[35px]" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"
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

    <nav x-cloak x-data="{
        shown: false,
        haveOne: '',
        menus: [],
        resolvePath(itemPath, childPath) {
            try {
                new URL(childPath);
                return childPath;
            } catch (e) {
                return itemPath + childPath;
            }
        }
    }"
        x-on:navigation.window="(event) => {
        shown = true;
        menus = event.detail.menus;
        console.log(menus);
    }"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full opacity-0 bg-secondary-accent/0"
        x-transition:enter-end="translate-x-0 opacity-100 bg-secondary-accent/30"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0"
        class="bg-secondary-accent/30 fixed inset-0 z-50 flex justify-end" x-show="shown">
        <div class="bg-secondary-accent/80 relative h-full max-w-[300px] overflow-y-auto" @click.away="shown=false">
            <!-- <div>
                <div class="text-accent-white mt-16 pl-4 pr-5 text-xl">
                    <div class="" x-data="{ height: '' }">
                         <template x-if="menus && menus.length > 0">
                            <template x-for="(item, index) in menus" :key="">
                                <div class="group flex cursor-pointer flex-row items-center justify-between gap-x-1"
                                    @click="haveOne = haveOne === item.id ? '' : item.id; height = $refs.menu.scrollHeight;">
                                    <div x-bind:class="haveOne == item.id ? 'text-secondary-warn' : 'text-accent-white'"
                                        class="group-hover:text-secondary-warn transition-all" x-text="item.name">
                                        </div>
                                    <div class="flex flex-row">
                                        <div class="group-hover:bg-secondary-warn h-[2px] w-[10px] rounded-full transition-all"
                                            x-bind:class="haveOne == item.id ? 'rotate-0 bg-secondary-warn' :
                                                'rotate-45 bg-accent-white'">
                                        </div>
                                        <div class="bg-accent-white group-hover:bg-secondary-warn relative right-1 h-[2px] w-[10px] rounded-full transition-all"
                                            x-bind:class="haveOne == item.id ? 'rotate-0 bg-secondary-warn' :
                                                '-rotate-45 bg-accent-white'">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <div class="group flex cursor-pointer flex-row items-center justify-between gap-x-1"
                            @click="haveOne = haveOne === 'about' ? '' : 'about'; height = $refs.menu.scrollHeight;">
                            <div x-bind:class="haveOne == 'about' ? 'text-secondary-warn' : 'text-accent-white'"
                                class="group-hover:text-secondary-warn transition-all">
                                {{ __('nav.about') }}</div>
                            <div class="flex flex-row">
                                <div class="group-hover:bg-secondary-warn h-[2px] w-[10px] rounded-full transition-all"
                                    x-bind:class="haveOne == 'about' ? 'rotate-0 bg-secondary-warn' : 'rotate-45 bg-accent-white'">
                                </div>
                                <div class="bg-accent-white group-hover:bg-secondary-warn relative right-1 h-[2px] w-[10px] rounded-full transition-all"
                                    x-bind:class="haveOne == 'about' ? 'rotate-0 bg-secondary-warn' : '-rotate-45 bg-accent-white'">
                                </div>
                            </div>
                        </div>
                        <ul x-ref="menu" x-bind:style="haveOne == 'about' ? `height: ${height}px` : 'height: 0px'"
                            class="mt-2 space-y-3 overflow-hidden pl-3 transition-all duration-300 ease-in-out">
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    {{ __('nav.profile_short') }}</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    {{ __('nav.symbols.mottos.logos') }}</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    {{ __('nav.hymne.mars') }}</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    {{ __('nav.cooperation') }}</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    {{ __('nav.performance') }}</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    {{ __('nav.statistic') }}</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    {{ __('nav.history') }}</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    {{ __('nav.vision.misi') }}</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    {{ __('nav.organize') }}</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    {{ __('nav.akreditasi') }}</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    {{ __('nav.documents') }}</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    {{ __('nav.info.money') }}</p>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="hover:text-secondary-warn text-accent-white absolute right-5 top-5 cursor-pointer transition-all hover:rotate-90"
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
            <div>
                <div class="text-accent-white mt-3 pl-4 pr-5 text-xl">
                    <div class="" x-data="{ height: '' }">
                        <div class="group flex cursor-pointer flex-row items-center justify-between gap-x-1"
                            @click="height = $refs.menu.scrollHeight; haveOne = haveOne === 'jurusan' ? '' : 'jurusan'">
                            <div x-bind:class="haveOne == 'jurusan' ? 'text-secondary-warn' : 'text-accent-white'"
                                class="group-hover:text-secondary-warn transition-all">
                                {{ __('nav.jurusan_dan_program_studi') }}</div>
                            <div class="flex flex-row">
                                <div class="group-hover:bg-secondary-warn h-[2px] w-[10px] rounded-full transition-all"
                                    x-bind:class="haveOne == 'jurusan' ? 'rotate-0 bg-secondary-warn' : 'rotate-45 bg-accent-white'">
                                </div>
                                <div class="bg-accent-white group-hover:bg-secondary-warn relative right-1 h-[2px] w-[10px] rounded-full transition-all"
                                    x-bind:class="haveOne == 'jurusan' ? 'rotate-0 bg-secondary-warn' : '-rotate-45 bg-accent-white'">
                                </div>
                            </div>
                        </div>
                        <ul x-ref="menu" x-bind:style="haveOne == 'jurusan' ? `height: ${height}px` : 'height: 0px'"
                            class="mt-2 space-y-3 overflow-hidden pl-3 transition-all duration-300 ease-in-out">
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    Pendidikan
                                    Tata Busana</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    Pendidikan
                                    Teknik Informatika dan Komputer</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    Pendidikan
                                    Tata Boga</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    Pendidikan
                                    Teknik Bangunan</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    Manajemen
                                    Konstruksi</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    Arsitektur
                                </p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    Pendidikan
                                    Tata Rias</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">Gizi</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    Pendidikan
                                    Teknik Mesin</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    Pendidikan
                                    Teknik Elektro</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">Teknik
                                    Elektro</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    Pendidikan
                                    Teknik Otomotif</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">Profesi
                                    Insinyur</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">D3
                                    Teknik
                                    Sipil</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">D3
                                    Teknik
                                    Mesin</p>
                            </li>
                            <li class="group flex cursor-pointer flex-row items-center gap-x-5">
                                <div
                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                </div>
                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors">
                                    S2-Pendidikan Guru Vokasi</p>
                            </li>

                        </ul>
                    </div>
                </div>
            </div> -->
            <div class="hover:text-secondary-warn text-accent-white absolute right-5 top-5 cursor-pointer transition-all hover:rotate-90"
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
            <div class="h-[50px]"></div>
            <template x-if="menus && menus.length > 0">
                <template x-for="(item, index) in menus" :key="item.id">
                    <div>
                        <div class="text-accent-white mt-3 pl-4 pr-5 text-xl">
                            <div class="" x-data="{ height: '' }">
                                <template x-if="item.pages && item.pages.length > 0">
                                    <div class="group flex cursor-pointer flex-row items-center justify-between gap-x-1"
                                        @click="height = $refs.menu.scrollHeight; haveOne = haveOne === item.id ? '' : item.id">
                                        <div x-bind:class="haveOne == item.id ? 'text-secondary-warn' : 'text-accent-white'"
                                            class="group-hover:text-secondary-warn transition-all" x-text="item.name">
                                        </div>
                                        <div class="flex flex-row">
                                            <div class="group-hover:bg-secondary-warn h-[2px] w-[10px] rounded-full transition-all"
                                                x-bind:class="haveOne == item.id ? 'rotate-0 bg-secondary-warn' :
                                                    'rotate-45 bg-accent-white'">
                                            </div>
                                            <div class="bg-accent-white group-hover:bg-secondary-warn relative right-1 h-[2px] w-[10px] rounded-full transition-all"
                                                x-bind:class="haveOne == item.id ? 'rotate-0 bg-secondary-warn' :
                                                    '-rotate-45 bg-accent-white'">
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="item.pages && item.pages.length > 0">
                                    <ul x-ref="menu"
                                        x-bind:style="haveOne == item.id ? `height: ${height}px` : 'height: 0px'"
                                        class="mt-2 space-y-3 overflow-hidden pl-3 transition-all duration-300 ease-in-out">
                                        <template x-for="(child, col) in item.pages" :key="child.id">
                                            <li @click="goToPage(resolvePath(item.path, child.path))"
                                                class="group flex cursor-pointer flex-row items-center gap-x-5">
                                                <div
                                                    class="bg-accent-white group-hover:bg-secondary-warn h-[4px] w-[4px] rounded-full transition-all">
                                                </div>
                                                <p class="text-accent-white group-hover:text-secondary-warn transition-colors"
                                                    x-text="child.name">
                                                </p>
                                            </li>
                                        </template>
                                    </ul>
                                </template>

                                <template x-if="!item.pages || item.pages.length == 0">
                                    <div class="" @click="goToPage(item.path)" x-text="item.name">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            <div class="nav-3:hidden animate-fade flex w-full items-center justify-center gap-x-5">
                <div class="nav-2:py-10 hover:text-secondary-warn ml-3 block cursor-pointer py-5 text-white"
                    @click="$dispatch('searching'); shown=false">
                    <svg class="h-[25px] w-[25px] transition-all" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                            </path>
                        </g>
                    </svg>
                </div>
                <div x-cloak x-data="{ locale: '{{ Cookie::get('locale', 'id') }}', lang_id: '{{ route('change.lang', ['lang' => 'id']) }}', lang_en: '{{ route('change.lang', ['lang' => 'en']) }}', enter: false, clicked: false }"
                    @click="goToPage(locale == 'id' ? lang_en : lang_id); clicked=true"
                    class="nav-2:py-10 animate-fade flex cursor-pointer items-center justify-center py-5"
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
                                    class="iconify iconify--emojione" preserveAspectRatio="xMidYMid meet"
                                    fill="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M31.8 62c16.6 0 30-13.4 30-30h-60c0 16.6 13.4 30 30 30"
                                            fill="#f9f9f9">
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
                                    class="iconify iconify--emojione" preserveAspectRatio="xMidYMid meet"
                                    fill="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M31.8 62c16.6 0 30-13.4 30-30h-60c0 16.6 13.4 30 30 30"
                                            fill="#f9f9f9">
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
            </div>
        </div>
    </nav>

    <livewire:component.footer />

    <div x-cloak x-data="{ showTop: false }"
        @scroll.window="
        showTop = (
            document.body.scrollHeight > window.innerHeight &&
            (window.scrollY / (document.body.scrollHeight - window.innerHeight)) > 0.5
        )
    ">
        <button x-show="showTop" x-transition @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="bg-secondary-warn/70 hover:bg-secondary-warn fixed bottom-5 right-5 z-40 flex h-14 w-14 cursor-pointer items-center justify-center rounded-full border-0 p-4 text-lg font-semibold text-white shadow-md transition-colors duration-300 md:bottom-10 md:right-10">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                <path d="M12 4l8 8h-6v8h-4v-8H4l8-8z" />
            </svg>
            <span class="sr-only">Go to top</span>
        </button>
    </div>

</body>
<script>
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }

    const currentPath = location.pathname;
    const scrollKey = "savedScroll";

    window.addEventListener("beforeunload", () => {
        localStorage.setItem(scrollKey, JSON.stringify({
            path: currentPath,
            scrollY: window.scrollY
        }));
    });

    document.addEventListener("DOMContentLoaded", () => {
        const saved = localStorage.getItem(scrollKey);

        if (saved) {
            try {
                const parsed = JSON.parse(saved);
                if (parsed.path === currentPath) {
                    window.scrollTo({
                        top: parseInt(parsed.scrollY),
                        behavior: "smooth"
                    });
                } else {
                    localStorage.removeItem(scrollKey);
                }
            } catch (e) {
                localStorage.removeItem(scrollKey);
            }
        }
    });
</script>


</html>
