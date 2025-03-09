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
                                    <th>@lang('Payment Date')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Supporter')</th>
                                    <th>@lang('Amount')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($donations as $donation)
                                    <tr>
                                        <td>
                                            {{ showDateTime($donation->created_at) }}
                                            <br>
                                            {{ diffForHumans($donation->created_at) }}
                                        </td>
                                        <td>
                                            <span class="fw-bold"> {{ @$donation->user->fullname }}</span>
                                            <br>
                                            <a href="{{ route('admin.users.detail', $donation->user_id) }}"> {{ @$donation->user->username }}</a>
                                        </td>
                                        <td>
                                            @if ($donation->supporter_id)
                                                <span class="fw-bold"> {{ @$donation->supporter->fullname }}</span>
                                                <br>
                                                <a href="{{ route('admin.users.detail', $donation->supporter_id) }}">
                                                    {{ @$donation->supporter->username }}</a>
                                            @else
                                                @lang('Anonymous User')
                                            @endif
                                        </td>

                                        <td>
                                            <span class="text--primary"> {{ $general->cur_sym . showAmount($donation->amount) }}</span>
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
                @if ($donations->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($donations) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-search-form dateSearch='yes' />
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function() {
            $('.select-filter').on('change', function() {
                let form = $('.filter');
                form.submit();
            });
        });
    </script>
@endpush
