@php
    $socialIcons = getContent('social_icon.element', orderById: true);
    $policyPages = getContent('policy_pages.element', false, null, true);
    $pages = App\Models\Page::where('tempname', $activeTemplate)
        ->where('is_default', Status::NO)
        ->get();
@endphp

<footer class="footer-area">
    <div class="py-sm-3 py-2 pb-4">
        <div class="container">
            <div class="row gy-2">
                <div class="col-lg-6">
                    <div class="footer-item">
                        <ul class="footer-menu">
                            @if ($general->multi_language)
                                <li class="footer-menu__item footer-lang">
                                    <div class="footer-item profile-footer-item">
                                        <div class="language-box ps-0">
                                            @include($activeTemplate . 'partials.language')
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @foreach ($pages as $k => $data)
                                <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a></li>
                            @endforeach
                            @foreach ($policyPages as $policy)
                                <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('policy.pages', [slug(@$policy->data_values->title), @$policy->id]) }}">
                                        {{ __(@$policy->data_values->title) }} </a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="flex-align copyright-share">
                        <div class="bottom-footer-text">
                            &copy; {{ date('Y') }} <a class="text--base" href="{{ route('home') }}">{{ $general->site_name }}</a>. @lang('All Rights Reserved')
                        </div>
                        <span class="hide-on-mobile">|</span>
                        <div class="footer-item">
                            <ul class="footer-menu flex-align">
                                @foreach ($socialIcons as $social)
                                    <li class="footer-menu__item">
                                        <a class="footer-menu__social" href="{{ @$social->data_values->url }}" target="_blank">@php echo @$social->data_values->social_icon @endphp</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
