@props([
    'placeholder' => 'Search...',
    'btn' => 'btn--primary',
    'dateSearch' => 'no',
    'keySearch' => 'yes',
    'visibilityFilter' => 'no',
    'StatusFilter' => 'no',
])

<form class="d-flex flex-wrap gap-2 filter" action="" method="GET">
    @if ($keySearch == 'yes')
        <x-search-key-field placeholder="{{ $placeholder }}" btn="{{ $btn }}" />
    @endif
    @if ($dateSearch == 'yes')
        <x-search-date-field />
    @endif
    @if ($visibilityFilter == 'yes')
        <x-visibility-filter />
    @endif
    @if ($StatusFilter == 'yes')
        <x-status-filter />
    @endif

</form>
