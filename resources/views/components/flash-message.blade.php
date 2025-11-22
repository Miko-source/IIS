<!-- resources/views/components/flash-message.blade.php -->
@if (session('error'))
    <div class="alert alert-danger fade show auto-hide mt-3" role="alert">
        {{ session('error') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success fade show auto-hide mt-3" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info fade show auto-hide mt-3" role="alert">
        {{ session('info') }}
    </div>
@endif


<style>
    .flash-message {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 16px;
        border: 1px solid;
    }
    .flash-message.error {
        background: #71679cff;
        color: #991b1b;
    }
    .flash-message.success {
        background: #d1fae5;
        color: #065f46;
    }
    .flash-message.info {
        background: #dbeafe;
        color: #1e40af;
    }
</style>