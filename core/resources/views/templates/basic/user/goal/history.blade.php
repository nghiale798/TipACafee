@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="d-flex gap-2 flex-wrap justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0">{{ __($pageTitle) }}</h2>
        <a class="btn btn--base btn--sm" href="{{ route('user.goal.index') }}"> <i class="las la-undo"></i> @lang('Back')</a>
    </div>
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            <div class="row">
                <div class="col-12">
                    <form action="" class="filter">
                        <div class="form-group">
                            <label>@lang('Filter By Goal')</label>
                            <select class="form--select form--control goal_id" name="goal_id">
                                <option value="">@lang('All')</option>
                                @foreach (@$goals as $goal)
                                    <option value="{{ $goal->id }}" @selected(request()->goal_id == $goal->id)>{{ __(strLimit($goal->title, 30)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                </div>
                <div class="project-area py-4">
                    @if (blank($goalLogs))
                        @include($activeTemplate . 'partials.empty', ['message' => 'No goal log found!'])
                    @else
                        <div class="verticalResponsiveTable">
                            <table class="table table table--responsive--sm">
                                <thead>
                                    <tr>
                                        <th>@lang('Goal Title')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Gifted At')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($goalLogs as $item)
                                        <tr>
                                            <td>
                                                {{ strLimit(__($item->goal->title), 40) }}
                                            </td>
                                            <td>
                                                {{ $general->cur_sym }}{{ showAmount($item->amount) }}
                                            </td>
                                            <td>
                                                    {{ showDateTime($item->created_at) }}<br>{{ diffForHumans($item->created_at) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($goalLogs->hasPages())
                            <div class="mt-3">
                                {{ paginateLinks($goalLogs) }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.goal_id').on('change', function() {
                let form = $('.filter');
                form.submit();
            });
        });
    </script>
@endpush
