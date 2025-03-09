$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

if (isLoggedIn) {
    $(document).on('click', '.like-btn', function () {
        let $this = $(this);
        let post_id = $(this).data('post_id');
        let likeCountElement = $(this).siblings('.count');
        $.ajax({
            url: likeURL,
            type: 'POST',
            data: {
                post_id: post_id,
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