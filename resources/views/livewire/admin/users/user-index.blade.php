@section('title', 'Users | FT')
<div>
    {{-- Be like water. --}}
    <h1>Users</h1>
    <div class="d-flex justify-content-start mr-3">
        <a href="/users/create" wire:navigate class="btn btn-primary">
            + Add User
        </a>
    </div>
    <br>
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

    <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">No.</th>
            <th scope="col">Photo Profil</th>
            <th scope="col">Fullname</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
            <th scope="col">Prodi</th>
            <th scope="col">Phone</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($user->image)
                            <img src="{{ asset('storage/'.$user->image) }}" alt="Profile" width="40" height="40" style="border-radius: 50%;">
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $user->fullname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>{{ $user->department->name ?? '-' }}</td> <!-- ini penting -->
                    <td>{{ $user->phone_number }}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" wire:click="confirmDelete('{{ $user->id }}')">
                            Hapus
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>

      </table>

      @if ($confirmingUserDeletion)
      <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Konfirmasi Hapus</h5>
              <button type="button" class="btn-close" aria-label="Close" wire:click="$set('confirmingUserDeletion', false)"></button>
            </div>
            <div class="modal-body">
              <p>Apakah kamu yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" wire:click="$set('confirmingUserDeletion', false)">Batal</button>
              <button type="button" class="btn btn-danger" wire:click="deleteUser">Ya, Hapus</button>
            </div>
          </div>
        </div>
      </div>
      @endif
</div>
