(function ($) {
  ("use strict");

  // ============== Header Hide Click On Body Js Start ========
  $(".header-button").on("click", function () {
    $(".body-overlay").toggleClass("show");
  });
  $(".body-overlay").on("click", function () {
    $(".header-button").trigger("click");
    $(this).removeClass("show");
  });
  // =============== Header Hide Click On Body Js End =========

  // ==========================================
  //      Start Document Ready function
  // ==========================================
  $(document).ready(function () {
    // ========================== Header Hide Scroll Bar Js Start =====================
    $(".navbar-toggler.header-button").on("click", function () {
      $("body").toggleClass("scroll-hide-sm");
    });
    $(".body-overlay").on("click", function () {
      $("body").removeClass("scroll-hide-sm");
    });
    // ========================== Header Hide Scroll Bar Js End =====================

    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js Start =====================
    $(".dropdown-item").on("click", function () {
      $(this).closest(".dropdown-menu").addClass("d-block");
    });
    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js End =====================

    // ========================== Add Attribute For Bg Image Js Start =====================
    $(".bg-img").css("background", function () {
      var bg = "url(" + $(this).data("background-image") + ")";
      return bg;
    });
    // ========================== Add Attribute For Bg Image Js End =====================

    // ========================== add active class to ul>li top Active current page Js Start =====================
    function dynamicActiveMenuClass(selector) {
      let fileName = window.location.pathname.split("/").reverse()[0];
      selector.find("li").each(function () {
        let anchor = $(this).find("a");
        if ($(anchor).attr("href") == fileName) {
          $(this).addClass("active");
        }
      });
      // if any li has active element add class
      selector.children("li").each(function () {
        if ($(this).find(".active").length) {
          $(this).addClass("active");
        }
      });
      // if no file name return
      if ("" == fileName) {
        selector.find("li").eq(0).addClass("active");
      }
    }
    if ($("ul.sidebar-menu-list").length) {
      dynamicActiveMenuClass($("ul.sidebar-menu-list"));
    }
    // ========================== add active class to ul>li top Active current page Js End =====================

    // ================== Password Show Hide Js Start ==========
    $(".toggle-password").on('click', function () {
      var input = $(this).siblings('input');
      $(this).toggleClass("fa fa-eye-slash");
      if (input.attr("type") == "password") {
        $(input).attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
    // =============== Password Show Hide Js End =================

    //!has-form--label class not present then add \\
    var label = $('label');
    if (!label.hasClass('form--label')) {
      label.addClass('form--label');
    }
    //End !has-form--label class not present then add \\


    // ================== Sidebar Menu Js Start ===============
    // Sidebar Dropdown Menu Start
    $(".has-dropdown > a").on('click', function () {
      $(".sidebar-submenu").slideUp(200);
      if ($(this).parent().hasClass("active")) {
        $(".has-dropdown").removeClass("active");
        $(this).parent().removeClass("active");
      } else {
        $(".has-dropdown").removeClass("active");
        $(this).next(".sidebar-submenu").slideDown(200);
        $(this).parent().addClass("active");
      }
    });
    // Sidebar Dropdown Menu End

    // Sidebar Icon & Overlay js
    $(".navigation-bar").on("click", function () {
      $(".sidebar-menu").addClass("show-sidebar");
      $(".sidebar-overlay").addClass("show");
    });
    $(".sidebar-menu__close, .sidebar-overlay").on("click", function () {
      $(".sidebar-menu").removeClass("show-sidebar");
      $(".sidebar-overlay").removeClass("show");
    });
    // Sidebar Icon & Overlay js
    // ===================== Sidebar Menu Js End =================

    // ==================== Dashboard User Profile Dropdown Start ==================
    $(".user-info__button").on("click", function () {
      $(".user-info-dropdown").toggleClass("show");
    });
    $(".user-info__button").attr("tabindex", -1).focus();

    $(".user-info__button").on("focusout", function () {
      $(".user-info-dropdown").removeClass("show");
    });
    // ==================== Dashboard User Profile Dropdown End ==================

  });
  // ==========================================
  //      End Document Ready function
  // ==========================================

  //lazy loading image
  function preloadImage(image) {
    const src = image.getAttribute("data-image_src");
    image.src = src;
  }

  let imageOptions = {
    threshold: 1,
  };

  const imageObserver = new IntersectionObserver((entries, imageObserver) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) {
        return;
      } else {
        preloadImage(entry.target);
        imageObserver.unobserve(entry.target);
      }
    });
  }, imageOptions);

  let images = document.querySelectorAll(".lazy-loading-img");
  images.forEach((image) => {
    imageObserver.observe(image);
  });

  //lazy loading image end


  // ========================= Preloader Js Start =====================
  $(window).on("load", function () {
    $(".preloader").fadeOut();
  });
  // ========================= Preloader Js End=====================

  // // ========================= Header Sticky Js Start ==============
  $(window).on("scroll", function () {
    if ($(window).scrollTop() >= 300) {
      $(".header").addClass("fixed-header");
    } else {
      $(".header").removeClass("fixed-header");
    }
  });
  // // ========================= Header Sticky Js End===================

  // //============================ Scroll To Top Icon Js Start =========
  var btn = $(".scroll-top");
  $(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
      btn.addClass("show");
    } else {
      btn.removeClass("show");
    }
  });

  btn.on("click", function (e) {
    e.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, "300");
  });

  // //========================= Custom select Js Start =====================
  const dropdownMainSelectColor = $("#edit-selected-color");
  const dropdownMainSelectName = $("#edit-selected-name");
  $(".single-color-list").on("click", function () {
    const getElementBg = this.children[0].getAttribute("data-bg-color");
    dropdownMainSelectColor.css("background-color", getElementBg);
    dropdownMainSelectName.text(this.innerText);
  });
  // color section
  $(".option-color").css("background", function () {
    return $(this).data("bg-color");
  });

  // //========================= Scroll To Top Icon Js End ======================

  // Custom color picker
  $("#customColor-input").iris({
    width: 300, // the width in pixel
    hide: false, // hide the color picker by default
    palettes: ["#125", "#459", "#78b", "#ab0", "#de3", "#f0f"], // custom palette
    change: function (event, ui) {
      dropdownMainSelectName.text(ui.color.toString());
      dropdownMainSelectColor.css("background-color", ui.color.toString());
    },
  });

  function openPopup() {
    if (!$(".custom-color") || !$("#customColor-input")) return;

    $(".custom-color").on("click", function () {
      $(".iris-picker.iris-border").addClass("d-block");
      $("#customColor-input").addClass("d-block");
    });

    $(document).on("click", function (event) {
      var target = $(event.target);
      if (
        target.closest(".iris-picker.iris-border").length ||
        target.closest("#customColor-input").length ||
        target.closest(".custom-color").length
      ) {
        $(".iris-picker.iris-border").addClass("d-block");
        $(".iris-picker.iris-border").removeClass("d-none");
        $("#customColor-input").addClass("d-block");
        $("#customColor-input").removeClass("d-none");
      } else {
        $(".iris-picker.iris-border").removeClass("d-block");
        $(".iris-picker.iris-border").addClass("d-none");
        $("#customColor-input").removeClass("d-block");
        $("#customColor-input").addClass("d-none");
      }
    });
  }
  openPopup();

  function selectPostType(whereClick, whichHide) {
    if (!whereClick && !whereClick) return;

    let isShowingPopup = false;

    whereClick.on("click", function () {
      if (isShowingPopup) {
        whichHide.addClass("d-none");
        whichHide.removeClass("d-block");
        isShowingPopup = false;
        return;
      }
      whichHide.addClass("d-block");
      whichHide.removeClass("d-none");
      isShowingPopup = true;
    });

    $(document).on("click", function (event) {
      var target = $(event.target);
      if (!target.closest(whereClick).length) {
        whichHide.addClass("d-none");
        whichHide.removeClass("d-block");
        isShowingPopup = false;
      }
    });
  }
  selectPostType(
    $(".post-type .post-type-selected"),
    $(".widget-card .post-type .post-type-select")
  );

  //========================= Emoji Plugin Js End ======================

  // Skeleton//
  setTimeout(function () {
    $('.skeleton').removeClass('skeleton');
  }, 1000);
  // Skeleton//

  //copy-for-share//
  $('.copy-btn').on('click', async function () {
    var link = $(this).data('link');
    await navigator.clipboard.writeText(link);
    $(this).parent().find('i.fa-copy').addClass('copied');
    setTimeout(() => {
      $(this).parent().find('i.fa-copy').removeClass('copied');
    }, 2000);
  });

  // ========================= Slick Slider Js Start ==============
  $('.work-slider').slick({
    prevArrow: '<span class="prev-arrow"><i class="las la-angle-left"></i></span>',
    nextArrow: '<span class="next-arrow"><i class="las la-angle-right"></i></span>',
    slidesToShow: 1,
    slidesToScroll: 1,
    // autoplay: true,
    autoplaySpeed: 2000,
    speed: 1500,
    dots: false,
    draggable: true,
    pauseOnHover: true,
    arrows: true,
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          arrows: false,
          slidesToShow: 1,
          dots: true,
        }
      }
    ]
  });

  // ========================= Slick Slider Js End ===================


  //copy-for-share//
  $('#copyBtn').on('click', async function () {
    var link = $(this).data('link');
    await navigator.clipboard.writeText(link);
    $(this).parent().find('i.fa-copy').addClass('copied');
    setTimeout(() => {
      $(this).parent().find('i.fa-copy').removeClass('copied');
    }, 2000);
  });

  $('#copyBtn').on('click', function (e) {
    e.stopPropagation(); // Prevent the click event from propagating to parent elements
  });



  // Check is have alt tag
  $('img').each(function () {
    const attr = $(this).attr('alt')

    if (!attr && !attr == '') {
      console.log(this);
    }
  })



})(jQuery);
