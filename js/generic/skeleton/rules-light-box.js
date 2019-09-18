document.addEventListener('DOMContentLoaded', function () {
    if (document.body.getElementsByClassName('lightbox').length === 0) {
        return
    }
    (function ($) {
        $(document).on('click', '.lightbox', function (event) {
            event.preventDefault()
            $(this).ekkoLightbox()
        })
    })(jQuery)
})