<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<header class="animate-fade-down fixed top-0 z-20 w-full overflow-hidden">
    <div class="bg-primary border-primary-dark absolute left-0 top-0 h-full w-full border-b-2 shadow-2xl transition-all">
    </div>
    <div class="mx-auto flex max-w-[var(--max-width)] flex-row px-10 pb-2 pt-4 transition-all">
        <div class="text-primary z-10 flex w-[330px] flex-row items-center justify-center gap-x-2 rounded-xl p-2">
            <img class="w-[4vw] min-w-[40px] max-w-[80px]" src="{{ asset('img/unimed.png') }}"
                alt="{{ config('app.name') }}">
            <p class="text-accent-white font-merriweather text-3xl font-bold">FAKULTAS<br />TEKNIK</p>
        </div>
        <div class="z-10 flex w-full flex-row items-center justify-end gap-x-10 text-2xl font-bold text-white">
            <div class="cursor-pointer overflow-hidden border-b-2 border-white">
                <svg class="animate-fade-up w-[35px] text-white hover:translate-y-2 hover:animate-bounce"
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

            <div @click="goToPage('{{ route('profile') }}')">{{ __('nav.profile') }}</div>
            <div>PPID</div>
            <div>{{ __('nav.mutu_internal') }}</div>
            <div
                class="relative cursor-pointer px-2 text-2xl before:absolute before:bottom-0 before:left-0 before:h-full before:w-full before:origin-bottom before:scale-y-[0.35] before:bg-green-400 before:transition-transform before:duration-500 before:ease-in-out hover:before:scale-y-100">
                <span class="relative">{{ __('nav.zona_integritas') }}</span>
            </div>
            <div
                class="relative cursor-pointer px-2 text-2xl before:absolute before:bottom-0 before:left-0 before:h-full before:w-full before:origin-bottom before:scale-y-[0.35] before:bg-green-400 before:transition-transform before:duration-500 before:ease-in-out hover:before:scale-y-100">
                <span class="relative">{{ __('nav.Hubungi Kami') }}</span>
            </div>
            <div x-data="{ locale: '{{ Cookie::get('locale', 'id') }}', lang_id: '{{ route('change.lang', ['lang' => 'id']) }}', lang_en: '{{ route('change.lang', ['lang' => 'en']) }}', enter: false }" @click="goToPage(locale == 'id' ? lang_en : lang_id)"
                class="flex cursor-pointer items-center justify-center" @mouseenter="enter = true"
                @mouseleave="enter=false">
                <template x-if="locale == 'en'">
                    <div class="relative flex flex-row items-center gap-x-1 rounded-full bg-green-400">
                        <p class="absolute left-2 text-base">Id</p>
                        <div class="h-[25px] w-[25px] overflow-hidden rounded-full shadow-2xl transition-all"
                            :class="{
                                'translate-x-[29px]': enter,
                                'translate-x-0': !enter
                            }">

                            <svg class="w-[35px]" x-show="!enter" xmlns="http://www.w3.org/2000/svg" id="flag-icons-us"
                                viewBox="0 0 640 480">
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
                        <p class="mr-2 text-base">En</p>
                    </div>
                </template>
                <template x-if="locale == 'id'">
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
                            <svg class="w-[35px]" x-show="enter" xmlns="http://www.w3.org/2000/svg" id="flag-icons-us"
                                viewBox="0 0 640 480">
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
                <!-- <p class="text-accent-white/40 text-xl">/</p>
                    <p
                        :class="{
                            'text-3xl text-accent-white ':locale=='id', '
                            text - xl text - accent - white / 40 ': locale != '
                            id '}">
                        Id</p> -->
            </div>
        </div>
    </div>
</header>
