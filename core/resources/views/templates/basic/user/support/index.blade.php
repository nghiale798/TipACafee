@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="search-header">
                        <form action="" method="">
                            <div class="d-flex justify-content-end">
                                <div class="input-group">
                                    <input class="form-control form--control" name="search" type="text" value="{{ request()->search }}" placeholder="@lang('Search here')">
                                    <button class="input-group-text btn">
                                        <i class="las la-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <a class="btn btn--sm btn--base ms-auto" href="{{ route('ticket.open') }}"> <i class="fa fa-plus"></i> @lang('New Ticket')</a>
                    </div>

                    @if (blank($supports))
                        @include($activeTemplate . 'partials.empty', ['message' => 'No support ticket found!'])
                    @else
                        <table class="table py-4 table table--responsive--sm">
                            <thead>
                                <tr>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Priority')</th>
                                    <th>@lang('Last Reply')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($supports as $support)
                                    <tr>
                                        <td> <a class="fw-bold" href="{{ route('ticket.view', $support->ticket) }}"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                                        <td>
                                            @php echo $support->statusBadge; @endphp
                                        </td>
                                        <td>
                                            @if ($support->priority == Status::PRIORITY_LOW)
                                                <span class="badge badge--dark">@lang('Low')</span>
                                            @elseif($support->priority == Status::PRIORITY_MEDIUM)
                                                <span class="badge  badge--warning">@lang('Medium')</span>
                                            @elseif($support->priority == Status::PRIORITY_HIGH)
                                                <span class="badge badge--danger">@lang('High')</span>
                                            @endif
                                        </td>
                                        <td>{{ diffForHumans($support->last_reply) }} </td>
                                        <td>
                                            <div class="d-flex flex-wrap align-items-center justify-content-end">
                                                <a class="btn btn--base btn--sm" href="{{ route('ticket.view', $support->ticket) }}">
                                                    <i class="las la-desktop"></i> @lang('View')
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($supports->hasPages())
                            <div class="mt-3">
                                {{ paginateLinks($supports) }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
