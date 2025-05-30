<?php

use Livewire\Volt\Component;

new class extends Component {}; ?>

<footer class="bg-primary" x-data="{ show: false }" x-intersect="show=true" class="animate-fade animate-delay-500" x-cloak>
    <div class="bg-primary-light mx-auto flex max-w-[--max-width] flex-row justify-center gap-x-5 py-1">
        <div class="text-accent-white cursor-pointer transition-all hover:scale-110 hover:text-yellow-300"
            @click="goToPage('https://www.instagram.com/ftunimed_official/')"
            x-bind:class="show ? 'animate-fade animate-delay-200' : 'opacity-0'">
            <svg fill="currentColor" class="w-[45px]" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path
                        d="M20.445 5h-8.891A6.559 6.559 0 0 0 5 11.554v8.891A6.559 6.559 0 0 0 11.554 27h8.891a6.56 6.56 0 0 0 6.554-6.555v-8.891A6.557 6.557 0 0 0 20.445 5zm4.342 15.445a4.343 4.343 0 0 1-4.342 4.342h-8.891a4.341 4.341 0 0 1-4.341-4.342v-8.891a4.34 4.34 0 0 1 4.341-4.341h8.891a4.342 4.342 0 0 1 4.341 4.341l.001 8.891z">
                    </path>
                    <path
                        d="M16 10.312c-3.138 0-5.688 2.551-5.688 5.688s2.551 5.688 5.688 5.688 5.688-2.551 5.688-5.688-2.55-5.688-5.688-5.688zm0 9.163a3.475 3.475 0 1 1-.001-6.95 3.475 3.475 0 0 1 .001 6.95zM21.7 8.991a1.363 1.363 0 1 1-1.364 1.364c0-.752.51-1.364 1.364-1.364z">
                    </path>
                </g>
            </svg>
        </div>
        <div class="text-accent-white flex cursor-pointer items-center transition-all hover:scale-110 hover:text-yellow-300"
            @click="goToPage('https://x.com/fteknikunimed')"
            x-bind:class="show ? 'animate-fade animate-delay-400' : 'opacity-0'">
            <svg class="w-[30px]" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
            </svg>
        </div>
        <div class="text-accent-white flex cursor-pointer items-center transition-all hover:scale-110 hover:text-yellow-300"
            @click="goToPage('https://www.youtube.com/@officialfakultasteknikunim9179')"
            x-bind:class="show ? 'animate-fade animate-delay-600' : 'opacity-0'">
            <svg class="w-[35px]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M9.49614 7.13176C9.18664 6.9549 8.80639 6.95617 8.49807 7.13509C8.18976 7.31401 8 7.64353 8 8V16C8 16.3565 8.18976 16.686 8.49807 16.8649C8.80639 17.0438 9.18664 17.0451 9.49614 16.8682L16.4961 12.8682C16.8077 12.6902 17 12.3589 17 12C17 11.6411 16.8077 11.3098 16.4961 11.1318L9.49614 7.13176ZM13.9844 12L10 14.2768V9.72318L13.9844 12Z"
                        fill="currentColor"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0 12C0 8.25027 0 6.3754 0.954915 5.06107C1.26331 4.6366 1.6366 4.26331 2.06107 3.95491C3.3754 3 5.25027 3 9 3H15C18.7497 3 20.6246 3 21.9389 3.95491C22.3634 4.26331 22.7367 4.6366 23.0451 5.06107C24 6.3754 24 8.25027 24 12C24 15.7497 24 17.6246 23.0451 18.9389C22.7367 19.3634 22.3634 19.7367 21.9389 20.0451C20.6246 21 18.7497 21 15 21H9C5.25027 21 3.3754 21 2.06107 20.0451C1.6366 19.7367 1.26331 19.3634 0.954915 18.9389C0 17.6246 0 15.7497 0 12ZM9 5H15C16.9194 5 18.1983 5.00275 19.1673 5.10773C20.0989 5.20866 20.504 5.38448 20.7634 5.57295C21.018 5.75799 21.242 5.98196 21.4271 6.23664C21.6155 6.49605 21.7913 6.90113 21.8923 7.83269C21.9973 8.80167 22 10.0806 22 12C22 13.9194 21.9973 15.1983 21.8923 16.1673C21.7913 17.0989 21.6155 17.504 21.4271 17.7634C21.242 18.018 21.018 18.242 20.7634 18.4271C20.504 18.6155 20.0989 18.7913 19.1673 18.8923C18.1983 18.9973 16.9194 19 15 19H9C7.08058 19 5.80167 18.9973 4.83269 18.8923C3.90113 18.7913 3.49605 18.6155 3.23664 18.4271C2.98196 18.242 2.75799 18.018 2.57295 17.7634C2.38448 17.504 2.20866 17.0989 2.10773 16.1673C2.00275 15.1983 2 13.9194 2 12C2 10.0806 2.00275 8.80167 2.10773 7.83269C2.20866 6.90113 2.38448 6.49605 2.57295 6.23664C2.75799 5.98196 2.98196 5.75799 3.23664 5.57295C3.49605 5.38448 3.90113 5.20866 4.83269 5.10773C5.80167 5.00275 7.08058 5 9 5Z"
                        fill="currentColor"></path>
                </g>
            </svg>
        </div>
    </div>
    <div class="flex max-w-[--max-width] flex-wrap justify-around gap-x-5 gap-y-3 px-10 py-8 text-base">
        <div class="flex flex-row flex-wrap justify-center gap-x-7 text-white">
            <p class="hover:text-secondary-warn cursor-pointer transition-colors"
                x-bind:class="show ? 'animate-fade animate-delay-200' : 'opacity-0'">{{ __('home.term') }}</p>
            <p class="hover:text-secondary-warn cursor-pointer transition-colors"
                x-bind:class="show ? 'animate-fade animate-delay-400' : 'opacity-0'">{{ __('home.faq') }}</p>
            <p class="hover:text-secondary-warn cursor-pointer transition-colors"
                x-bind:class="show ? 'animate-fade animate-delay-600' : 'opacity-0'">{{ __('home.copyright') }}</p>
            <p class="hover:text-secondary-warn cursor-pointer transition-colors"
                x-bind:class="show ? 'animate-fade animate-delay-800' : 'opacity-0'">{{ __('home.contact') }}</p>
        </div>
        <div>
            <p class="text-accent-white text-center"
                x-bind:class="show ? 'animate-fade animate-delay-1000' : 'opacity-0'">Copyright © 2025 by Fakultas
                Teknik UNIMED</p>
        </div>
    </div>
</footer>
