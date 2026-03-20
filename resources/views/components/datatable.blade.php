<div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: var(--card-radius, 20px);">
    @if(isset($title) || isset($action))
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center" style="border-radius: var(--card-radius, 20px) var(--card-radius, 20px) 0 0; border-bottom: 2px solid #f1f5f9;">
        <h6 class="mb-0 text-dark fw-bold">
            @if(isset($icon)) <i class="{{ $icon }} text-primary me-2"></i> @endif
            {{ $title ?? 'Tabel Data' }}
        </h6>
        @if(isset($action))
            {{ $action }}
        @endif
    </div>
    @endif
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="{{ $id ?? 'customDatatable' }}">
                <thead class="bg-light">
                    <tr>
                        {{ $head }}
                    </tr>
                </thead>
                <tbody>
                    {{ $body }}
                </tbody>
            </table>
        </div>
        @if(isset($empty) && $empty == true)
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                    <i class="fa-regular fa-folder-open text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
                <h5 class="text-dark fw-bold">Belum ada data</h5>
                <p class="text-muted small">Data masih kosong.</p>
                @if(isset($emptyAction))
                    {{ $emptyAction }}
                @endif
            </div>
        @endif
    </div>
</div>