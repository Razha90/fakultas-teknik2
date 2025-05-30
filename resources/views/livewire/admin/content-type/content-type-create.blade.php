<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContentType">
        Add Data
      </button>
      <br>
      <br>

      <div wire:ignore.self class="modal fade" id="addContentType" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form wire:submit.prevent="save" >
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Add Jenis Konten</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                        <label  for="recipient-name" class="control-label">Name</label>
                        <input type="text"  wire:model="name" placeholder="Jenis Content" class="form-control" id="recipient-name1">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
          </div>
        </div>
</div>
