<div class="modal fade confirm-modal custom--modal" id="confirmationAlert">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                <div class="modal-body text-center">
                    <h4 class="modal-title mb-3" id="editPageTitle">@lang('Confirmation Alert!')</h4>
                    <p class="modal-desc  question mb-3">
                    </p>
                    <div class="d-flex flex-wrap gap-3 flex-column ">
                        <button class="btn btn--base w-100 con-btn" type="submit">@lang('Yes')</button>
                        <button class="btn btn-outline--light w-100" data-bs-dismiss="modal" type="button">@lang('No')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.confirmationAltBtn', function() {
                var modal = $('#confirmationAlert');
                let data = $(this).data();
                modal.find('.question').text(`${data.question}`);
                modal.find('form').attr('action', `${data.action}`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
