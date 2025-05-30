@section('title', 'Departments | FT')
<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <h1>Departments</h1>

    <table class="table table-striped">
        @livewire('admin.departments.department-create')
        <thead>
          <tr>
            <th scope="col">No.</th>
            <th scope="col">Nama Department</th>
            <th scope="col">Action</th>

          </tr>
        </thead>
        <tbody>
            @foreach ($departments as $department)
            <tr>
                <th scope="row">{{$loop->iteration}}</th>
                <td>{{$department->name}}</td>
                <td>
                    <button type="button" class="btn waves-effect waves-light btn-rounded btn btn-warning" wire:click="editDepartment('{{ $department->id }}')">
                        Edit
                    </button>
                    |
                    <button type="button" wire:click="confirmDelete('{{ $department->id }}')" class="btn btn-danger">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>

      @if ($departments->hasPages())
      <nav aria-label="Page navigation">
          <ul class="pagination">
              {{-- Previous --}}
              @if ($departments->onFirstPage())
                  <li class="page-item disabled"><span class="page-link">Previous</span></li>
              @else
                  <li class="page-item">
                      <a class="page-link" wire:click="previousPage" wire:loading.attr="disabled" style="cursor: pointer;">Previous</a>
                  </li>
              @endif

              {{-- Page Numbers --}}
              @foreach ($departments->links()->elements[0] as $page => $url)
                  @if ($page == $departments->currentPage())
                      <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                  @else
                      <li class="page-item">
                          <a class="page-link" wire:click="gotoPage({{ $page }})" style="cursor: pointer;">{{ $page }}</a>
                      </li>
                  @endif
              @endforeach

              {{-- Next --}}
              @if ($departments->hasMorePages())
                  <li class="page-item">
                      <a class="page-link" wire:click="nextPage" wire:loading.attr="disabled" style="cursor: pointer;">Next</a>
                  </li>
              @else
                  <li class="page-item disabled"><span class="page-link">Next</span></li>
              @endif
          </ul>
      </nav>
  @endif



      {{-- Modal Edit  --}}
      <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="updateDepartment">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Department</label>
                            <input type="text" wire:model="name" wire:key="edit-name-{{ $editKey }}" class="form-control" placeholder="Nama Kategori">

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
                    Apakah kamu yakin ingin menghapus department ini?
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
    var modalEl = document.getElementById('CreateModal'); // Ganti dari 'exampleModal'
    var modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) {
        modal.hide();
    }

    // Hapus backdrop abu-abu
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());

    document.body.classList.remove('modal-open');
    document.body.style = '';
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


         window.addEventListener('dataCreated', event => {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Department berhasil ditambahkan!',
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
