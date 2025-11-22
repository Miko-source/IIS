<!-- resources/views/components/flash-message.blade.php -->

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

@if (session('info'))
    <div class="flash-message info">
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

.auto-hide {
    animation-name: fadeIn, fadeOut;
    animation-duration: 0.5s, 0.5s;
    animation-timing-function: ease-out, ease-in;
    animation-delay: 0s, 8s;          /* tady je 8s viditelnosti */
    animation-fill-mode: forwards, forwards;
}


    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-8px); }
    }

    /* style of buttons*/
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
