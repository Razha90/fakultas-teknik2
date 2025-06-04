<div x-data="dataNewsIndex" x-init="console.log(data)">
    <h1>{{ __('news.news') }}</h1>
    <div class="d-flex justify-content-start mr-3">
        <button wire:click="addNews" class="btn btn-primary">
            + {{ __('news.add_news') }}
        </button>
    </div>

    <table class="table-striped mt-10 table">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">{{ __('news.title') }}</th>
                <th scope="col">{{ __('news.views') }}</th>
                <th scope="col">{{ __('news.status') }}</th>
                <th scope="col">{{ __('news.action') }}</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(item, index) in data" :key="index">
                <tr>
                    <th scope="row" x-text="index + 1"></th>
                    <td>
                        <template
                            x-if="item.translations && Array.isArray(item.translations) && item.translations.length > 0">
                            <template x-for="(child, col) in item.translations" :key="col">
                                <span x-text="child.title" class="block"></span>
                            </template>
                        </template>
                        <template x-if="!Array.isArray(item?.translations) || item.translations.length === 0">
                            <span class="italic text-gray-600">[ {{ __('news.no.translate') }} ]</span>
                        </template>
                    </td>
                    <td x-text="item.views"></td>
                    <td>
                        <span x-text="item.status" class="rounded-md p-2 text-white"
                            x-bind:class="item.status == 'draft' ? '!bg-red-500' : '!bg-green-400'"></span>
                    </td>
                    <td>
                        <a :href="getUrl(item.id)" target="_blank" class="btn btn-warning">Ubah</a>
                        <button class="btn btn-danger" @click="$dispatch('deletemenu', { menu: item })">Hapus</button>
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
                <h4 class="text-center text-gray-500">Apakah Anda yakin ingin menghapus Berita <span
                        class="font-bold text-gray-600" x-text="data.translations[0].title ? data.translations[0].title : '{{ __('news.none.translate') }}'"></span>?</h4>
            </div>
            <div class="mt-3 flex flex-row items-center justify-center gap-x-3">
                <button type="button" class="btn btn-danger"
                    @click="$wire.deleteNews(data.id); open=false;">Hapus</button>
                <button type="button" class="btn btn-secondary" @click="open=false">Batal</button>
            </div>
        </div>
    </div>
</div>
<script>
    function dataNewsIndex() {
        return {
            data: @entangle('data').live,
            getUrl(id) {
                let url = '{{ route('news-edit', ['id' => '__ID__']) }}';
                return url.replace('__ID__', id);
            }
        }
    }
</script>
