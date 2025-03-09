@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Membership Date')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Member')</th>
                                    <th>@lang('Amount') | @lang('Level')</th>
                                    <th>@lang('Type') | @lang('Status')</th>
                                    <th>@lang('Next Payment Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($memberships as $membership)
                                    <tr>
                                        <td>{{ $memberships->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div>
                                                {{ showDateTime($membership->created_at) }}
                                                <br>
                                                {{ diffForHumans($membership->created_at) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-bold"> {{ @$membership->user->fullname }}</span>
                                                <br>
                                                <a href="{{ route('admin.users.detail', $membership->user_id) }}"> {{ @$membership->user->username }}</a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold"> {{ @$membership->member->fullname }}</span>
                                            <br>
                                            <a href="{{ route('admin.users.detail', $membership->member_id) }}"> {{ @$membership->member->username }}</a>

                                        </td>
                                        <td>
                                            <div>
                                                {{ $general->cur_sym . showAmount($membership->amount) }}
                                                <br>
                                                {{ __($membership->level->name) }}
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column gap-2">
                                                @php echo $membership->typeBadge; @endphp
                                                <br>
                                                @php echo $membership->StatusBadge; @endphp
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ showDateTime($membership->next_date) }}
                                                <br>
                                                {{ diffForHumans($membership->next_date) }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($memberships->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($memberships) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-search-form dateSearch='yes' />
@endpush
