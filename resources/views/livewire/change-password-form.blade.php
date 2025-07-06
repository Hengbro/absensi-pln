<form wire:submit.prevent="updatePassword">
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label">Password Lama</label>
        <input type="password" class="form-control @error('current_password') is-invalid @enderror" wire:model.defer="current_password">
        @error('current_password') 
            <div class="invalid-feedback">{{ $message }}</div> 
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <input type="password" class="form-control @error('new_password') is-invalid @enderror" wire:model.defer="new_password">
        @error('new_password') 
            <div class="invalid-feedback">{{ $message }}</div> 
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" wire:model.defer="new_password_confirmation">
        @error('new_password_confirmation') 
            <div class="invalid-feedback">{{ $message }}</div> 
        @enderror
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            <span wire:loading.remove>Simpan</span>
            <span wire:loading>
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Menyimpan...
            </span>
        </button>
    </div>
</form>