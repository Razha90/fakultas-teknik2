@section('title', 'Edit Konten | Fakultas Teknik')

<div class="container">
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" enctype="multipart/form-data">
        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label">Judul</label>
                    <input type="text" wire:model="title" class="form-control" placeholder="Masukkan judul konten">
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" wire:model="slug" class="form-control" readonly>
                    @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3" wire:ignore>
                    <label class="form-label">Deskripsi</label>
                    <textarea id="editor1" class="form-control" rows="8" wire:model.defer="description"></textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-4">
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="beritaCheck" wire:model="is_berita">
                    <label class="form-check-label" for="beritaCheck">Centang jika ini konten berita</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Konten</label>
                    <select wire:model="content_types_id" class="form-control">
                        <option value="">-- Pilih Jenis Konten --</option>
                        @foreach($contentTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('content_types_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <div class="position-relative">
                        <div class="form-control" style="cursor:pointer;" wire:click="toggleCategoryDropdown">
                            Klik untuk memilih kategori
                        </div>
                        @if($showCategoryDropdown)
                            <div class="border rounded mt-1 p-2 position-absolute bg-white w-100 shadow" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                @foreach($categories as $cat)
                                    <div>
                                        <label class="form-check-label d-flex align-items-center gap-2">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   wire:click="toggleCategory('{{ $cat->id }}')"
                                                   @if(in_array($cat->id, $selected_categories)) checked @endif>
                                            {{ $cat->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="mt-2 d-flex flex-wrap gap-2">
                        @foreach($selected_categories as $catId)
                            @php $cat = $categories->firstWhere('id', $catId); @endphp
                            @if($cat)
                                <span class="badge bg-secondary d-flex align-items-center">
                                    {{ $cat->name }}
                                    <button type="button" class="btn-close btn-close-white btn-sm ms-2" wire:click="removeCategory('{{ $catId }}')" style="font-size: 0.6em;"></button>
                                </span>
                            @endif
                        @endforeach
                    </div>

                    <small class="text-muted">* Pilih kategori hanya jika jenis konten adalah <strong>Berita</strong></small>
                    @error('selected_categories') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select wire:model="status" class="form-control">
                        <option value="unpublished">Unpublished</option>
                        <option value="published">Publish</option>
                    </select>
                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Gambar</label>
                    <input type="file" wire:model="image" class="form-control">
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" width="50%" class="mt-2 rounded shadow-sm"/>
                    @elseif($content->image)
                        <img src="{{ asset('storage/'.$content->image) }}" width="50%" class="mt-2 rounded shadow-sm"/>
                    @endif
                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Simpan Konten</button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('contentSaved', () => {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Konten berhasil diUpdate.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('contents.index') }}";
                    }
                });
            });
        });
    </script>
</div>
