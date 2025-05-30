@section('title', 'Jenis Konten | Fakultas Teknik')
<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <h1>Jenis Konten</h1>

    <table class="table table-striped">
        @livewire('admin.content-type.content-type-create')
        <thead>
          <tr>
            <th scope="col">No.</th>
            <th scope="col">Jenis Konten</th>
            <th scope="col">Action</th>

          </tr>
        </thead>
        <tbody>
            @foreach ($contentType as $cT)
            <tr>
                <th scope="row">{{$loop->iteration}}</th>
                <td>{{$cT->name}}</td>
                <td>
                    <button type="button" class="btn waves-effect waves-light btn-rounded btn btn-warning" wire:click="editContentType('{{ $cT->id }}')">
                        Edit
                    </button>
                    |
                    <button type="button" wire:click="confirmDelete('{{ $cT->id }}')" class="btn btn-danger">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>


      {{-- Modal Edit  --}}
      <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="updateContentType">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Jenis Konten</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Jenis Konten</label>
                            <input type="text" wire:model="name" wire:key="edit-name-{{ $editKey }}" class="form-control" placeholder="Jenis Konten">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- penutup modal edit --}}

    {{-- Pembuka Modal Hapus --}}
    <div wire:ignore.self class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah kamu yakin ingin Jenis Konten ini?
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="delete" class="btn btn-danger" data-dismiss="modal">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Penutup modal Hapys --}}
      <script>

        window.addEventListener('showEditModal', event => {
            var modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        });

        window.addEventListener('closeEditModal', event => {
            var modalEl = document.getElementById('editModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        });

        window.addEventListener('openDeleteModal', event => {
            var modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
        });

        window.addEventListener('closeModal', event => {
            var modalEl = document.getElementById('addContentType');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        });

        window.addEventListener('closeDeleteModal', event => {
            var modalEl = document.getElementById('deleteConfirmModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        });

        window.addEventListener('showSDeleteAlert', event => {
    Swal.fire({
        icon: 'success',
        title: 'Data Berhasil dihapus!',
    }).then((result) => {
        if (result.isConfirmed) {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());

            document.body.classList.remove('modal-open');
            document.body.style = '';
        }
    });
});

    window.addEventListener('showEditAlert', event => {
        Swal.fire({
            icon: 'success',
            title: 'Data Berhasil diUpdate!',
        });
    });

        window.addEventListener('dataDuplicate', event => {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Duplikat!',
                    text: 'Data yang kamu masukkan sudah ada, masukkan data yang lain!',
                    confirmButtonText: 'Oke'
                });
            });


         window.addEventListener('contentTypeCreated', event => {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Jenis Konten berhasil ditambahkan!',
        confirmButtonText: 'Oke'
    }).then((result) => {
        if (result.isConfirmed) {
            var modalEl = document.getElementById('exampleModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }

            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());

            // Kembalikan scroll ke body
            document.body.classList.remove('modal-open');
            document.body.style = '';
        }
    });
});

      </script>
</div>
