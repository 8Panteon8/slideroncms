import {
  Swiper,
  EffectFade,
  Navigation,
  Pagination,
  Scrollbar,
  Controller,
  Parallax,
  Mousewheel,
} from "swiper";
Swiper.use([
  EffectFade,
  Navigation,
  Pagination,
  Scrollbar,
  Controller,
  Parallax,
  Mousewheel,
]);

import { gsap, Power2 } from "gsap";

import MicroModal from "micromodal";

// Import October CMS System Framework (requires jQuery)
import "~/modules/system/assets/js/framework.js";
import "~/modules/system/assets/js/framework.extras.js";

// Import October CMS Plugins Examples
// import '~/themes/mytheme/assets/vendor/lazyload/lazyload.js'
// import '~/plugins/nms/plugin/assets/js/plugin.js'

document.addEventListener("DOMContentLoaded", () => {
  let popupAlert = document.querySelector(".popup-form-alert");

  MicroModal.init({
    openTrigger: "data-micromodal-open",
    closeTrigger: "data-micromodal-close",
    disableScroll: true,
    disableFocus: true,
    awaitOpenAnimation: true,
    awaitCloseAnimation: true,
    onClose: function () {
      popupAlert.innerHTML = "";
    },
  });

  const swiperText = new Swiper(".slider-text", {
    loop: false,
    effect: "slide",
    speed: 2400,
    mousewheel: {
      invert: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    scrollbar: {
      el: ".swiper-scrollbar",
      draggable: true,
    },
  });

  const swiperIMG = new Swiper(".slider-img", {
    loop: false,
    speed: 2400,
    parallax: true,
    pagination: {
      el: ".slider-pagination-count .total",
      type: "custom",
      renderCustom: function (swiper, current, total) {
        return `0${total}`;
      },
    },
  });

  swiperIMG.controller.control = swiperText;
  swiperText.controller.control = swiperIMG;

  // slideChange

  let curnum = document.querySelector(".current"),
    pagcur = document.querySelector(".slider-pagination-current"),
    gear = document.querySelector(".slider-gear");

  swiperText.on("slideChange", function () {
    let ind = swiperText.realIndex + 1;
    gsap.to(curnum, 0.2, {
      force3D: true,
      y: -10,
      opacity: 0,
      ease: Power2.easeOut,
      onComplete: function () {
        gsap.to(curnum, 0.1, {
          force3D: true,
          y: 10,
        });
        curnum.innerHTML = `0${ind}`;
        pagcur.innerHTML = `0${ind}<span class="slider-pagination-current__dot">.</span>`;
      },
    });
    gsap.to(curnum, 0.2, {
      force3D: true,
      y: 0,
      delay: 0.3,
      opacity: 1,
      ease: Power2.easeOut,
    });
  });

  swiperText.on("slideNextTransitionStart", function () {
    gsap.to(gear, 2.8, {
      rotation: "+=45",
      ease: Power2.easeOut,
    });
  });

  swiperText.on("slidePrevTransitionStart", function () {
    gsap.to(gear, 2.8, {
      rotation: "-=45",
      ease: Power2.easeOut,
    });
  });

  // Cursor

  const body = document.querySelector("body");

  const cursor = document.getElementById("cursor"),
    links = document.getElementsByTagName("a");

  let mouseX = 0,
    mouseY = 0,
    posX = 0,
    posY = 0;

  function mouseCoords(e) {
    mouseX = e.pageX;
    mouseY = e.pageY;
  }

  gsap.to({}, 0.01, {
    repeat: -1,

    onRepeat: () => {
      posX += (mouseX - posX) / 6;
      posY += (mouseY - posY) / 6;

      gsap.set(cursor, {
        css: {
          left: posX,
          top: posY,
        },
      });
    },
  });

  for (let i = 0; i < links.length; i++) {
    links[i].addEventListener("mouseover", () => {
      cursor.classList.add("active");
    });

    links[i].addEventListener("mouseout", () => {
      cursor.classList.remove("active");
    });
  }

  body.addEventListener("mouseout", () => {
    cursor.classList.add("hidden");
  });

  body.addEventListener("mousemove", (e) => {
    mouseCoords(e);
    cursor.classList.remove("hidden");
  });
});
