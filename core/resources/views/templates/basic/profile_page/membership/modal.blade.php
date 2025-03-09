<div class="modal fade  membershipModal" id="membershipModal">
    <div class="modal-dialog modal-dialog-centered preview-modal">
        <div class="modal-content">
            <button class="btn-close close-preview flex-center" data-bs-dismiss="modal" type="button" aria-label="Close"> <i class="fas fa-times"></i></button>
            <div class="modal-body text-center">
                <div class="modal-body__img">
                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}" alt="">
                </div>
                <h4 class="text-center mb-0">@lang('Become a Member')</h4>
                <div class="modal-body__content">
                    <div class="buy-coffee-card">
                        <ul class="nav nav-tabs custom--tab" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link m-ship fs-14" id="monthlyPay" data-duration_type="{{ Status::MONTHLY_MEMBERSHIP }}" data-bs-toggle="tab" data-bs-target="#monthlyPay-pane" type="button" role="tab" aria-controls="monthlyPay-pane" aria-selected="true"> <span class="monthlyPrice"></span>&nbsp;@lang('Per Month')</button>
                            </li>
                            @if (@$user->membershipSetting?->accept_annual_membership)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link m-ship fs-14 active" id="yearlyPlay-tab" data-duration_type="{{ Status::YEARLY_MEMBERSHIP }}" data-bs-toggle="tab" data-bs-target="#yearlyPlay-tab-pane" type="button" role="tab" aria-controls="yearlyPlay-tab-pane" aria-selected="false"> <span class="yearlyPrice"></span>&nbsp;@lang('Per Year')</button>
                                </li>
                            @endif
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade @if (!@$user->membershipSetting?->accept_annual_membership) show active @endif" id="monthlyPay-pane" role="tabpanel" aria-labelledby="monthlyPay-pane" tabindex="0">
                                @include($activeTemplate . 'profile_page.membership.panel',['id' => 'monthly'])
                            </div>
                            @if (@$user->membershipSetting?->accept_annual_membership)
                                <div class="tab-pane fade show active" id="yearlyPlay-tab-pane" role="tabpanel" aria-labelledby="yearlyPlay-tab-pane" tabindex="0">
                                    @include($activeTemplate . 'profile_page.membership.panel',['id' => 'yearly'])
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')
    <style>
        .nav-item .nav-link.m-ship {
            justify-content: center;
        }
    </style>
@endpush

@push('script')
    <script>
        'use stric';
        $(document).ready(function() {
            $(".joinMembershipBtn").on('click', function() {
                var modal = $('#membershipModal');
                var level = $(this).data('levels');
                var cur = `{{ $general->cur_sym }}`;
                var mAmount = parseFloat(level.monthly_price).toFixed();
                var yAmount = parseFloat(level.yearly_price).toFixed();

                modal.find('.monthlyPrice').text(cur + mAmount);
                modal.find('.yearlyPrice').text(cur + yAmount);

                $('#monthlyPay').attr('data-donation_amount', mAmount);
                $('#yearlyPlay-tab').attr('data-donation_amount', yAmount);

                if (`{{ @$user->membershipSetting?->accept_annual_membership }}`) {
                    modal.find('[name="amount"]').val(yAmount);
                    modal.find('[name="duration_type"]').val(12);
                } else {
                    modal.find('[name="amount"]').val(mAmount);
                    modal.find('[name="duration_type"]').val(1);
                }
                modal.find('[name="membership_level_id"]').val(level.id);
                modal.find('.package-name').text(level.name);
                modal.find('.package-description').text(level.description);
                modal.find('.one').text(level.rewards.one);
                modal.find('.two').text(level.rewards.two);
                modal.find('.three').text(level.rewards.three);
                modal.modal('show');
            })

            $('#membershipModal').find('.nav-tabs button').on('click', function() {
                var donationType = $(this).data('duration_type');
                var donationAmount = $(this).data('donation_amount');
                $('#membershipModal').find('[name="duration_type"]').val(donationType);
                $('#membershipModal').find('[name="amount"]').val(donationAmount);
            });
        });
    </script>
@endpush
