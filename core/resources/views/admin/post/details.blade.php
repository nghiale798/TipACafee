@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#photo-details" type="button">@lang('Post Details')</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#comment" type="button">@lang('Comments')</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="photo-details">
                            <div class="mt-4">
                                <div class="mb-4">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <span class="text--muted">@lang('Owner')</span>
                                            <h6 class="text--primary">{{ @$post->user->fullname }} <br>
                                                <a href="{{ route('admin.users.detail', @$post->user_id) }}"> {{ @$post->user->username }}</a>
                                            </h6>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="text--muted">@lang('Title')</span>
                                            <h6>{{ __(@$post->title) }}</h6>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="text--muted">@lang('Total Likes')</span>
                                            <h6 class="badge badge--warning">{{ @$post->likes_count }}</h6>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="text--muted">@lang('Total Comments')</span>
                                            <h6 class="badge badge--primary">{{ @$post->comments_count }}</h6>
                                        </li>
                                    </ul>
                                </div>
                                <h5>@lang('Description')</h5>
                                <hr>
                                <div class="mb-4">
                                    @php  echo @$post->content; @endphp
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="comment">
                            <div class="mt-4">
                                @if ($post->comments->count())
                                    <div class="table-responsive--md table-responsive">
                                        <table class=" table align-items-center table--light">
                                            <thead>
                                                <tr>
                                                    <th>@lang('Fullname') | @lang('Email')</th>
                                                    <th>@lang('Comment')</th>
                                                    <th>@lang('Created At')</th>
                                                    <th>@lang('Status')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($post->comments as $comment)
                                                    <tr>
                                                        <td>
                                                            <span class="fw-bold">{{ __($comment->user->fullname) }}</span>
                                                            <br>
                                                            <a href="{{ route('admin.users.detail', @$comment->user->id) }}"> {{ @$comment->user->username }}</a>
                                                        </td>
                                                        <td> {{ strLimit($comment->comment, 30) }}</td>
                                                        <td>
                                                            {{ showDateTime($comment->created_at) }}
                                                            <span class="d-block">{{ diffForHumans($comment->created_at) }}</span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                echo $comment->statusBadge;
                                                            @endphp
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if ($post->comments->count() > 10)
                                            <div class="d-flex justify-content-end">
                                                <a class="btn btn--primary me-md-2" type="button" href="{{ route('admin.post.comment', $post->id) }}?photo_id={{ $post->id }}">
                                                    <i class="las la-comment-dots"></i> @lang('See More..')</a>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-center border-1"> @lang('No comment yet')</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.post.index') }}" />
@endpush

@push('style')
    <style>
        .list-group-item {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding: .8rem 0;
            border: 1px solid #f1f1f1;
        }

        .accordion-button:not(.collapsed) {
            box-shadow: none !important;
        }

        .gallery-card {
            position: relative;
        }

        .gallery-card:hover .view-btn {
            opacity: 1;
            visibility: visible;
        }

        .gallery-card .view-btn {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.364);
            color: #f0e9e9;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            font-size: 42px;
            opacity: 0;
            visibility: none;
            -webkit-transition: all 0.3s;
            -o-transition: all 0.3s;
            transition: all 0.3s;
        }

        .thumb i {
            font-size: 22px;
        }
    </style>
@endpush
