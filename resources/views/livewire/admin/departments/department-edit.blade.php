<div>
    <!-- Modal Edit -->
    <div wire:ignore.self class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="update">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" wire:model="name" class="form-control" placeholder="Nama Department" />
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script untuk buka dan tutup modal -->
    <script>
        window.addEventListener('openEditModal', event => {
            var modal = new bootstrap.Modal(document.getElementById('editDepartmentModal'));
            modal.show();
        });

        window.addEventListener('closeEditModal', event => {
            var modalEl = document.getElementById('editDepartmentModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if(modal) {
                modal.hide();
            }
        });

        window.addEventListener('showToast', event => {
            const toastEl = document.getElementById('liveToast');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = event.detail.message;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        });
    </script>
</div>
