@extends($activeTemplate . 'layouts.master')
@section('content')
    <h2 class="page-title">{{ __($pageTitle) }}</h2>
    <div class="multi-page-card">
        @include($activeTemplate . 'user.explore.navbar')
        <div class="multi-page-card__body">
            @if (blank(@$followings))
                <div class="no-donation-view">
                    <span class="no-donation-view__icon flex-center">
                        <i class="las la-user-check"></i>
                    </span>
                    <h5 class="no-donation-view__title">@lang('You don\'t have any following yet!')</h5>
                    <div class="no-donation-view__content">
                        <p class="no-donation-view__share">
                            <span class="share pageShareBtn">@lang('Share your page')</span>
                            @lang('with your audience and supporters.')
                        </p>
                    </div>
                </div>
            @else
                @foreach ($followings as $data)
                    @php
                        $following = $data->user;
                    @endphp
                    <div class="creator-wrapper">
                        <div class="creator-item">
                            <div class="creator-item__left">
                                <div class="creator-item__profile">
                                    <div class="thumb">
                                        <img src="{{ avatar($following->image ? getFilePath('userProfile') . '/' . $following->image : null) }}" alt="">
                                    </div>
                                    <div class="content">
                                        <h6 class="title mb-0">
                                            {{ __(@$following->fullname) }}
                                        </h6>
                                        <span class="mail">
                                            {{ @$following->email }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="creator-item__right">
                                <div class="creator-item__right-date">
                                    <span>@lang('Following since') {{ showDateTime($data->created_at, 'F Y') }}</span>
                                </div>
                                <div class="creator-item__right-action">
                                    <div class="post-list-card__option">
                                        <a class="action-list_btn" href="{{ route('home.page', $following->profile_link) }}" title="@lang('Following User Profile')" target="_blank"> <i class="las la-external-link-alt"></i></a>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                @endforeach

                @if ($followings->hasPages())
                    <div class="mt-3">
                        {{ paginateLinks($followings) }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
