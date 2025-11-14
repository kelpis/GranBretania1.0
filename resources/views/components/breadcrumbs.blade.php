@props(['items' => []])

<nav class="text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
  <ol class="list-none p-0 inline-flex">
    @foreach($items as $i => $item)
      @php $isLast = $i === count($items) - 1; @endphp
      <li class="flex items-center {{ $isLast ? 'text-gray-500' : '' }}" @if($isLast) aria-current="page" @endif>
        @if(!$isLast && !empty($item['url']))
          <a href="{{ $item['url'] }}" class="text-azul hover:text-rojo">{{ $item['label'] }}</a>
        @else
          <span>{{ $item['label'] }}</span>
        @endif

        @if(!$isLast)
          <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
          </svg>
        @endif
      </li>
    @endforeach
  </ol>
</nav>
