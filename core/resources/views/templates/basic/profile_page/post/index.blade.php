@extends($activeTemplate . 'layouts.profile')
@section('content')
    @php
        $coffee = @$user->donationSetting->donation_price ?? $general->starting_price;
    @endphp
    <div class="post-content template">
        <div class="template__inner py-44">
            @if (!blank($posts))
                <div class="search-filter flex-between pb-3">
                    <form class="search" method="GET">
                        <div class="input-group mb-3">
                            <input class="form-control form--control" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search here')">
                            <button class="input-group-text search-icon" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                    <div class="filter">
                        <div class="dropdown custom--dropdown">
                            <button class="btn btn--outline btn--base-outline dropdown-toggle post" data-bs-toggle="dropdown" type="button" aria-expanded="false">
                                @lang('Newest')
                            </button>
                            <ul class="dropdown-menu filter-btn">
                                <li class="filter-item" data-filter_type="1">@lang('Newest')</li>
                                <li class="filter-item" data-filter_type="2">@lang('Oldest')</li>
                                <li class="filter-item" data-filter_type="3">@lang('Popular')</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row gy-4 justify-content-center">
                @if (!blank($posts))
                    <div class="col-lg-7 order-0">
                        <div id="filteredDataContainer">
                            @include($activeTemplate . 'profile_page.post.list')
                        </div>
                    </div>

                    @if (!blank($popularPosts))
                        <div class="col-lg-5  order-1">
                            @include($activeTemplate . 'profile_page.post.popular')
                        </div>
                    @else
                        @include($activeTemplate . 'partials.empty', ['message' => 'No popular post found!'])
                    @endif
                @else
                    <div class="col-lg-5 col-md-7 col-sm-10">
                        @include($activeTemplate . 'partials.empty', ['message' => 'No post found!'])
                    </div>
                    <div class="col-lg-5 col-md-7 col-sm-10 order-1">
                        @include($activeTemplate . 'profile_page.support_member')
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        const isLoggedIn = "{{ auth()->check() }}";
        const likeURL = "{{ route('user.post.like') }}";

        //start-filter//
        $('.filter-btn .filter-item').on('click', function() {
            var filterType = $(this).data('filter_type');

            if (filterType == 1) {
                $('.dropdown-toggle').text("@lang('Newest')");
            } else if (filterType == 2) {
                $('.dropdown-toggle').text("@lang('Oldest')");
            } else if (filterType == 3) {
                $('.dropdown-toggle').text("@lang('Popular')");
            }

            var route = '{{ route('filter.post') }}';

            var filterData = {
                'filter_type': filterType,
                'user_id': `{{ $user->id }}`,
            }

            $.ajax({
                url: route,
                method: 'GET',
                data: filterData,
                success: function(response) {
                    if (response.success) {
                        $('#filteredDataContainer').html(response.html);
                    }
                },
                error: function(xhr, status, error) {
                    notify('error', error);
                }
            });
        })

        //end-filter//
    </script>
    <script src="{{ asset($activeTemplateTrue . 'js/central-js.js') }}"></script>
@endpush
