@props([
    'image',
    'name',
    'price',
    'oldPrice' => null,
    'tag' => null,
    'tagLabel' => null,
    'quickAddText' => __('homev2.products.quick_add'),
    'alt' => null,
])

<div {{ isset($attributes) ? $attributes->merge(['class' => 'product-card']) : 'class="product-card"' }}>
    <div class="product-img-wrap">
        <img src="{{ $image }}" alt="{{ $alt ?? $name }}"
             style="width:100%;aspect-ratio:3/4;object-fit:cover;display:block;">
        <div class="product-quick-add">{{ $quickAddText }}</div>
        @if($tag && $tagLabel)
            <span class="product-tag {{ $tag }}">{{ $tagLabel }}</span>
        @endif
    </div>
    <p class="product-name">{{ $name }}</p>
    <p class="product-price">
        {!! $price !!}
        @if($oldPrice)
            <span class="old-price">{!! $oldPrice !!}</span>
        @endif
    </p>
</div>
