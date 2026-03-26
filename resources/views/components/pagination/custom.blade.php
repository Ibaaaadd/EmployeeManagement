@if ($paginator->hasPages())
    <style>
        .modern-pagination {
            display: flex;
            gap: 0.35rem;
            margin: 0;
            padding: 0;
            list-style: none;
            align-items: center;
        }
        .modern-pagination .page-item .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            border: none;
            border-radius: 10px;
            color: #64748b;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0 0.5rem;
            transition: all 0.2s ease;
            background: transparent;
            text-decoration: none;
        }
        .modern-pagination .page-item:not(.disabled):not(.active) .page-link:hover {
            background-color: #f1f5f9;
            color: #0f172a;
            transform: translateY(-1px);
        }
        .modern-pagination .page-item.active .page-link {
            background-color: #4f46e5;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }
        .modern-pagination .page-item.disabled .page-link {
            color: #cbd5e1;
            background: transparent;
            cursor: default;
        }
        .table-loading {
            opacity: 0.6;
            pointer-events: none;
            transition: opacity 0.2s;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center w-100 mt-2">
        <div class="text-muted small text-start">
            Menampilkan <span class="fw-bold text-dark">{{ $paginator->firstItem() }}</span> &mdash; <span class="fw-bold text-dark">{{ $paginator->lastItem() }}</span> dari <span class="fw-bold text-dark">{{ $paginator->total() }}</span> data
        </div>
        <div>
            <ul class="modern-pagination justify-content-end">
                {{-- Skip Backward 10 Pages --}}
                @if ($paginator->currentPage() > 10)
                    <li class="page-item">
                        <a class="page-link ajax-pagination-link" href="{{ $paginator->url($paginator->currentPage() - 10) }}" aria-label="Mundur 10 Halaman">
                            <i class="fa-solid fa-angles-left"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" aria-hidden="true"><i class="fa-solid fa-angles-left"></i></span>
                    </li>
                @endif

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link ajax-pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fa-solid fa-angle-left"></i></a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $start = max($paginator->currentPage() - 2, 1);
                    $end = min($paginator->currentPage() + 2, $paginator->lastPage());
                    
                    if ($end - $start < 4) {
                        if ($start == 1) {
                            $end = min(5, $paginator->lastPage());
                        } else {
                            $start = max($paginator->lastPage() - 4, 1);
                        }
                    }
                @endphp

                @if ($start > 1)
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link ajax-pagination-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                    @endif
                @endfor

                @if ($end < $paginator->lastPage())
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link ajax-pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fa-solid fa-angle-right"></i></a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                    </li>
                @endif

                {{-- Skip Forward 10 Pages --}}
                @if ($paginator->currentPage() + 10 <= $paginator->lastPage())
                    <li class="page-item">
                        <a class="page-link ajax-pagination-link" href="{{ $paginator->url($paginator->currentPage() + 10) }}" aria-label="Maju 10 Halaman">
                            <i class="fa-solid fa-angles-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" aria-hidden="true"><i class="fa-solid fa-angles-right"></i></span>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <script>
        if (!window.hasAjaxPaginationStarted) {
            window.hasAjaxPaginationStarted = true;
            document.addEventListener('click', function(e) {
                const link = e.target.closest('.ajax-pagination-link');
                if (!link) return;
                
                e.preventDefault();
                const url = link.href;
                if (!url || url === '#') return;

                // Cari container tabel terdekat (berasumsi tabel ada di dalam .card atau ada container khusus)
                const tableContainer = link.closest('.card') || link.closest('.table-responsive')?.parentElement || document.body;
                
                // Tambahkan efek loading blur
                tableContainer.classList.add('table-loading');

                fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const isCard = link.closest('.card');
                    if (isCard) {
                        // Ganti konten spesifik .card agar header lain tidak ter-refresh
                        const allObj = Array.from(document.querySelectorAll('.card'));
                        const index = allObj.indexOf(isCard);
                        const newObj = Array.from(doc.querySelectorAll('.card'));
                        
                        if (newObj[index]) {
                            isCard.innerHTML = newObj[index].innerHTML;
                            window.history.pushState(null, '', url);
                        } else {
                            window.location.href = url; // fallback
                        }
                    } else {
                         // Coba fallback replace body if not in standard card container
                        window.location.href = url;
                    }
                    
                    tableContainer.classList.remove('table-loading');
                })
                .catch(err => {
                    console.error('Error fetching Next Page:', err);
                    window.location.href = url; 
                });
            });
            
            // Tambahkan dukungan back button untuk AJAX state 
            window.addEventListener('popstate', function() {
                window.location.reload();
            });
        }
    </script>
@endif