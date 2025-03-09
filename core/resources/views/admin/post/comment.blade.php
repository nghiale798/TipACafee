@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class=" table align-items-center table--light">
                            <thead>
                                <tr>
                                    <th>@lang('Name') | @lang('Email')</th>
                                    <th>@lang('Comment')</th>
                                    <th>@lang('Created At')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ __($comment->user->fullname) }}</span>
                                            <br>
                                            <a href="{{ route('admin.users.detail', @$comment->user->id) }}"> {{ @$comment->user->username }}</a>
                                        </td>
                                        <td>{{ strLimit($comment->comment, 30) }}</td>
                                        <td>
                                            {{ showDateTime($comment->created_at) }}
                                            <span class="d-block">{{ diffForHumans($comment->created_at) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                echo $comment->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary detailBtn" data-resourse="{{ $comment }}">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </button>
                                                @if ($comment->status == Status::ENABLE)
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.gallery.comment.status', $comment->id) }}" data-question="@lang('Are you sure to disabled this comment?')" type="button">
                                                        <i class="la la-eye-slash"></i> @lang('Disabled')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--success  confirmationBtn" data-action="{{ route('admin.gallery.comment.status', $comment->id) }}" data-question="@lang('Are you sure to enable this comment?')" type="button">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @endif
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

                @if ($comments->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($comments) @endphp
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    {{-- DETAILS MODAL --}}
    <div class="modal fade" id="detailModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Camapign Comment')</h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group-flush list-group">
                        <li class="list-group-item align-items-center fw-bold">
                            <p class="comment text-end"></p>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button class="btn btn--dark btn-sm" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <x-search-form />
    <x-back route="{{ route('admin.post.details', $id) }}" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var resourse = $(this).data('resourse');
                $('.comment').text(resourse.comment);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
