@php
    /** @var $entry */
    $file = $entry->getUrl();
@endphp
<div class="row" id="" style="width: 300px;">
    <div class="col-3 mb-3" style="height: 250px">
        <a data-fancybox="galledatadatary" target="_blank" href="{{ asset($file) }}">
            {{ $entry->title }}
                </a>
            </div>
</div>


