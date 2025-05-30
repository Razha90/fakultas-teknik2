<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.bootstraps-layout')] class extends Component {
    //
}; ?>

<div x-data="initWelcome">
    <button class="btn btn-primary">Tombol Utama</button>
    <button class="btn btn-secondary">Tombol Kedua</button>
    <button class="btn btn-success">Tombol Sukses</button>
    <button class="btn btn-danger"><i class="bi bi-0-circle"></i>Tombol Error</button>
    
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Judul Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Ini isi modal
                </div>
            </div>
        </div>
    </div>

    <button @click="showModal()">Buka Modal dari JS</button>
</div>
<script>
    function initWelcome() {
        return {
            showModal() {
                var modalEl = document.getElementById('exampleModal');
                var modal = new window.bootstrap.Modal(modalEl);

                modal.show();
            }
        }
    }
</script>
