$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

if (isLoggedIn) {
    $(document).on('click', '.like-btn', function () {
        let $this = $(this);
        let gallery_id = $(this).data('gallery_id');
        let hasCount = $(this).siblings('.count');

        if (hasCount.length > 0) {
            var likeCountElement = $(this).siblings('.count');
        } else {
            var likeCountElement = $this.closest('li').find('.gallery__like-num.count');
        }
        $.ajax({
            url: likeURL,
            type: 'POST',
            data: {
                gallery_id: gallery_id,
            },
            success: function (response) {
                if (response.success) {
                    if (response.is_like) {
                        $this.find('i').removeClass('far fa-heart').addClass('fas fa-heart');
                    } else {
                        $this.find('i').removeClass('fas fa-heart').addClass('far fa-heart');
                    }
                    likeCountElement.text(response.count_like);
                }
            }
        });
    });
}