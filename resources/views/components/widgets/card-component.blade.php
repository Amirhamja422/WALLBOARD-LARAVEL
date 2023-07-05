<div class="card radius-6">
    @if ($cardHeaderVisible)
        <div class="card-header bg-transparent">
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0">{{ $cardTitle ?? '' }}</h6>
                </div>
                @if ($btnVisible)
                    <div class="ms-auto">
                        <span class="badge bg-{{ $btnClass }} text-dark" type="button" data-bs-toggle="modal"
                            data-bs-target="#createModal"><i
                                class="bi bi-{{ $btnIcon }}"></i>&nbsp;{{ $btnName }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
    {{ $more_data ?? '' }}
</div>
