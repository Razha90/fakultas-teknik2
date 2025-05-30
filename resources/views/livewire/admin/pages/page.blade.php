@section('title', 'Pages | FT')
<div>
    <h1>Halaman</h1>
    <div class="d-flex justify-content-start mr-3">
        <button wire:click="addPage" class="btn btn-primary">
            + Tambah Halaman
        </button>
    </div>


    <table class="table-striped table mt-10">
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
            @foreach ($pages as $index => $page)
                <tr>
                    <th scope="row">{{ $index + 1 }}</th>
                    <td>{{ $page->title }}</td>
                    <td>{{ $page->path }}</td>
                    <td>{{ $page->is_published ? 'Ya' : 'Tidak' }}</td>
                    <td>
                        <a href="{{ route('page-edit', $page->id) }}" class="btn btn-secondary">Lihat</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
