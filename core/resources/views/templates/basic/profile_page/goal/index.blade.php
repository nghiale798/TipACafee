<div class="card goal-gift-card mb-4 goal-wrapper">
    <div class="flex-between mb-3">
        <span class="badge badge-outline--base">{{ __($user->fullname) }} @lang('\'s Goal')</span>
        <div class="share flex-align gap-2 ms-auto">
            <div class="dropdown">
                <span class="icon" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                    @lang('Share') <i class="far fa-share-square"></i>
                </span>
                <div class="dropdown-menu share-action dropdown-menu-end">
                    <div class="social-list  gap-2 flex-wrap">
                        <a class="social-btn facebook flex-align" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"><i class="fab fa-facebook-f"></i> @lang('Facebook')</a>
                        <a class="social-btn linkedin flex-align" href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __($hasGoal->title) }}&amp;summary={{ __($hasGoal->description) }}" target="_blank"><i class="fab fa-linkedin-in"></i>@lang('Linkedin')</a>
                        <a class="social-btn twitter flex-align" href="https://twitter.com/intent/tweet?text={{ __($hasGoal->title) }}&amp;url={{ urlencode(url()->current()) }}" target="_blank"><i class="fab fa-twitter"></i>@lang('Twitter')</a>
                    </div>
                    <div class="text-center">
                        <button class="btn d-inline-flex btn-outline--base btn--sm copy-widget-btn" id="copyButton" data-profile="{{ route('home.page', $user->profile_link) }}" data-url="{{ route('goal.widget', $user->username) }}" type="button">
                            <i class="fa fa-copy"></i>@lang('Copy Widget for WebPage ') </span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h5>{{ __($hasGoal->title) }}</h5>
    <div class="event-bar-item mb-3">
        <div class="skill-bar">
            @php
                $goalTnxMessage = @$hasGoal->thanks_message;
                $collectGiftSum = $user?->goalLogs?->sum('amount') ?? 0;
                $percent = percent($hasGoal->starting_amount + $collectGiftSum, $hasGoal->target_amount);
            @endphp
            <div class="progressbar" data-perc="{{ progressPercent($percent > 100 ? '100' : $percent) }}%">
                <div class="bar"></div>
                <span class="label">{{ showAmount(progressPercent($percent > 100 ? '100' : $percent)) }}%</span>
            </div>
        </div>
    </div>

    <div class="seeMoreWrapper">
        <div class="text">
            <p>{{ __($hasGoal->description) }}</p>
        </div>
        <button class="more-button fs-12 underline" type="button">@lang('Show more')</button>
    </div>
    <h6 class="my-2">
        @lang('Achievement') {{ showAmount($percent) }}@lang('%') &#128525;
        @if ($hasGoal->view_publicly)
            @lang('of') {{ $general->cur_sym . showAmount($hasGoal->target_amount) }} {{ __($general->cur_text) }}
        @endif
    </h6>
    <div class="text-end mt-3 mt-md-0">
        <button class="btn d-inline-flex btn-outline--base btn--sm goalGiftBtn  @if ($user->id == $authUser?->id) disabled @endif" data-achivement="{{ showAmount(progressPercent($percent), 2) }}%" data-goal_for="{{ @$hasGoal->title }}" @if ($hasGoal->view_publicly) data-goal_target="{{ $general->cur_sym . showAmount($hasGoal->target_amount) }} {{ __($general->cur_text) }}" @endif>@lang('Send Gift Money')</button>
    </div>
</div>

