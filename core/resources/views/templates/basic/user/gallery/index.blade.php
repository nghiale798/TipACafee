@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="posts-page">
        <h2 class="page-title">{{ __($pageTitle) }}</h2>
        <div class="post-card-wrapper">
            <div class="row gy-4">
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
                            @forelse ($publishGallary as $gallery)
                                @include($activeTemplate . 'partials.gallery')
                            @empty
                                <div class="tab-no-content text-center pt-4 pb-2">
                                    <span class="tab-no-content__icon">
                                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/mike.png') }}" alt="">
                                    </span>
                                    <h4 class="tab-no-content__title">
                                        @lang('Manage your published gallery image')
                                    </h4>
                                    <p class="tab-no-content__desc">
                                        @lang('Gallery image public view or make them exclusive to your supporters or members. Creators who post exclusives regularly tend to earn more support.')
                                    </p>
                                </div>
                            @endforelse

                            <div class="mt-3">
                                {{ paginateLinks($publishGallary) }}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="draft-post" role="tabpanel" aria-labelledby="draft-post-tab" tabindex="0">

                            @forelse ($draftGallery as $gallery)
                                @include($activeTemplate . 'partials.gallery')
                            @empty
                                <div class="tab-no-content text-center pt-4 pb-2">
                                    <span class="tab-no-content__icon">
                                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/mike.png') }}" alt="">
                                    </span>
                                    <h4 class="tab-no-content__title">@lang('Manage your drafted gallery image')</h4>
                                    <p class="tab-no-content__desc">
                                        @lang('This is a place for all your unfinished gallery image. If you wish to save the progress on your gallery image and finish it later, save it as a draft.')
                                    </p>
                                </div>
                            @endforelse

                            @if ($draftGallery->hasPages())
                                <div class="mt-3">
                                    {{ paginateLinks($draftGallery) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include($activeTemplate . 'user.gallery.add_modal')
    @include($activeTemplate . 'user.gallery.edit_modal')

    <div class="modal fade" id="statuConfirmationModal">
        <div class="modal-dialog modal-dialog-centered preview-modal">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body text-center">
                        <h4 class="modal-title">@lang('Status Changed Confirmation!')</h4>
                        <p class="modal-desc mt-3"></p>
                        <button class="btn btn--base w-100 mt-3" type="submit">@lang('Yes')</button>
                        <button class="btn btn-outline--light w-100 mt-3" data-bs-dismiss="modal" type="button">@lang('No')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-alert />
@endsection
