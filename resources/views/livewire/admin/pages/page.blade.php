@section('title', 'Pages | FT')
<div x-data="dataPage">
    <h1>Halaman</h1>
    <div class="d-flex justify-content-start mr-3">
        <button wire:click="addPage" class="btn btn-primary">
            + Tambah Halaman
        </button>
    </div>


    <table class="table-striped mt-10 table">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Judul</th>
                <th scope="col">Path</th>
                <th scope="col">Rilis</th>
                <th scope="col">Lihat</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(page, index) in data" :key="index">
                <tr>
                    <th scope="row" x-text="index + 1"></th>
                    <td x-text="page.name"></td>
                    <td x-text="page.menu ? resolvePath(page.menu.path, page.path) : page.path"></td>
                    <td x-text="page.isReleased == '1' ? 'Ya' : 'Tidak'"></td>
                    <td>
                        <a :href="getUrl(page.id)" target="_blank" class="btn btn-warning">Ubah</a>
                        <button class="btn btn-danger" @click="$dispatch('deletemenu', { menu: page })">Hapus</button>
                    </td>
                </tr>
            </template>
        </tbody>

    </table>

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
                    @click="$wire.deletePage(data.id); open=false;">Hapus</button>
                <button type="button" class="btn btn-secondary" @click="open=false">Batal</button>
            </div>
        </div>
    </div>
</div>
<script>
    function dataPage() {
        return {
            data: @entangle('data').live,
            getUrl(id) {
                let url = '{{ route('page-edit', ['id' => '__ID__']) }}';
                return url.replace('__ID__', id);
            },
            resolvePath(itemPath, childPath) {
                try {
                    new URL(childPath);
                    return childPath;
                } catch (e) {
                    return itemPath + childPath;
                }
            }
        }
    }
</script>
