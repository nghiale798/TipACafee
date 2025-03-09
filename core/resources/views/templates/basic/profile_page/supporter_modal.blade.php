<div class="modal fade" id="supportModal">
    <div class="modal-dialog modal-dialog-centered preview-modal">
        <div class="modal-content">
            <button class="btn-close close-preview flex-center" data-bs-dismiss="modal" type="button" aria-label="Close"> <i class="fas fa-times"></i></button>
            <div class="modal-body text-center">
                <div class="buy-coffee-card">
                    <div class="modal-body__img">
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}" alt="">
                    </div>
                    <h3 class="buy-coffee-card__title">@lang('Buy') <span class="name">{{ __($user->fullname) }}</span> @lang('a') {{ __(@$user->donate_emoji_name) }}</h3>
                    <div class="buy-coffee-card__list">
                        <span class="coffee-cup text--base">{{ @$user->donate_emoji }}</span>
                        <span class="times"><i class="la la-times"></i></span>
                        <ul class="coffee-no-of-cups">
                            <li class="number-of-cup flex-center" data-donation_amount="{{ $coffee * 1 }}">1</li>
                            <li class="number-of-cup flex-center" data-donation_amount="{{ $coffee * 2 }}">2</li>
                            <li class="number-of-cup flex-center" data-donation_amount="{{ $coffee * 3 }}">3</li>
                            <li class="number-of-cup flex-center" data-donation_amount="{{ $coffee * 5 }}">5</li>
                            <li class="custom-cups"><input class="form--control" name="quantity" type="number" placeholder="20" /></li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <input class="form--control" name="donor_identity" type="text" value="{{ old('donor_identity') }}" placeholder="@lang('Name or twitter (optional)')">
                    </div>
                    <div class="form-group">
                        <textarea class="form--control mb-3" id="message" name="message" placeholder="@lang('Say something (optional)')">{{ old('message') }}</textarea>
                        <label class="privet-message form--check" for="privet-message">
                            <span class="custom--check">
                                <input class="form-check-input" id="privet-message" name="is_message_private" type="checkbox">
                            </span>
                            <span class="form-check-label">@lang('Make this message private?')</span>
                        </label>
                    </div>
                    @if (@$user->donationSetting->cause_percent > 0)
                        <div class="donation-indicate flex-align gap-2 mb-4">
                            <span class="icon"><img alt="" src="{{ getImage($activeTemplateTrue . 'images/icons/support.png') }}"></span>
                            {{ getAmount($user->donationSetting->cause_percent) }}% @lang('of all proceeds go to') {{ __($user->donationSetting->institute) }}
                        </div>
                    @endif
                    <form id="payosForm" action="{{ route('payment.payos') }}" method="POST">
                        @csrf
                        <input type="hidden" name="amount" value="{{ $coffee * 1 }}">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="donor_identity" value="">
                        <input type="hidden" name="message" value="">
                        <button type="submit" class="btn btn--base w-100">
                            @lang('Support') <span class="donation-amount">{{ $general->cur_sym . getAmount(@$coffee) }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Cập nhật số tiền khi chọn số lượng cà phê
    const coffeeCups = document.querySelectorAll(".number-of-cup");
    const customCupsInput = document.querySelector(".custom-cups input");
    const amountInput = document.querySelector("#payosForm input[name='amount']");
    const donationAmountSpan = document.querySelector(".donation-amount");

    coffeeCups.forEach(cup => {
        cup.addEventListener("click", function () {
            const amount = this.getAttribute("data-donation_amount");
            amountInput.value = amount;
            updateDonationAmount(amount);
        });
    });

    customCupsInput.addEventListener("input", function () {
        const quantity = parseInt(this.value) || 0;
        const amount = quantity * {{ $coffee }};
        amountInput.value = amount;
        updateDonationAmount(amount);
    });

    function updateDonationAmount(amount) {
        if (donationAmountSpan) {
            donationAmountSpan.textContent = "{{ $general->cur_sym }}" + amount.toLocaleString();
        }
    }

    // Xử lý gửi form đến PayOS
    const payosForm = document.getElementById("payosForm");
    if (payosForm) {
        payosForm.addEventListener("submit", function (event) {
            event.preventDefault();

            // Cập nhật giá trị của donor_identity và message
            const donorIdentity = document.querySelector("[name='donor_identity']").value;
            const message = document.querySelector("[name='message']").value;
            payosForm.querySelector("[name='donor_identity']").value = donorIdentity;
            payosForm.querySelector("[name='message']").value = message;

            // Gửi form bằng AJAX
            fetch(payosForm.action, {
                method: "POST",
                body: new FormData(payosForm),
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json",
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.pay_url) {
                    window.location.href = data.pay_url; // Chuyển hướng đến trang thanh toán PayOS
                } else {
                    alert("Có lỗi xảy ra, vui lòng thử lại!");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    }
});
</script>