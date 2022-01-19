import helper from "./helper";

let publicJs = {
    init() {

        let swiper = new Swiper(".mySwiper", {
            spaceBetween: 0,
            speed: 2000,
            effect: "fade",
            centeredSlides: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
                disabledClass: "disabled_swiper_button",
            },
        });

        swiper.on("slideChange", () => {
            const slideIndex = swiper.activeIndex;
            const previous = parseInt(slideIndex) - 1;
            const lastIndex = parseInt(swiper.slides.length) - 1;

            let i, j;

            if (slideIndex > 0) {
                setTimeout(() => {
                    for (i = 0; i <= previous; i++) {
                        $(`#slideIndex${i}`).hide();
                    }

                    for (j = slideIndex + 1; j <= lastIndex; j++) {
                        $(`#slideIndex${j}`).hide();
                    }

                    $(`#slideIndex${slideIndex}`).show();
                }, 300);
            } else {
                $(`#slideIndex0`).show();
                for (i = 1; i <= lastIndex; i++) {
                    $(`#slideIndex${i}`).hide();
                }
            }
        });

        let testimonialSwiper = new Swiper(".testimonial-swiper", {
            spaceBetween: 0,
            speed: 600,
            effect: "flip",
            flipEffect: {
                slideShadows: false,
            },
            centeredSlides: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: true,
            },
        });
        $(".prev-arrow").on('click', e => {
            console.log("prev");
            testimonialSwiper.slidePrev();
        });
        $(".next-arrow").on('click', e => {
            console.log("next");
            testimonialSwiper.slideNext();
        });

        this.initHandlers();
    },

    initHandlers() {
        $("form").on("submit", (e) => {
            self = $(e.target);

            self.children()
                .find('button[type="submit"]')
                .prop("disabled", true);
        });
    },
};

$(() => {
    publicJs.init();
});
