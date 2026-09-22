
@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Pagination">
        <div class="pagination-info">
            Menampilkan
            <strong>{{ $paginator->firstItem() ?? 0 }}</strong>
            –
            <strong>{{ $paginator->lastItem() ?? 0 }}</strong>
            dari
            <strong>{{ $paginator->total() }}</strong>
            data
        </div>

        <div class="pagination-list">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-link disabled" aria-disabled="true">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="pagination-link"
                    rel="prev"
                    aria-label="Halaman sebelumnya"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)

                {{-- "Three Dots" --}}
                @if (is_string($element))
                    <span class="pagination-link pagination-dots">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="pagination-link active"
                                aria-current="page"
                            >
                                {{ $page }}
                            </span>
                        @else
                            <a
                                href="{{ $url }}"
                                class="pagination-link"
                                aria-label="Ke halaman {{ $page }}"
                            >
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif

            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="pagination-link"
                    rel="next"
                    aria-label="Halaman berikutnya"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </a>
            @else
                <span class="pagination-link disabled" aria-disabled="true">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </span>
            @endif

        </div>
    </nav>
@endif