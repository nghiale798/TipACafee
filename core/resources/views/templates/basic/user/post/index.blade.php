@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="posts-page">
        <h2 class="page-title">{{ __($pageTitle) }}</h2>
        <div class="post-card-wrapper">
            <div class="row gy-3">
                <div class="col-sm-6 col-msm-6">
                    <a class="post-card flex-align" href="{{ route('user.post.create') }}">
                        <span class="post-card__icon">
                            <i class="far fa-address-card"></i>
                        </span>
                        <span class="post-card__title">@lang('Create Post')</spana>
                    </a>
                </div>
                <div class="col-sm-6 col-msm-6">
                    <div class="post-card flex-align cursor-pointer" id="gallery-img-btn" data-bs-target="#add-img-modal" data-bs-toggle="modal">
                        <span class="post-card__icon">
                            <i class="far fa-image"></i>
                        </span>
                        <button type="button">
                            <span class="create-page-text">@lang('Add Photo')</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="post-status pt-44">
            <div class="row">
                <div class="col-12">
                    <ul class="nav custom--tab nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="publish-post-tab" data-bs-toggle="pill" data-bs-target="#publish-post" type="button" role="tab" aria-controls="publish-post" aria-selected="true">@lang('Publish')</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="draft-post-tab" data-bs-toggle="pill" data-bs-target="#draft-post" type="button" role="tab" aria-controls="draft-post" aria-selected="false">@lang('Draft')</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="publish-post" role="tabpanel" aria-labelledby="publish-post-tab" tabindex="0">
                            @forelse ($publishPosts as $post)
                                @include($activeTemplate . 'partials.post')
                            @empty
                                <div class="tab-no-content text-center pt-4 pb-2">
                                    <span class="tab-no-content__icon">
                                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/mike.png') }}" alt="post">
                                    </span>
                                    <h4 class="tab-no-content__title">
                                        @lang('Manage your published post')
                                    </h4>
                                    <p class="tab-no-content__desc">
                                        @lang('Post public posts or make them exclusive to your supporters or members. Creators who post exclusives regularly tend to earn more support.')
                                    </p>
                                </div>
                            @endforelse
                            @if ($publishPosts->count() > 0 && $publishPosts->hasPages())
                                <div class="mt-3">
                                    {{ paginateLinks($publishPosts) }}
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="draft-post" role="tabpanel" aria-labelledby="draft-post-tab" tabindex="0">
                            @forelse ($draftPosts as $post)
                                @include($activeTemplate . 'partials.post')
                            @empty
                                <div class="tab-no-content text-center pt-4 pb-2">
                                    <span class="tab-no-content__icon">
                                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/mike.png') }}" alt="post">
                                    </span>
                                    <h4 class="tab-no-content__title">@lang('Manage your drafted post')</h4>
                                    <p class="tab-no-content__desc">
                                        @lang('This is a place for all your unfinished posts. If you wish to save the progress on your post and finish it later, save it as a draft.')
                                    </p>
                                </div>
                            @endforelse
                            @if ($draftPosts->count() > 0 && $draftPosts->hasPages())
                                <div class="mt-3">
                                    {{ paginateLinks($draftPosts) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include($activeTemplate . 'user.gallery.add_modal')

    <x-confirmation-alert />
@endsection
