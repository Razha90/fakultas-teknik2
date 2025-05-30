@section('title', 'User Profile | Fakultas Teknik')
<div class="row">
    {{-- SIDEBAR KIRI --}}
    <div class="col-lg-4 col-xlg-3 col-md-5">
        <div class="card">
            <div class="card-body">
                <center class="m-t-30">
                    <div class="form-group">
                        <label>Foto Profil</label>
                        <div class="mb-2">
                            @if ($newImage)
                                <img src="{{ $newImage->temporaryUrl() }}" class="rounded-circle" width="150" height="150">
                            @else
                                <img src="{{ $image ? asset('storage/' . $image) : asset('sbAdmin/img/undraw_profile.svg') }}" class="rounded-circle" width="150" height="150">
                            @endif
                        </div>
                        <input type="file" class="form-control" wire:model="newImage">
                        @error('newImage') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <h4 class="card-title m-t-10">{{ Auth::user()->fullname }}</h4>
                    <h6 class="card-title m-t-10">{{ Auth::user()->position }}</h6>
                    <br>
                    <h6 class="card-subtitle">
                        {{ Auth::user()->role }} {{ Auth::user()->department->name ?? 'Tidak ada departemen' }}
                    </h6>
                </center>
            </div>
            <hr>
            <div class="card-body">
                <small class="text-muted">Email address</small>
                <h6>{{ Auth::user()->email }}</h6>

                <small class="text-muted p-t-30 db">Phone</small>
                <h6>{{ Auth::user()->phone_number ?? '+62 812 3456 7890' }}</h6>


            </div>
        </div>
    </div>

    {{-- KONTEN KANAN --}}
    <div class="col-lg-8 col-xlg-9 col-md-7">
        <div class="card">
            <div class="card-body">
                {{-- NAVIGATION TABS --}}
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'timeline' ? 'active' : '' }}" wire:click="setTab('timeline')">Timeline</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'setting' ? 'active' : '' }}" wire:click="setTab('setting')">Setting</button>
                    </li>
                </ul>

                {{-- KONTEN TAB --}}
                @if ($activeTab === 'timeline')
                <h5>Riwayat Konten yang Dibuat</h5>

                @if ($contents->isEmpty())
                <p class="text-muted">Belum ada konten yang Anda buat.</p>
            @else
                <div class="timeline">
                    @foreach ($contents as $content)
                        <div class="timeline-item mb-4">
                            <h6 class="mb-1">{{ $content->title }}</h6>
                            <small class="text-muted">{{ $content->created_at->format('d M Y - H:i') }}</small>
                            <p class="mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($content->description), 100) }}</p>
                            <span class="badge bg-primary">{{ $content->type->name ?? 'Tipe tidak tersedia' }}</span>
                            <span class="badge bg-secondary">{{ ucfirst($content->status) }}</span>
                        </div>
                        <hr>
                    @endforeach
                </div>
            @endif

                @elseif ($activeTab === 'profile')
                    <p><strong>Nama Lengkap:</strong> {{ $name }}</p>
                    <p><strong>Email:</strong> {{ $email }}</p>
                @elseif ($activeTab === 'setting')
                    @if (session()->has('message'))
                        <div class="alert alert-success">{{ session('message') }}</div>
                    @endif

                    <form wire:submit.prevent="updateProfile">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" wire:model.defer="username">
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" wire:model.defer="fullname">
                            @error('fullname') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" wire:model.defer="email">
                        </div>

                        <div class="form-group">
                            <label>No Telepon</label>
                            <input type="text" class="form-control" wire:model.defer="phone_number">
                        </div>

                        <div class="form-group">
                            <label>Program Studi</label>
                            <select class="form-control" wire:model.defer="department_id">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Posisi</label>
                            <input type="text" class="form-control" wire:model.defer="position">
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" wire:model.defer="password" placeholder="Biarkan kosong jika tidak ingin mengubah">
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Simpan Perubahan</button>
                    </form>

                @endif
            </div>
        </div>
    </div>
</div>
