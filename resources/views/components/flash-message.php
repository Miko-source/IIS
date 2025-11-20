@if (session('error'))
    <div class="flash-message error">
        {{ session('error') }}
    </div>
@endif

@if (session('success'))
    <div class="flash-message success">
        {{ session('success') }}
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
        background: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }
    .flash-message.success {
        background: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }
</style>