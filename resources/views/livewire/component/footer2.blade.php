<?php

use Livewire\Volt\Component;

new class extends Component {}; ?>

<footer x-cloak class="bg-primary relative z-10 w-full">
        <div class="mx-auto flex w-full max-w-7xl flex-row items-start justify-between pb-10 pt-10">
            <div class="flex max-w-sm flex-col items-start">
                <div class="text-primary flex w-[330px] flex-row items-center justify-start gap-x-2 rounded-xl p-2">
                    <img class="w-[4vw] min-w-[40px] max-w-[80px]" src="{{ asset('img/unimed.png') }}"
                        alt="{{ config('app.name') }}">
                    <p class="text-accent-white font-merriweather text-3xl font-bold">FAKULTAS<br />TEKNIK</p>
                </div>
                <p class="text-accent-white">Jl. William Iskandar Ps. V, Kenangan Baru, Kec. Percut Sei Tuan,
                    Kabupaten
                    Deli Serdang, Sumatera Utara
                    20221</p>
            </div>
            <div x-data="{ shown: false }" x-intersect="shown = true">
                <h2 x-show="shown" class="animate-fade text-accent-white text-xl font-bold">
                    {{ __('nav.jurusan_dan_program_studi') }}</h2>
                <ul x-show="shown">
                    <li
                        class="animate-fade text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="https://tatabusana.unimed.ac.id/" target="_BLANK">Pendidikan Tata Busana</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-200 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="https://ptik.unimed.ac.id/" target="_BLANK">Pendidikan Teknik Informatika dan
                            Komputer</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-200 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="https://www.unimed.ac.id/2022/07/04/program-studi-pendidikan-tata-boga/#1656914193673-467fa727-87cc"
                            target="_BLANK">Pendidikan Tata Boga</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-400 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">Pendidikan Teknik Bangunan</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-600 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">Manajemen Konstruksi</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-800 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">Arsitektur</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-1000 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">Pendidikan Tata Rias</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-1200 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">Gizi</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-1400 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">Pendidikan Teknik Mesin</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-1600 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">Pendidikan Teknik Elektro</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-1800 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">Teknik Elektro</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-2000 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">Pendidikan Teknik Otomotif</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-2200 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">Profesi Insinyur</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-2400 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">D3 Teknik Sipil</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-2600 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">D3 Teknik Mesin</a>
                    </li>
                    <li
                        class="animate-fade animate-delay-2800 text-accent-white/80 hover:text-accent-white/40 cursor-pointer text-base transition-all">
                        <a href="#" target="_BLANK">S2-Pendidikan Guru Vokasi</a>
                    </li>
                </ul>
            </div>
            <div>
                <div x-data="{ shown: false }" x-intersect="shown = true">
                    <h2 x-cloak x-show="shown" class="animate-fade text-accent-white text-xl font-bold">
                        {{ __('home.social_media') }}</h2>
                    <div x-cloak x-show="shown" class="flex flex-row items-center gap-x-4">
                        <div class="animate-fade-left"
                            @click="goToPage('https://www.facebook.com/FakultasTeknikUnimed')"><svg
                                class="text-accent-white w-[45px] cursor-pointer transition-opacity hover:opacity-50"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M20 12.05C19.9813 10.5255 19.5273 9.03809 18.6915 7.76295C17.8557 6.48781 16.673 5.47804 15.2826 4.85257C13.8921 4.2271 12.3519 4.01198 10.8433 4.23253C9.33473 4.45309 7.92057 5.10013 6.7674 6.09748C5.61422 7.09482 4.77005 8.40092 4.3343 9.86195C3.89856 11.323 3.88938 12.8781 4.30786 14.3442C4.72634 15.8103 5.55504 17.1262 6.69637 18.1371C7.83769 19.148 9.24412 19.8117 10.75 20.05V14.38H8.75001V12.05H10.75V10.28C10.7037 9.86846 10.7483 9.45175 10.8807 9.05931C11.0131 8.66687 11.23 8.30827 11.5161 8.00882C11.8022 7.70936 12.1505 7.47635 12.5365 7.32624C12.9225 7.17612 13.3368 7.11255 13.75 7.14003C14.3498 7.14824 14.9482 7.20173 15.54 7.30003V9.30003H14.54C14.3676 9.27828 14.1924 9.29556 14.0276 9.35059C13.8627 9.40562 13.7123 9.49699 13.5875 9.61795C13.4627 9.73891 13.3667 9.88637 13.3066 10.0494C13.2464 10.2125 13.2237 10.387 13.24 10.56V12.07H15.46L15.1 14.4H13.25V20C15.1399 19.7011 16.8601 18.7347 18.0985 17.2761C19.3369 15.8175 20.0115 13.9634 20 12.05Z"
                                        fill="currentColor"></path>
                                </g>
                            </svg>
                        </div>

                        <div class="animate-fade-left animate-delay-500">
                            <svg class="text-accent-white w-[35px] cursor-pointer transition-opacity hover:opacity-50"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M8.288 21C15.832 21 19.96 14.4544 19.96 8.78772C19.96 8.60357 19.96 8.41943 19.952 8.23528C20.752 7.62425 21.448 6.87092 22 6.01715C21.248 6.36033 20.456 6.5947 19.64 6.69514C20.496 6.15945 21.136 5.31404 21.44 4.31798C20.632 4.8202 19.752 5.17175 18.832 5.3559C17.28 3.62324 14.68 3.53954 13.024 5.17175C11.96 6.21804 11.504 7.78328 11.84 9.2732C8.552 9.09742 5.472 7.46521 3.392 4.78671C2.304 6.74537 2.856 9.25646 4.664 10.512C4.008 10.4953 3.376 10.3111 2.8 9.9763C2.8 9.99305 2.8 10.0098 2.8 10.0265C2.8 12.0689 4.176 13.8266 6.096 14.2368C5.488 14.4126 4.856 14.4377 4.24 14.3121C4.776 16.0615 6.32 17.2585 8.072 17.292C6.616 18.4889 4.824 19.1334 2.976 19.1334C2.648 19.1334 2.32 19.1083 2 19.0748C3.88 20.3387 6.056 21 8.288 21Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </g>
                            </svg>
                        </div>

                        <div class="animate-fade-left animate-delay-1000"
                            @click="goToPage('https://www.instagram.com/ftunimed/')">
                            <svg class="text-accent-white w-[35px] cursor-pointer transition-opacity hover:opacity-50"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18ZM12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16Z"
                                        fill="currentColor"></path>
                                    <path
                                        d="M18 5C17.4477 5 17 5.44772 17 6C17 6.55228 17.4477 7 18 7C18.5523 7 19 6.55228 19 6C19 5.44772 18.5523 5 18 5Z"
                                        fill="currentColor"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M1.65396 4.27606C1 5.55953 1 7.23969 1 10.6V13.4C1 16.7603 1 18.4405 1.65396 19.7239C2.2292 20.8529 3.14708 21.7708 4.27606 22.346C5.55953 23 7.23969 23 10.6 23H13.4C16.7603 23 18.4405 23 19.7239 22.346C20.8529 21.7708 21.7708 20.8529 22.346 19.7239C23 18.4405 23 16.7603 23 13.4V10.6C23 7.23969 23 5.55953 22.346 4.27606C21.7708 3.14708 20.8529 2.2292 19.7239 1.65396C18.4405 1 16.7603 1 13.4 1H10.6C7.23969 1 5.55953 1 4.27606 1.65396C3.14708 2.2292 2.2292 3.14708 1.65396 4.27606ZM13.4 3H10.6C8.88684 3 7.72225 3.00156 6.82208 3.0751C5.94524 3.14674 5.49684 3.27659 5.18404 3.43597C4.43139 3.81947 3.81947 4.43139 3.43597 5.18404C3.27659 5.49684 3.14674 5.94524 3.0751 6.82208C3.00156 7.72225 3 8.88684 3 10.6V13.4C3 15.1132 3.00156 16.2777 3.0751 17.1779C3.14674 18.0548 3.27659 18.5032 3.43597 18.816C3.81947 19.5686 4.43139 20.1805 5.18404 20.564C5.49684 20.7234 5.94524 20.8533 6.82208 20.9249C7.72225 20.9984 8.88684 21 10.6 21H13.4C15.1132 21 16.2777 20.9984 17.1779 20.9249C18.0548 20.8533 18.5032 20.7234 18.816 20.564C19.5686 20.1805 20.1805 19.5686 20.564 18.816C20.7234 18.5032 20.8533 18.0548 20.9249 17.1779C20.9984 16.2777 21 15.1132 21 13.4V10.6C21 8.88684 20.9984 7.72225 20.9249 6.82208C20.8533 5.94524 20.7234 5.49684 20.564 5.18404C20.1805 4.43139 19.5686 3.81947 18.816 3.43597C18.5032 3.27659 18.0548 3.14674 17.1779 3.0751C16.2777 3.00156 15.1132 3 13.4 3Z"
                                        fill="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
                <div x-data="{ shown: false }" x-intersect="shown = true">
                    <iframe x-cloak x-show="shown" class="animate-fade"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d39569.95359019277!2d98.6721998614779!3d3.6144259670147845!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x303131714c40fccd%3A0x17660a6371985d8c!2sState%20University%20of%20Medan!5e1!3m2!1sid!2sid!4v1745563840872!5m2!1sid!2sid"
                        width="400" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </footer>
