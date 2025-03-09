@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $goalContent = getContent('goal.content', true);
    @endphp
    <div class="d-flex gap-2 flex-wrap justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0">{{ __($pageTitle) }}</h2>
        <a class="btn btn--base btn--sm" href="{{ route('user.goal.gift.history') }}"> <i class="las la-gift"></i> @lang('Goal Gift Log')</a>
    </div>

    <div class="enable-membership">
        <div class="enable-membership__header">
            <h2 class="enable-membership__title mb-1">{{ __(@$goalContent->data_values->heading) }}</h2>
            <p class="enable-membership__sub-title fs-16">{{ __(@$goalContent->data_values->subheading) }}</p>
            @if (@!$runningGoal)
                <button class="btn btn--base enable-membership__enable-btn goalBtn" type="button">
                    @lang('Set a Goal')
                    <span class="icon"><i class="fas fa-flag"></i></span>
                </button>
            @endif
        </div>

        @if (!blank($allGoals))
            <div class="accordion donation--accordion" id="transactionAccordion">
                @foreach ($allGoals as $goal)
                    <div class="accordion-item border-0">
                        <div class="accordion-header" id="h-{{ $loop->index + 1 }}">
                            <div class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#c-{{ $loop->index + 1 }}" role="button" aria-expanded="false" aria-controls="c-1">
                                <div class="col-sm-6 col-12">
                                    <div class="left flex-align">
                                        <div class="content">
                                            <h6 class="title mb-0">{{ __($goal->title) }}</h6>
                                            <h6 class="title mb-2"> @php echo $goal->statusBadge; @endphp</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 text-end px-3">
                                    <div class="title mb-1">@lang('Starting')</div>
                                    <p class="donation-time">{{ $general->cur_sym }}{{ showAmount($goal->starting_amount) }}</p>
                                </div>
                                <div class="col-3 text-end">
                                    <div class="title mb-1">@lang('Target')</div>
                                    <p class="donation-user">{{ $general->cur_sym }}{{ showAmount($goal->target_amount) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-collapse collapse" id="c-{{ $loop->index + 1 }}" data-bs-parent="#transactionAccordion" aria-labelledby="h-{{ $loop->index + 1 }}" style="">
                            <div class="accordion-body">
                                <ul class="caption-list">
                                    <li class="caption-list__item">
                                        <h6 class="caption">@lang('Achievement')</h6>
                                        <span class="value">{{ $general->cur_sym . showAmount($goal->goal_logs_sum_amount) }}</span>
                                    </li>
                                    <li class="caption-list__item">
                                        <h6 class="caption">@lang('Description')</h6>
                                        <span class="value">{{ __($goal->description) }}</span>
                                    </li>
                                    <li class="caption-list__item">
                                        <h6 class="caption">@lang('Thank You Message')</h6>
                                        <span class="value">{{ __(@$goal->thanks_message) }}</span>
                                    </li>
                                    @if ((@$runningGoal && $goal->status == Status::RUNNING) || (@!$runningGoal && $goal->status == Status::DISABLE))
                                        <li class="caption-list__item">
                                            <h6 class="caption">@lang('Actions')</h6>
                                            <div class="value d-flex gap-3">
                                                @if (@$runningGoal && $goal->status == Status::RUNNING)
                                                    <button class="btn follow-button--sm btn--outline goalBtn" data-resource="{{ @$goal }}" type="button">
                                                        <i class="las la-pen"></i> @lang('Edit')
                                                    </button>
                                                    <button class="btn follow-button--sm btn--outline confirmationAltBtn" data-question="@lang('Are you sure you want to proceed? Once done, this cannot be undone.')" data-action="{{ route('user.goal.complete', @$goal->id) }}" type="button">
                                                        <i class="las la-check"></i> @lang('Complete')
                                                    </button>
                                                    <button class="btn follow-button--sm btn--outline confirmationAltBtn" data-question="@lang('Are you sure to cancel this goal?')" data-action="{{ route('user.goal.status', [@$goal->id, 'flag' => 0]) }}" type="button">
                                                        <i class="las la-ban"></i> @lang('Cancel')
                                                    </button>
                                                @elseif (@!$runningGoal && $goal->status == Status::DISABLE)
                                                    <button class="btn follow-button--sm btn--outline confirmationAltBtn" data-question="@lang('Are you sure to run this goal?')" data-action="{{ route('user.goal.status', [@$goal->id, 'flag' => 1]) }}" type="button">
                                                        <i class="las la-running"></i> @lang('Run')
                                                    </button>
                                                    <button class="btn follow-button--sm btn--outline confirmationAltBtn" data-question="@lang('Are you sure you want to proceed? Once done, this cannot be undone.')" data-action="{{ route('user.goal.complete', @$goal->id) }}" type="button">
                                                        <i class="las la-check"></i> @lang('Complete')
                                                    </button>
                                                @endif
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <x-confirmation-alert />

    <div class="modal fade setGoalModal custom--modal" id="edit-img-modal" aria-labelledby="addimg-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h4 class="modal-title"></h4>
                    <button data-bs-dismiss="modal" type="button" aria-label="Close">
                        <span class="icon fs-20"><i class="fa fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="goalForm" action="{{ route('user.goal.store') }}" method="POST">
                        @csrf
                        <div class="gallery-modal-item">
                            <div class="form-group">
                                <label class="form--label">@lang('Title')</label>
                                <input class="form--control" name="title" type="text" placeholder="@lang('E.g. New laptop/New Phone')" required>
                            </div>
                            <div class="form-group">
                                <label class="form--label">@lang('Description')</label>
                                <textarea class="form--control" name="description" placeholder="@lang('Give a short discription of why you need to reach your goal.')" required></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form--label">@lang('Target Amount')</label>
                                <div class="input-group">
                                    <input class="form-control form--control" name="target_amount" type="number" required>
                                    <span class="input-group-text style-left">{{ __($general->cur_text) }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form--label">@lang('Starting Amount') <i class="las la-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('You may want include to income you have already received.')"></i></label>
                                <div class="input-group">
                                    <input class="form-control form--control" name="starting_amount" type="number" required>
                                    <span class="input-group-text style-left">{{ __($general->cur_text) }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="privet-message form--check" for="view_publicly">
                                    <span class="custom--check">
                                        <input class="form-check-input" id="view_publicly" name="view_publicly" type="checkbox">
                                    </span>
                                    <p class="form-check-label text--base"><i class="las la-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('By default it\'s not showed on publicly.')"></i> <small>@lang(' If You prefer to display your goal amount publicly, Please check this box.')</small></p>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="form--label">@lang('Thank You Message')</label>
                                <textarea class="form--control" name="thanks_message" required></textarea>
                            </div>
                            <input name="id" type="hidden">
                        </div>
                        <div class="gallery-modal-item submit">
                            <button class="btn btn--base w-100 publish-gallery-btn" type="submit">@lang('Set Goal')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";

        $(document).ready(function() {

            $(".goalBtn").on('click', function() {
                var modal = $('.setGoalModal').modal();
                var data = $(this).data();
                var resource = data.resource;
                modal.find('.modal-title').text('Set Goal')
                if (resource) {
                    modal.find('.modal-title').text('Update Goal')
                    $('input[name="id"]').val(resource.id);
                    $('input[name="title"]').val(resource.title);
                    $('textarea[name="thanks_message"]').val(resource.thanks_message);
                    $('textarea[name="description"]').val(resource.description);
                    $('input[name="target_amount"]').val(Number(resource.target_amount));
                    $('input[name="starting_amount"]').val(Number(resource.starting_amount));
                    var isPublic = parseInt(resource.view_publicly) == 1;
                    $('#view_publicly').prop('checked', isPublic);
                }
                modal.modal('show');
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

        });
    </script>
@endpush
