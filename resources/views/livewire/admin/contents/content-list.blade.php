@section('title', 'Konten | Fakultas Teknik')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Daftar Konten</h4>
        <a href="{{ route('content.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambahkan Data
        </a>
    </div>

    {{-- FILTER BAR --}}
     <input type="text" wire:model.debounce.300ms="search" placeholder="Cari judul konten..." class="form-control mb-3">


    {{-- TABLE --}}
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Jenis</th>
                <th>Pengguna</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Gambar</th>
                <th>
                    <a href="#" wire:click.prevent="sortBy('published_at')" class="text-decoration-none text-dark">
                        Tanggal Publish
                        @if ($sortField === 'published_at')
                            @if ($sortDirection === 'asc')
                                <i class="bi bi-caret-up-fill"></i>
                            @else
                                <i class="bi bi-caret-down-fill"></i>
                            @endif
                        @endif
                    </a>
                </th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($contents as $index => $content)
                <tr wire:key="content-{{ $content->id }}">
                    <td>{{ $contents->firstItem() + $index }}</td>
                    <td>{{ $content->title }}</td>
                    <td>{{ $content->type?->name ?? '-' }}</td>
                    <td>{{ $content->user?->fullname ?? '-' }}</td>
                    <td>
                        @foreach ($content->categories as $category)
                            <span class="badge bg-info text-dark">{{ $category->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        @if ($content->status === 'published')
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-secondary">Draft</span>
                        @endif
                    </td>
                    <td>
                        @if ($content->image)
                            <img src="{{ asset('storage/' . $content->image) }}" width="60">
                        @else
                            <span class="text-muted">Tidak ada</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($content->published_at)->format('d-m-Y') }}</td>
                    <td>
                        <a href="{{ route('content.edit', $content->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <button type="button" wire:click="confirmDelete('{{ $content->id }}')" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">Tidak ada data ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

@if ($contents->lastPage() > 1)
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item {{ $contents->onFirstPage() ? 'disabled' : '' }}">
            @if(!$contents->onFirstPage())
                <a href="#" class="page-link" wire:click.prevent="gotoPage({{ $contents->currentPage() - 1 }})" tabindex="-1">Previous</a>
            @else
                <span class="page-link">Previous</span>
            @endif
        </li>

        @for ($page = 1; $page <= $contents->lastPage(); $page++)
            <li class="page-item {{ $page == $contents->currentPage() ? 'active' : '' }}">
                <a href="#" class="page-link" wire:click.prevent="gotoPage({{ $page }})">{{ $page }}</a>
            </li>
        @endfor

        <li class="page-item {{ $contents->hasMorePages() ? '' : 'disabled' }}">
            @if($contents->hasMorePages())
                <a href="#" class="page-link" wire:click.prevent="gotoPage({{ $contents->currentPage() + 1 }})">Next</a>
            @else
                <span class="page-link">Next</span>
            @endif
        </li>
    </ul>
</nav>
@endif

    {{-- MODAL DELETE --}}
    <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus konten ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button wire:click.prevent="deleteConfirmed" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('openDeleteModal', () => {
            var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });

        window.addEventListener('closeDeleteModal', () => {
            var modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            modal.hide();
        });

        window.addEventListener('contentDeleted', () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Konten berhasil dihapus!',
                showConfirmButton: false,
                timer: 2000
            });
        });
    </script>
</div>
