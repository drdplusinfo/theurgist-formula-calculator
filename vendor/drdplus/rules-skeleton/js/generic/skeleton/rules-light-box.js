(function ($) {
    $(document).on('click', '.lightbox', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
})(jQuery);