// Initialize Swiper (Book Slider)
var swiper = new Swiper(".mySwiper", {
    slidesPerView: 3,
    spaceBetween: 30,
    loop: true,
    autoplay: {
        delay: 2000,
        disableOnInteraction: false,
    },
    breakpoints: {
        768: {
            slidesPerView: 2,
        },
        480: {
            slidesPerView: 1,
        }
    }
});
