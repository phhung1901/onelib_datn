@php
    /** @var $entry */
    $file = $entry->getUrl();
@endphp
<a data-fancybox="galledatadatary" target="_blank" href="{{ asset($file) }}">
    {{ $entry->id }}
</a>


