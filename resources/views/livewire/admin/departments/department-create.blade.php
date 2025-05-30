<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}

    <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#CreateModal">
    Tambah Data
  </button>
  <br>

  <!-- Modal -->
  <div  wire:ignore.self class="modal fade" id="CreateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Data Department</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form wire:submit.prevent="save">
            <div class="mb-3">
                <label for="recipient-name1" class="form-label">Name</label>
                <input type="text" wire:model="name" placeholder="Nama Department" class="form-control" id="recipient-name1">
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- Footer Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

    <!-- Tombol untuk membuka modal --
</div>
