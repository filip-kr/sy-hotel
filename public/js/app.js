// Cookie notification
$('.cookieBtn').click(function () {
    $('.cookie-container').removeClass('active');
    localStorage.setItem('cookieBannerDisplayed', 'true');
});

setTimeout(() => {
    if (!localStorage.getItem('cookieBannerDisplayed')) {
        $('.cookie-container').addClass('active');
    }
}, 2000);