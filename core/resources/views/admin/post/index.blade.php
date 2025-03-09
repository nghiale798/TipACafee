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
                                    <th>@lang('Author')</th>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Visibility')</th>
                                    <th>@lang('Posted At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $post)
                                    <tr>
                                        <td>
                                            <span class="fw-bold"> {{ @$post->user->fullname }}</span>
                                            <br>
                                            <a href="{{ route('admin.users.detail', $post->user_id) }}"> {{ @$post->user->username }}</a>
                                        </td>
                                        <td>
                                            <a class="fw-bold" href="{{ route('post.view', [$post->user->profile_link, $post->slug]) }}" target="_blank"> {{ strLimit($post->title, 30) }} </a>
                                        </td>
                                        <td>
                                            @if ($post->status == Status::VISIBLE_PUBLIC)
                                                <span class="badge badge--primary">@lang('Published')</span>
                                            @else
                                                <span class="badge badge--dark">@lang('Draft')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($post->visible == Status::VISIBLE_PUBLIC)
                                                <span class="badge badge--primary">@lang('Public')</span>
                                            @elseif ($post->visible == Status::VISIBLE_SUPPORTER)
                                                <span class="badge badge--success">@lang('Supporter')</span>
                                            @elseif ($post->visible == Status::VISIBLE_MEMBER)
                                                <span class="badge badge--warning">@lang('Member')</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            {{ showDateTime($post->created_at) }}
                                            <br>
                                            {{ diffForHumans($post->created_at) }}
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a class="btn btn-sm btn-outline--primary ms-1" href="{{ route('admin.post.details', $post->id) }}">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>

                                                <a class="btn btn-sm btn-outline--info ms-1" href="{{ route('admin.post.comment', $post->id) }}">
                                                    <i class="las la-comment"></i> @lang('Comments')
                                                    ({{ $post->comments_count }})
                                                </a>
                                            </div>
                                        </td>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($posts->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($posts) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form dateSearch='yes' visibilityFilter="yes" StatusFilter="yes" />
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
