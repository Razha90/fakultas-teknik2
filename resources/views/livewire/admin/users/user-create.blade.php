<div>
    <div class="card">
        <div class="card-header bg-success">
            <h4 class="text-white">Add User</h4>
        </div>

        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Username</label>
                        <input type="text" class="form-control" wire:model.defer="username">
                        @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Fullname</label>
                        <input type="text" class="form-control" wire:model.defer="fullname">
                        @error('fullname') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" wire:model.defer="email">
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Role</label>
                        <select class="form-control" wire:model.defer="role">
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staf</option>
                        </select>
                        @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Department</label>
                        <select class="form-control" wire:model.defer="id_department">
                            <option value="">-- Pilih Department --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('id_department') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Position</label>
                        <input type="text" class="form-control" wire:model.defer="position">
                        @error('position') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" wire:model.defer="phone_number">
                        @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Image</label>
                        <input type="file" class="form-control" wire:model="image">
                        @error('image') <small class="text-danger">{{ $message }}</small> @enderror

                        @if ($image)
                            <div class="mt-2">
                                <img src="{{ $image->temporaryUrl() }}" width="120" class="img-thumbnail">
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" wire:model.defer="password">
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" wire:model.defer="password_confirmation">
                        @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>


    <script>
        window.addEventListener('user-created', event => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data Users Berhasil Disimpan!',
                showConfirmButton: false,
                timer: 2000
            })
        });
    </script>
</div>