<div class="modal fade" id="goalGiftModal">
    <div class="modal-dialog modal-dialog-centered preview-modal">
        <div class="modal-content header-share">
            <button class="btn-close close-preview flex-center" data-bs-dismiss="modal" type="button" aria-label="Close"> <i
                   class="fas fa-times"></i></button>
            <div class="modal-body text-center">
                <div class="modal-body__content share-action">
                    <h1 class="modal-body__title">@lang('Send gift for') {{ __($user->fullname) }} @lang('\'s goal')</h1>

                    <form action="{{ route('payment.index') }}" method="post">
                        @csrf
                        <input name="user_id" type="hidden" value="{{ $user->id }}">
                        <input name="goal_id" type="hidden" value="{{ @$hasGoal->id }}">

                        <div class="modal-body">
                            <ul class="list-group list-group-flush goalDetails"></ul>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control form--control" name="amount" type="number" placeholder="@lang('Enter goal gift money')" required>
                                <span class="input-group-text style-left">{{ __($general->cur_text) }}</span>
                            </div>
                        </div>

                        <button class="btn btn--base w-100" type="submit">
                            @lang('Send Gift Money')
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        "use strict";
        $(document).ready(function() {

            //progress-bar
            $(".progressbar").each(function() {
                $(this).find(".bar").animate({
                    "width": $(this).attr("data-perc")
                }, 3000);
                $(this).find(".label").animate({
                    "left": $(this).attr("data-perc")
                }, 3000);
            });

            // STARt-See-more-less//
            $(window).on('load', function() {
                showMoreAndLess('.seeMoreWrapper');
            });

            function showMoreAndLess(object) {
                $(object).each(function() {
                    var $maxLength = 175;
                    var $speed = 400;
                    var $ellipsis = '...';
                    var $firstTag = $(this).find('.text p:first-child');
                    var $longText = $firstTag.text();
                    var $shortText = '';
                    var $text = $(this).find('.text');
                    var $textHeight = $text.outerHeight();

                    if ($longText.length > $maxLength) {
                        $shortText = $longText.substr(0, $maxLength);
                        $shortText = $shortText.substr(0, Math.min($shortText.length, $shortText.lastIndexOf(' ')));
                    } else {
                        $shortText = $longText;
                    }

                    $firstTag.text($shortText + $ellipsis);

                    var $height = $firstTag.outerHeight();
                    $text.height($height);

                    $(window).on('resize', function() {
                        var $height = $firstTag.outerHeight();
                        var $textHeight = $text.outerHeight();
                        $text.height($height);
                    });

                    $(this).find('.more-button').click(function(e) {
                        $(this).parent().toggleClass('active');

                        var $text = $(this).parent().find('.text');

                        if ($(this).text() == 'Show more') {
                            $(this).text('Show less');
                            $text.find('p:first-child').text($longText);
                            $text.animate({
                                height: $textHeight
                            }, $speed);
                        } else {
                            $(this).text('Show more');

                            $text.animate({
                                height: $height
                            }, $speed);
                            setTimeout(function() {
                                $text.find('p:first-child').text($shortText + $ellipsis);
                            }, ($speed + 10));
                        }
                        e.stopPropagation();
                    });
                });

            }

            function titleCase(string) {
                return string.replaceAll("_", ' ').replace(/\w\S*/g, function(txt) {
                    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                });
            };


            // END-See-more-less//
            $(".goalGiftBtn").on('click', function() {
                var modal = $('#goalGiftModal');
                let data = $(this).data();
                let content = ``;
                $.each(data, function(index, value) {
                    content += `<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0"><span>@lang('${titleCase(index)}')</span><span class="type">${value}</span></li>`;
                });
                $('.goalDetails').html(content);

                modal.modal('show');
            });

            //Copy-widget-Start-Here//
            $("#copyButton").click(function() {
                var url = $('#copyButton').data('url');
                var profileUrl = $('#copyButton').data('profile');
                var poweredBy = '{{ gs('site_name') }}'
                var scriptContent = `fetch("${url}").then(response => response.json()).then(data => {
                    let progressBar = '<div style="background-color: rgb(0 0 0 / 10%);border-radius: 10px;height: 12px;max-width: 380px;margin: auto;">' +
                '<div style="background-color: rgb(255 193 7);color: white;text-align: right;font-size: 12px;border-radius: inherit;padding: 0;line-height: 1.2; font-weight: 500; height: 100%; display: flex; align-items: center; justify-content: center; position: relative; width: ' + data.progress_percent + '%;"><span class="w-percent" style="position: absolute; bottom: calc(100% + 5px); right: 0; color: black; font-size: 10px; border: 1px solid rgb(255 193 7 / 40%); padding: 2px 5px; display: flex; width: max-content;}">'+ data.progress_percent + ' %</span></div></div>';
                    let poweredBy = '<p>Powered By: ${poweredBy}</p>';
                    let sendGift = "&hearts; Send Gift to " + data.profile_name + "'s goal";
                    let images = '<img style="height: 100%";width: 100%; object-fit: cover;" src="' + data.user_image + '" alt="Profile Image"/>';
                    let title = '<h3 style="line-height: 1.2; font-size: 18px;font-weight: 600;margin-bottom: 6px;">' + data.title + '</h3>';
                    let description =  data.description;
                    let widgets = document.getElementsByClassName("goal-widget");
                    for (let i = 0; i < widgets.length; i++) {widgets[i].innerHTML = '<div style="height: 42px; width: 42px;margin: 0 auto 8px;border-radius: 50%;padding: 4px;border: 1px solid rgb(0 0 0 / 10%); overflow: hidden;">' + images + '</div>' + title + '<p style="line-height: 1.4;font-size: 14px;max-width: 400px;margin: 0 auto 27px;color: rgb(0 0 0 / 50%);display: -webkit-box;-webkit-line-clamp: 2;overflow: hidden;-webkit-box-orient: vertical;">' + description + '</p>' + progressBar + '<button style="margin-top: 12px; background: white; color: #10163a; font-size: 12px; padding: 8px 16px; line-height: 1; border-radius: 4px; font-weight: 500; border: 1px solid rgb(0 0 0 / 10%);" class="goal-btn" type="button">' + sendGift + '</button><div style="font-size: 12px; margin-top: 6px; color: #696969; text-decoration: underline;">'+poweredBy+'</div>';}
                }).catch(function(error) {
                    console.warn('Something went wrong.', error);
                    let widgets = document.getElementsByClassName("goal-widget");
                            for (let i = 0; i < widgets.length; i++) {
                                widgets[i].innerText = 'Failed to load widget.';
                            }
                });`;

                var goalWidgetStyle = "<style>.w-percent::after {content: '';border: 4px solid #ffc107;border-top-color: transparent;border-left-color: transparent;position: absolute;top: calc(100% - 4px);right: 4px;transform: rotate(45deg);}.goal-widget:hover .goal-btn {background-color: #ffc107 ! important;color: white !important;}</style>";
                var goalWidgetDiv = '<a href="' + profileUrl + '" class="goal-widget" style="text-align: center;display: block;max-width: 450px;margin: 50px auto;padding: 16px;border-radius: 8px;background-color: #ffff;box-shadow: 0px 5px 30px rgb(0 0 0 / 10%)"  target="_blank"></a>';
                var scriptTag = goalWidgetStyle + goalWidgetDiv + ' <script> ' + scriptContent + ' <\/script>';

                var tempElement = $("<textarea>").val(scriptTag).appendTo('body').select();
                try {
                    var successful = document.execCommand('copy');
                    var msg = successful ? 'successful' : 'unsuccessful';
                    iziToast.success({
                        message: "Script copied " + msg,
                        position: "topRight"
                    });
                } catch (err) {
                    console.error('Oops, unable to copy', err);
                }
                // Remove the temporary element
                tempElement.remove();
            });
            //Copy-widget-end-Here//
        });
    </script>
@endpush
