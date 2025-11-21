<div class="card my-3">
    <div class="card-body">
        @isset($title)
            <h5 class="card-title">{{ $title }}</h5>
        @endisset

        {{ $slot }}
    </div>
</div>
