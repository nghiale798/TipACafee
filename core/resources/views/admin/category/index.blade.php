@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Is Featured')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>
                                            {{ __($category->name) }}
                                        </td>
                                        <td>
                                            @if ($category->is_featured == Status::CATEGORY_FEATURED)
                                                <span class="badge badge--primary"> @lang('Yes')</span>
                                            @else
                                                <span class="badge badge--warning"> @lang('No')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php echo $category->statusBadge; @endphp
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                                    <button class="btn btn-outline--primary editBtn cuModalBtn btn-sm"
                                                        data-modal_title="@lang('Update Category')" data-resource="{{ $category }}">
                                                        <i class="las la-pen"></i>@lang('Edit')
                                                    </button>
                                                    <button class="btn btn-outline--info btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="las la-ellipsis-v"></i> @lang('More')
                                                    </button>
                                                    <ul class="dropdown-menu px-2">
                                                        <li>
                                                            @if ($category->status == Status::ENABLE)
                                                                <button class="dropdown-item confirmationBtn" data-question="@lang('Are you sure to disable this category?')"
                                                                    data-action="{{ route('admin.category.status', $category->id) }}" type="button">
                                                                    <i class="la la-eye-slash"></i> @lang('Disable')
                                                                </button>
                                                            @else
                                                                <button class="dropdown-item confirmationBtn" data-question="@lang('Are you sure to enable this category?')"
                                                                    data-action="{{ route('admin.category.status', $category->id) }}" type="button">
                                                                    <i class="la la-eye"></i> @lang('Enable')
                                                                </button>
                                                            @endif
                                                        </li>
                                                        <li>
                                                            @if ($category->is_featured == Status::CATEGORY_FEATURED)
                                                                <button class="dropdown-item confirmationBtn" data-question="@lang('Are you sure to unfeature this category?')"
                                                                    data-action="{{ route('admin.category.feature', $category->id) }}" type="button">
                                                                    <i class="las la-star-half-alt"></i> @lang('Unfeature')
                                                                </button>
                                                            @else
                                                                <button class="dropdown-item confirmationBtn" data-question="@lang('Are you sure to feature this category?')"
                                                                    data-action="{{ route('admin.category.feature', $category->id) }}" type="button">
                                                                    <i class="las la-star"></i> @lang('Feature')
                                                                </button>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>

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
                @if ($categories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($categories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="cuModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input class="form-control" name="name" type="text" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form />
    <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Add Category')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('style')
    <style>
        .category-more-btn {
            color: #707070;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            $('.table-responsive').on('click', 'button[data-bs-toggle="dropdown"]', function(e) {
                const {
                    top,
                    left
                } = $(this).next(".dropdown-menu")[0].getBoundingClientRect();
                $(this).next(".dropdown-menu").css({
                    position: "fixed",
                    inset: "unset",
                    transform: "unset",
                    top: top + "px",
                    left: left + "px",
                });
            });
            if ($('.table-responsive').length) {
                $(window).on('scroll', function(e) {
                    $('.table-responsive .dropdown-menu').removeClass('show');
                    $('.table-responsive button[data-bs-toggle="dropdown"]').removeClass('show');
                });
            }
        })(jQuery);
    </script>
@endpush
