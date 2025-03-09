@php
    $bannerContent = getContent('banner.content', true);
@endphp
<section class="banner-section ">
    <div class="container">
        <form action="{{ route('get.started') }}" method="GET">
            <div class="row">
                <div class="col-12">
                    <div class="banner-content">
                        <h1 class="banner-content__title s-highlight" data-s-break="1" data-s-length="1">{{ __(@$bannerContent->data_values->heading) }}</h1>
                        <div class="banner-content__input input-group">
                            <span class="input-group-text" id="basic-addon3">{{ route('home') }}/</span>
                            <input class="form-control" name="username" type="text" placeholder="@lang('yourname')" autocomplete="off">
                        </div>
                        <div class="banner-content__button">
                            <span class="animation animation__left">
                                <svg width="68" height="74" viewBox="0 0 68 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M22.4259 68.5278C16.0259 66.7318 9.32534 65.8258 2.82534 64.9958C1.42534 64.8218 0.125535 65.7928 0.0255346 67.1608C-0.174465 68.5298 0.826121 69.7818 2.12612 69.9557C8.42612 70.7548 14.9255 71.6097 21.0255 73.3387C22.3255 73.7137 23.7261 72.9418 24.1261 71.6138C24.5261 70.2868 23.7259 68.9038 22.4259 68.5278Z" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M41.8251 43.0648C31.5251 32.5538 19.9251 23.3958 9.8251 12.6028C8.9251 11.5948 7.3251 11.5408 6.3251 12.4818C5.3251 13.4238 5.22549 15.0078 6.22549 16.0158C16.3255 26.8398 27.9255 36.0278 38.2255 46.5698C39.2255 47.5538 40.8251 47.5678 41.8251 46.5998C42.7251 45.6328 42.8251 44.0488 41.8251 43.0648Z" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M61.1264 2.63576C61.4264 8.65176 61.7259 14.6678 62.0259 20.6848C62.0259 22.0628 63.2264 23.1268 64.6264 23.0598C66.0264 22.9918 67.0259 21.8188 67.0259 20.4398C66.7259 14.4138 66.4264 8.38876 66.1264 2.36376C66.0264 0.985757 64.8262 -0.0712432 63.4262 0.00375683C62.1262 0.0787568 61.0264 1.25876 61.1264 2.63576Z" />
                                </svg>
                            </span>
                            <button class="btn" type="submit">
                                {{ __(@$bannerContent->data_values->button_name) }} <span class="icon"><i class="fas fa-arrow-right"></i></span>
                            </button>
                            <span class="animation animation__right">
                                <svg width="68" height="74" viewBox="0 0 68 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M22.4259 68.5278C16.0259 66.7318 9.32534 65.8258 2.82534 64.9958C1.42534 64.8218 0.125535 65.7928 0.0255346 67.1608C-0.174465 68.5298 0.826121 69.7818 2.12612 69.9557C8.42612 70.7548 14.9255 71.6097 21.0255 73.3387C22.3255 73.7137 23.7261 72.9418 24.1261 71.6138C24.5261 70.2868 23.7259 68.9038 22.4259 68.5278Z" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M41.8251 43.0648C31.5251 32.5538 19.9251 23.3958 9.8251 12.6028C8.9251 11.5948 7.3251 11.5408 6.3251 12.4818C5.3251 13.4238 5.22549 15.0078 6.22549 16.0158C16.3255 26.8398 27.9255 36.0278 38.2255 46.5698C39.2255 47.5538 40.8251 47.5678 41.8251 46.5998C42.7251 45.6328 42.8251 44.0488 41.8251 43.0648Z" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M61.1264 2.63576C61.4264 8.65176 61.7259 14.6678 62.0259 20.6848C62.0259 22.0628 63.2264 23.1268 64.6264 23.0598C66.0264 22.9918 67.0259 21.8188 67.0259 20.4398C66.7259 14.4138 66.4264 8.38876 66.1264 2.36376C66.0264 0.985757 64.8262 -0.0712432 63.4262 0.00375683C62.1262 0.0787568 61.0264 1.25876 61.1264 2.63576Z" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-sm btn-trigger fs-12 mt-2">{{ __(@$bannerContent->data_values->title) }}</p>
                    </div>
                </div>
            </div>
        </form>
        <div class="banner-icons">
            <span class="icon-list twitch"><img src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image_one, '30x30') }}" alt=""></span>
            <span class="icon-list behance"><img src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image_two, '30x30') }}" alt=""></span>
            <span class="icon-list reddit"><img src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image_three, '30x30') }}" alt=""></span>
            <span class="icon-list spotify"><img src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image_four, '30x30') }}" alt=""></span>
            <span class="icon-list codePen"><img src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image_five, '30x30') }}" alt=""></span>
            <span class="icon-list tikTok"><img src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image_six, '30x30') }}" alt=""></span>
            <span class="icon-list rss"><img src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image_seven, '30x30') }}" alt=""></span>
            <span class="icon-list gitHub"><img src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image_eight, '30x30') }}" alt=""></span>
            <span class="icon-list vimeo"><img src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image_nine, '30x30') }}" alt=""></span>
            <span class="icon-list medium"><img src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image_ten, '30x30') }}" alt=""></span>
            <span class="icon-list soundCloud"><img src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image_eleven, '30x30') }}" alt=""></span>
        </div>
    </div>
</section>
