{{-- usage: @include('admin.partials.pagination', ['paginator' => $students]) --}}
@if ($paginator->total() > 0)
<div class="table-foot">
  <div>Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}</div>
  @if ($paginator->hasPages())
  <ul class="pager">
    <li>@if ($paginator->onFirstPage())<span class="off"><i class="bi bi-chevron-left"></i></span>@else<a href="{{ $paginator->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>@endif</li>
    @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
      <li>@if ($page == $paginator->currentPage())<span class="on">{{ $page }}</span>@else<a href="{{ $url }}">{{ $page }}</a>@endif</li>
    @endforeach
    <li>@if ($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>@else<span class="off"><i class="bi bi-chevron-right"></i></span>@endif</li>
  </ul>
  @endif
</div>
@endif
