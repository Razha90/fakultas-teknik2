@section('title', 'Pages | FT')
<div class="" x-data="initMenuIndex" x-init="console.log(menus)">
    <h1>Menu</h1>
    <div class="d-flex justify-content-start mr-3">
        <button @click="$dispatch('addonemenu')" class="btn btn-primary">
            + Tambah Menu Baru
        </button>
    </div>


    <table class="table-striped mt-10 table">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Title</th>
                <th scope="col">Path</th>
                <th scope="col">Posisi</th>
                <th scope="col">Aktif</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <template x-if="menus">
                <template x-for="(item, index) in menus" :key="index">

                    <tr>
                        <th scope="row" x-text="index+1"></th>
                        <td x-text="item.name"></td>
                        <td x-text="item.path"></td>
                        <td class="">
                            <div class="flex w-[150px] flex-row items-center justify-center gap-x-4">
                                <button class="btn btn-primary" x-show="item.position != '1'"
                                    @click="$wire.upMenu(item.id)">
                                    <i class="bi bi-chevron-up"></i>
                                </button>
                                <button class="btn btn-primary !cursor-default opacity-0" x-show="item.position == '1'">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <p x-text="item.position" class="!m-0 text-3xl font-bold"></p>
                                <button class="btn btn-primary -rotate-180" @click="$wire.downMenu(item.id)"
                                    x-show="index != menus.length - 1">
                                    <i class="bi bi-chevron-up"></i>
                                </button>
                                <button class="btn btn-primary !cursor-default opacity-0"
                                    x-show="index == menus.length - 1">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="w-[90px] flex flex-col items-center justify-center">
                                <p x-text="item.isActive == '1' ? 'Ya' : 'Tidak'"
                                    x-bind:class="item.isActive == '1' ? 'btn btn-success' : 'btn btn-danger'"
                                    class="text-center"></p>
                                <button x-bind:class="item.isActive == '1' ? 'btn btn-danger' : 'btn btn-primary'"
                                    x-text="item.isActive == '1' ? 'Draft' : 'Aktifkan'" @click="$wire.toggleMenuActive(item.id)"></button>
                            </div>
                        </td>
                        <td>
                            <div class="flex w-[100px] flex-col gap-y-3">
                                <button @click="$dispatch('editmenu', { menu: item })"
                                    class="btn btn-warning me-2">Edit</button>
                                <button @click="$dispatch('deletemenu', { menu: item })"
                                    class="btn btn-danger">Hapus</button>
                            </div>
                        </td>
                    </tr>
                </template>
            </template>
        </tbody>
        <template x-if="!menus.length">
            <tfoot>
                <tr>
                    <td colspan="6" class="text-center">
                        <span>Tidak ada menu yang tersedia.</span>
                    </td>
                </tr>
            </tfoot>
        </template>
    </table>

    <div x-on:addonemenu.window="open=true" x-show="open" x-cloak x-data="{
        open: false,
        name: '',
        linked: '',
        loading: false,
        async handleMenu() {
            try {
                this.loading = true;
                if (this.name.trim() === '' || this.linked.trim() === '') {
                    this.$dispatch('failed', [{
                        message: 'Judul dan tautan tidak boleh kosong.'
                    }]);
                    return;
                }
                await this.$wire.menusPageAdd(this.name, this.linked);
    
            } catch (error) {} finally {
                this.loading = false;
                this.open = false;
                this.name = '';
                this.linked = '';
            }
        }
    }"
        class="animate-fade fixed inset-0 z-10 flex items-center justify-center bg-black/30">
        <div class="relative w-[500px] overflow-y-auto bg-white p-3 shadow-lg" @click.away="open=false">
            <div class="flex flex-row justify-between">
                <h4>Tambah Menu Baru</h4>
                <div class="hover:text-secondary-warn cursor-pointer text-red-500 transition-all hover:rotate-90"
                    @click="open=false">
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
            <div class="mt-2">
                <div class="flex flex-col">
                    <label for="name" class="text-primary ml-3">Masukkan Judul</label>
                    <input id="name" type="text" class="w-full rounded-xl border border-gray-300 p-2"
                        placeholder="Judul" x-model="name">
                </div>
                <div class="mt-3 flex flex-col">
                    <label for="link" class="text-primary ml-3">Masukkan Tautan</label>
                    <input id="link" type="text" class="w-full rounded-xl border border-gray-300 p-2"
                        placeholder="Tautan" x-model="linked">
                </div>
                <div class="mt-4 text-center">
                    <button type="click" @click="handleMenu()"
                        class="mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                        <span x-show="!loading">Tambah</span>
                        <svg x-show="loading" aria-hidden="true"
                            class="h-8 w-8 animate-spin fill-blue-600 text-gray-200 dark:text-gray-600"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-on:deletemenu.window="(event) => {
        open = true;
        data = event.detail.menu;
        
    }"
        x-show="open" x-cloak x-data="{
            data: {},
            open: false,
        }"
        class="animate-fade fixed inset-0 z-10 flex items-center justify-center bg-black/30">
        <div class="relative w-[500px] overflow-y-auto bg-white p-3 shadow-lg" @click.away="open=false">
            <div class="flex flex-row justify-between">
                <div></div>
                <div class="hover:text-secondary-warn cursor-pointer text-red-500 transition-all hover:rotate-90"
                    @click="open=false">
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
                <h4 class="text-center text-gray-500">Apakah Anda yakin ingin menghapus menu <span
                        class="font-bold text-gray-600" x-text="data.name"></span>?</h4>
            </div>
            <div class="mt-3 flex flex-row items-center justify-center gap-x-3">
                <button type="button" class="btn btn-danger"
                    @click="$wire.deleteMenu(data.id); open=false;">Hapus</button>
                <button type="button" class="btn btn-secondary" @click="open=false">Batal</button>
            </div>
        </div>
    </div>

    <div x-on:editmenu.window="(event) => {
        open = true;
        data = event.detail.menu;
        initData();
    }"
        x-show="open" x-cloak x-data="{
            open: false,
            data: {},
            name: '',
            loading: false,
            linked: '',
            initData() {
                this.name = this.data.name;
                this.linked = this.data.path;
            },
            async updateData() {
                try {
                    this.loading = true;
                    if (this.name.trim() === '' || this.linked.trim() === '') {
                        this.$dispatch('failed', [{
                            message: 'Judul dan tautan tidak boleh kosong.'
                        }]);
                        return;
                    }
                    await this.$wire.updateMenu(this.data.id, this.name, this.linked);
        
                } catch (error) {} finally {
                    this.loading = false;
                    this.open = false;
                    this.name = '';
                    this.linked = '';
                }
            }
        }"
        class="animate-fade fixed inset-0 z-10 flex items-center justify-center bg-black/30">
        <div class="relative w-[500px] overflow-y-auto bg-white p-3 shadow-lg" @click.away="open=false">
            <div class="flex flex-row justify-between">
                <h4>Ubah Menu <span x-text="data.name" class="text-gray-500"></span></h4>
                <div class="hover:text-secondary-warn cursor-pointer text-red-500 transition-all hover:rotate-90"
                    @click="open=false">
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
            <div class="mt-2">
                <div class="flex flex-col">
                    <label for="name" class="text-primary ml-3">Masukkan Judul</label>
                    <input id="name" type="text" class="w-full rounded-xl border border-gray-300 p-2"
                        placeholder="Judul" x-model="name">
                </div>
                <div class="mt-3 flex flex-col">
                    <label for="link" class="text-primary ml-3">Masukkan Tautan</label>
                    <input id="link" type="text" class="w-full rounded-xl border border-gray-300 p-2"
                        placeholder="Tautan" x-model="linked">
                </div>
                <div class="mt-4 text-center">
                    <button type="click" @click="updateData"
                        class="mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                        <span x-show="!loading">Tambah</span>
                        <svg x-show="loading" aria-hidden="true"
                            class="h-8 w-8 animate-spin fill-blue-600 text-gray-200 dark:text-gray-600"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function initMenuIndex() {
        return {
            menus: @entangle('menus').live,

        }
    }
</script>
