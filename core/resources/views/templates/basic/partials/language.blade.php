@php
    $language = App\Models\Language::all();
    $langDetails = $language->where('code', config('app.locale'))->first();
@endphp

<div class="dropdown-lang dropdown">
    <div class="language-btn dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
        <img class="flag" src="{{ getImage(getFilePath('flag') . '/' . @$langDetails->flag, getFileSize('flag')) }}"
            alt="{{ __(@$langDetails->name) }}">
        <span class="language-text">{{ __(@$langDetails->name) }}</span>
    </div>
    <ul class="dropdown-menu">
        @foreach ($language as $item)
            <li class="langSel" data-code="{{ @$item->code }}">
                <span class="lang--list">
                    <img class="flag" src="{{ getImage(getFilePath('flag') . '/' . @$item->flag, getFileSize('flag')) }}"
                        alt="{{ __(@$item->name) }}">{{ __(@$item->name) }}
                </span>
            </li>
        @endforeach
    </ul>
</div>

@push('script')
    <script>
        "use stric";
        $(document).ready(function() {
            $('.langSel').on('click', function(e) {
                let langCode = $(this).data('code');
                window.location.href = "{{ route('home') }}/change/" + langCode;
            });

            $('.custom--dropdown > .custom--dropdown__selected').on('click', function() {
                $(this).parent().toggleClass('open');
            });
            $('.custom--dropdown > .dropdown-list > .dropdown-list__item').on('click', function() {
                $('.custom--dropdown > .dropdown-list > .dropdown-list__item').removeClass('selected');
                $(this).addClass('selected').parent().parent().removeClass('open').children('.custom--dropdown__selected').html($(this)
                    .html());
            });
            $(document).on('keyup', function(evt) {
                if ((evt.keyCode || evt.which) === 27) {
                    $('.custom--dropdown').removeClass('open');
                }
            });
            $(document).on('click', function(evt) {
                if ($(evt.target).closest(".custom--dropdown > .custom--dropdown__selected").length === 0) {
                    $('.custom--dropdown').removeClass('open');
                }
            });
        });
    </script>
@endpush
