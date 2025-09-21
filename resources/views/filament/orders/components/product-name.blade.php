@if($record->product?->getFirstMediaUrl('products', 'preview'))
    <img 
        src="{{ $record->product->getFirstMediaUrl('products', 'preview') }}" 
        alt="{{ $record->product->name }}" 
        class="w-10 h-10 rounded-md object-cover"
    >
@else
    <div class="w-10 h-10 bg-gray-300 rounded-md flex items-center justify-center text-gray-500 text-xs">
        Resim yok
    </div>
@endif
<span class="truncate font-medium text-gray-800">
    {{ $record->product?->name ?? '—' }}
</span>
