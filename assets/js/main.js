(function ($) {

    function rtPreloader() {
        $('#preloader').fadeOut('slow', function () {
            $(this).remove();
        });
    
    }

    rtPreloader();

    function rdtheme_wc_scripts() {
        /* Shop change view */
        $('#shop-view-mode li a').on('click', function () {
            $('body').removeClass('product-grid-view').removeClass('product-list-view');

            if ($(this).closest('li').hasClass('list-view-nav')) {
                $('body').addClass('product-list-view');
                Cookies.set('shopview', 'list');
            } else {
                $('body').addClass('product-grid-view');
                Cookies.remove('shopview');
            }
            return false;
        });
    }


    /*-------------------------------------
    On Scroll
    -------------------------------------*/
    $(window).on('scroll', function () {
        // Sticky Header
        if ($('body').hasClass('sticky-header')) {
            // Sticky header
            var stickyPlaceHolder = $("#rt-sticky-placeholder"),
                menu = $("#header-menu"),
                menuH = menu.outerHeight(),
                topHeaderH = $('#tophead').outerHeight() || 0,
                middleHeaderH = $('#middleHeader').outerHeight() || 0,
                targrtScroll = topHeaderH + middleHeaderH;
            if ($(window).scrollTop() > targrtScroll) {
                menu.addClass('rt-sticky');
                stickyPlaceHolder.height(menuH);
            } else {
                menu.removeClass('rt-sticky');
                stickyPlaceHolder.height(0);
            }

            // Sticky mobile header
            var stickyPlaceHolder = $("#mobile-sticky-placeholder"),
                menubar = $("#mobile-men-bar"),
                menubarH = menubar.outerHeight(),
                topHeaderH = $('#mobile-top-fix').outerHeight() || 0,
                total_height =topHeaderH;
            if ($(window).scrollTop() > total_height) {
                $("#meanmenu").addClass('mobile-sticky');
                stickyPlaceHolder.height(menubarH);             
            } else {
                $("#meanmenu").removeClass('mobile-sticky');
                stickyPlaceHolder.height(0);
            }
        }
    });

    /*-------------------------------------
    On load and resize
    -------------------------------------*/
    $(window).on("load resize", function () {
        if (ClCarObj.rtStickySidebar === 'enable') {
            $('#sticky_sidebar').rtStickySidebar({
                additionalMarginTop: Number(ClCarObj.lsSideOffset) + 10,
                additionalMarginBottom: 20,
            });
        }
    });

   

    /*-------------------------------------
    Video Popup
    -------------------------------------*/
    var yPopup = $(".popup-youtube");
    if (yPopup.length) {
        yPopup.magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false
        });
    }


    if ('enable' === ClCarObj.rtMagnificPopup) {
        $('.blocks-gallery-item a').filter(function () {
            return this.href.match(/((.jpg|.gif|.png|.jpeg|.svg))/i);
        }).magnificPopup({
            type: 'image',
            mainClass: 'mfp-with-zoom',
            zoom: {
                enabled: true,
                duration: 300,
                easing: 'ease-in-out',
                opener: function (openerElement) {
                    return openerElement.is('img') ? openerElement : openerElement.find('img');
                }
            },
            gallery: {
                enabled: true
            }
        });
    }


    // Window Ready
    jQuery(document).ready(function ($) {

        rdtheme_content_ready_scripts();
        rdtheme_wc_scripts();

        //Favourite Icon Update
        //=========================
        $(document).on('rtcl.favorite', function (e, data) {
            var $favCount = $(".rt-header-favourite-count").first();
            var $favCountAll = $(".rt-header-favourite-count");
            var favCountVal = parseInt($favCount.text(), 10);
            favCountVal = isNaN(favCountVal) ? 0 : favCountVal;
            if ("added" === data.action) {
                favCountVal++;
                $favCountAll.text(favCountVal);
            } else if ("removed" === data.action) {
                favCountVal--;
                $favCountAll.text(favCountVal);
            }
        });
        //End Favourite Icon Update

        //Compare icon update
        //====================
        $(document).on('rtcl.compare.added', function (e, data) {
            $('.rt-compare-count').text(data.current_listings);
        });

        $(document).on('rtcl.compare.removed', function (e, data) {
            $('.rt-compare-count').text(data.current_listings);
        });

        $(document).on('click', '.rtcl-compare-btn-clear', function () {
            $('.rt-compare-count').text('0');
        });

        //End Compare icon update

        $('.rtcl-item-visible-btn').on('click', function (e) {
            e.preventDefault();
            $(this).parents('.advance-search-form').find('.expanded-wrap').slideToggle();
        });

        $('.input-group .form-control').on('focus', function () {
            $(this).parent('.input-group').addClass('active');
        }).on('focusout', function () {
            $(this).parent('.input-group').removeClass('active');
        });

        $('.single-listing-style-2 .single-product .product-heading').fadeIn();

        /* Scroll to top */
        const backToTop = document.querySelector(".scrollup");
        window.onscroll = function () {
            scrollFunction();
        };
        function scrollFunction() {
            if (backToTop !== null) {
                if (
                    document.body.scrollTop > 80 ||
                    document.documentElement.scrollTop > 80
                ) {
                    backToTop.style.display = "block";
                    backToTop.classList.add('back-top');
                } else {
                    backToTop.style.display = "none";
                    backToTop.classList.remove('back-top');
                }
            }
        }
        if (backToTop !== null) {
            backToTop.addEventListener("click", (e) => {
            e.preventDefault();
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
            });
        }

        // Add class to listing search filter radios
        $('.search-radio-check ul li:first-child label').addClass('active');
        var $rtSearchRadioButtons = $('.search-radio-check input[type="radio"]');
        $rtSearchRadioButtons.click(function () {
            $rtSearchRadioButtons.each(function () {
                $(this).parent().toggleClass('active', this.checked);
            });
        });


        // Mobile Menu

        var mobileMenu = $('.offscreen-navigation nav ul');

        if (mobileMenu.length) {
            mobileMenu.children("li").addClass("menu-item-parent");

            mobileMenu.find(".menu-item-has-children > a, .page_item_has_children > a").append('<span class="pointer"></span>')
            mobileMenu.find(".menu-item-has-children > a > .pointer, .page_item_has_children > a > .pointer").on("click", function (e) {
                e.preventDefault();
                $(this).parent().toggleClass("opened");
                var n = $(this).parent().next(".sub-menu, .children"),
                    s = $(this).parent().closest(".menu-item-parent").find(".sub-menu, .children");
                //mobileMenu.find(".sub-menu, .children").not(s).slideUp(250).prev('a').removeClass('opened'); 
                n.slideToggle(250);
            });

            mobileMenu.find('.menu-item:not(.menu-item-has-children, .page_item_has_children) > a').on('click', function (e) {
                $('.rt-slide-nav').slideUp();
                $('body').removeClass('slidemenuon');
            });
        }


        $('.sidebarBtn.circle-btn').on('click', function (e) {
            e.preventDefault();
            $('.overly-sidebar-wrapper').addClass('show');
            $('.offcanvas-menu-btn').addClass('menu-status-open');
        });

        $('.mean-bar .sidebarBtn').on('click', function (e) {
            e.preventDefault();

            if ($('.rt-slide-nav').is(":visible")) {
                $('.rt-slide-nav').slideUp();
                $('body').removeClass('slidemenuon');
            } else {
                $('.rt-slide-nav').slideDown();
                $('body').addClass('slidemenuon');
            }

        });

        

    });

    // Window Load
    $(window).on('load', function () {
        // Scripts needs loading inside content area
        rdtheme_content_load_scripts();
        isSelect2();


        // Number Field range slider
        if ($.fn.ionRangeSlider) {
            $(".ion-rangeslider").each(function () {
                var $this = $(this);
                var rangeType = $this.data('type');
                $this.ionRangeSlider({
                    type: rangeType || "double",
                    drag_interval: true,
                    min_interval: null,
                    max_interval: null,
                    onChange: function (data) {
                        var $inp = data.input;
                        $inp.parent().find('.min-volumn').val(data.from);
                        $inp.parent().find('.max-volumn').val(data.to);
                    },
                });
            });
        }

        // Advanced Search Revel
        $(".advanced-btn").on("click", function () {
            $(this).toggleClass("collapsed active-open");
            $("#advanced-search").toggleClass("show");
        });

        // Share Icon reveled
        $("#share-btn").on("click", function (e) {
            e.preventDefault();
            $(this).siblings('.share-icon').toggleClass('open');
        });

        

         // Delete Logo Image
    $(".remove-logo-image a").on("click", function (e) {
        e.preventDefault();
        let attachmentID = $(this).data('attachment_id');
        let postID = $(this).data('post_id');
        let container = $(this).parents('.logo-image');
        let inputWrapper = $('.logo-input-wrapper');

        let r = confirm('Are you want to delete this attachment?');

        if (r) {
            $.ajax({
                type: "post",
                url: ClCarObj.ajaxUrl,
                
                data: {
                    action: "delete_listing_logo_attachment",
                    attachment_id: attachmentID,
                    post_id: postID,
                },
                success: function (response) {
                    if (response === 'success') {
                        container.fadeOut(function () {
                            container.remove();
                            inputWrapper.toggleClass('d-none');
                        });
                    }
                }
            })
        }
    });


    });

    function rdtheme_content_ready_scripts() {

        /*---------------------------------------
          Background Parallax
          --------------------------------------- */
            if ($(".rt-parallax-bg-yes").length) {
                $(".rt-parallax-bg-yes").each(function () {
                    var speed = $(this).data('speed');
                    $(this).parallaxie({
                        speed: speed ? speed : 0.5,
                        offset: 0,
                    });
                });
            }

            $('body').on('change', '.rtcl-widget-search-form', function(e){
            	e.stopPropagation();
            	return false;
            })

            $('body').on('change', 'select[name=rtcl_category]', function(e){
            	e.stopPropagation();
            	return false;
            })



        /*Custom Ranger SLider for text field*/
        /*TODO:: Conditonaly add korte hobe ta na hole sob jaigai add hoe jabe*/

        // var searchItemDiv = $('<div>').addClass('search-item');
        // var priceRangeDiv = $('<div>').addClass('kilo-range');
        // var label = $('<label>').text('Kilometters Run');
        // var input = $('<input>').attr({
        //     type: 'number',
        //     class: 'my-ion-rangeslider',
        //     'data-min': '0',
        //     'data-max': '5000',
        //     'data-from': '100',
        //     'data-to': '1000'
        // });
        // var minVolumeInput = $('<input>').attr({
        //     type: 'hidden',
        //     class: 'min-volumn',
        //     name: 'filters[kilo][min]',
        //     value: '100'
        // });
        // var maxVolumeInput = $('<input>').attr({
        //     type: 'hidden',
        //     class: 'max-volumn',
        //     name: 'filters[kilo][max]',
        //     value: '1000'
        // });
        //
        // priceRangeDiv.append(label, input, minVolumeInput, maxVolumeInput);
        // searchItemDiv.append(priceRangeDiv);
        //
        // $('.advance-search-form .rtin-location').after(searchItemDiv);
        // $(".my-ion-rangeslider").ionRangeSlider({
        //     type: "double",
        //     onChange: function (data) {
        //         console.log(data.from);
        //         console.log(data.to);
        //     },
        // });

        /*Custom Ranger SLider for text field end*/


        /*======================================
        //TweenMax Mouse Effect
        ====================================*/

        $(".motion-effects-wrap").mousemove(function(e) {
            parallaxIt(e, '.motion-effects1', -100);
            parallaxIt(e, '.motion-effects2', -200);
            parallaxIt(e, '.motion-effects3', 100);
            parallaxIt(e, '.motion-effects4', 200);
            parallaxIt(e, '.motion-effects5', -50);
            parallaxIt(e, '.motion-effects6', 50);
            parallaxIt(e, '.motion-effects7', 10);
        });
    
        function parallaxIt(e, target_class, movement) {
            var $wrap = $(e.target).parents('.motion-effects-wrap');
            if (!$wrap.length)
                return;
            var $target = $wrap.find(target_class);
            var relX = e.pageX - $wrap.offset().left;
            var relY = e.pageY - $wrap.offset().top;
    
            TweenMax.to($target, 1, {
                x: (relX - $wrap.width() / 2) / $wrap.width() * movement,
                y: (relY - $wrap.height() / 2) / $wrap.height() * movement
            });
        }

        $('.clcar-related-slider').each(function () {
            var $this = $(this); 
            var settings=$this.data('options');
            var autoplay=settings['autoplay'] == 1 ? true:false;
            new Swiper($this[0], {
                loop: true,
                slidesPerView: 3,
                speed:1000,
                autoplay:autoplay,
                spaceBetween:  24,
                navigation: {nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev'},
                breakpoints: {
                    0: {
                        slidesPerView: settings['breakpoints']['0']['slidesPerView'],
                    },
                    576: {
                        slidesPerView: settings['breakpoints']['576']['slidesPerView'],
                    },
                    768: {
                        slidesPerView: settings['breakpoints']['768']['slidesPerView'],
                    },
                    992: {
                        slidesPerView: settings['breakpoints']['992']['slidesPerView'],
                    },
                },
            });
        });

        //global swiper slider

        $('.rt-global-slider').each(function() {
            var $this = $(this);
            $this.fadeIn();
            var settings = $this.data('slider-options');
            var autoplayconditon= settings['auto'];
            var $pagination = $this.find('.el-swiper-pagination')[0];
            var $next = $this.find('.custom-swiper-button-next')[0];
            var $prev = $this.find('.custom-swiper-button-prev')[0];
             new Swiper( $this[0], {
                    speed:settings['speed'],
                    direction:  settings['direction'],
                    spaceBetween:  settings['spaceBetween'],
                    slidesPerGroup: settings['slidesPerGroup'] ? settings['slidesPerGroup'] : 1,
                    autoplay: autoplayconditon ? { delay:settings['autoplay']['delay'] } : false,
                    pagination: {
                        el: $pagination,
                        clickable: true,
                        type: 'bullets',
                    },
                    navigation: {
                        nextEl: $next,
                        prevEl: $prev,
                    },
                    breakpoints: {
                        0: {
                            slidesPerView: settings['breakpoints']['0']['slidesPerView'],
                        },
                        576: {
                            slidesPerView: settings['breakpoints']['576']['slidesPerView'],
                        },
                        768: {
                            slidesPerView: settings['breakpoints']['768']['slidesPerView'],
                        },
                        992: {
                            slidesPerView: settings['breakpoints']['992']['slidesPerView'],
                        },
                        1200: {
                            slidesPerView:  settings['breakpoints']['1200']['slidesPerView'],
                        },
                        1600: {
                            slidesPerView: settings['breakpoints']['1600']['slidesPerView'],
                        },
                    },
            });

        });


        /*===================================
        // Section background image 
        ====================================*/
        imageFunction();

        function imageFunction() {
            $("[data-bg-image]").each(function () {
            let img = $(this).data("bg-image");
            $(this).css({
                backgroundImage: "url(" + img + ")",
            });
            });
        }

        //counterUp
        
        var counterContainer = $('.counter');
        if (counterContainer.length) {
            counterContainer.counterUp({
                delay: counterContainer.data('rtsteps'),
                time: counterContainer.data('rtspeed')
            });
        }

        //elementor advance listing search

        // Advanced Search Revel
        $(".rt-hero-section  .advanced-btn").on("click", function () {
            $(this).toggleClass("collapsed");
            $("#rt-search-custom-field").toggleClass("show");

        });

        // Add class to listing search filter radios
        // $('.search-radio-check ul li:nth-child(2) label').addClass('active');
        // var $rtSearchRadioButtons = $('.search-radio-check input[type="radio"]');
        // $rtSearchRadioButtons.click(function () {
        //     $rtSearchRadioButtons.each(function () {
        //         $(this).parent().toggleClass('active', this.checked);
        //     });
        // });

        /* Wow Js Init */
        var wow = new WOW({
            boxClass: 'wow',
            animateClass: 'animated',
            offset: 0,
            mobile: false,
            live: true,
            scrollContainer: null,
        });

        new WOW().init();
    }
   
    //Select2 js
    function isSelect2() {
        // Select2 Activation
        var $select2 = $('select.select2');
        if ($select2.length) {
            $select2.select2({
                theme: 'classic',
                dropdownAutoWidth: true,
                width: '100%',
            });
        }
    }

    function rdtheme_content_load_scripts() {

        $('.rtcl-sold-out, .section-title-wrapper .bg-title-wrap').fadeIn();
        $('.rtrs-review-wrap .rtrs-review-form .rtrs-form-group .rtrs-submit-btn').parent('.rtrs-form-group').addClass('rtrs-submit-button');
        // $('.single-product .product-amenities .amenities-list ')

        $('.button-times').on('click', function (e) {
            e.preventDefault();
            $(this).parents('.advanced-search-box').removeClass('show');
        });

        $(".advance-search-form.is-preloader").each(function () {
            $(this).removeClass('is-preloader');
        });


        //Add Class on search hover
        $(".header-btn .search-icon-wrapper .input-group .form-control").click(function () {
            $(this).parents('.search-icon').addClass('active');
        });

        //Remove Class on click out site of search
        $(document).on("click", function (e) {
            if ($(e.target).is(".header-btn .search-icon-wrapper .input-group .form-control") === false) {
                $(".header-btn .icon-hover-item").removeClass("active");
            }
        });

        // Isotope
        if (typeof $.fn.isotope == 'function') {
            // Run 1st time
            var $isotopeContainer = $('#inner-isotope');

            setTimeout(function () {
                $isotopeContainer.each(function () {
                    var $container = $(this).find('.featuredContainer'),
                        filter = $(this).find('.isotope-classes-tab a.current').data('filter');
                    runIsotope($container, filter);
                });

                // Run on click event

                $('.isotope-classes-tab a').on('click', function () {
                    $(this).closest('.isotope-classes-tab').find('.current').removeClass('current');
                    $(this).addClass('current');
                    var $container = $(this).closest('.isotope-wrap').find('.featuredContainer'),
                        filter = $(this).attr('data-filter');
                    runIsotope($container, filter);
                    return false;
                });

            }, 1000);
        }
  
    }

    // Elementor Frontend Load
    $(window).on('elementor/frontend/init', function () {
        if (elementorFrontend.isEditMode()) {
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', function () {
                rdtheme_content_ready_scripts();
                rdtheme_content_load_scripts();
                isSelect2();
            
            });
        }
    });

    $('.rt-el-listing-wrapper').each(function (){
        var $container = $(this).find('.featuredContainer');
        $container.isotope({
            transitionDuration: "1s",
            hiddenStyle: {
                opacity: 0,
                transform: "scale(0.001)"
            },
            visibleStyle: {
                transform: "scale(1)",
                opacity: 1
            }
        });
    })

    function runIsotope($container, filter) {
        $container.isotope({
            filter: filter,
            transitionDuration: "1s",
            originLeft: ClCarObj.isRTL == 'rtl' ? false : true,
            hiddenStyle: {
                opacity: 0,
                transform: "scale(0.001)"
            },
            visibleStyle: {
                transform: "scale(1)",
                opacity: 1
            }
        });
    }

    /**
     * Listing details popup
     */
    function listygo_lightbox2() {
        // Define click event on gallery item
        $('.listing-gallery-item .listing-popup-btn').click(function(event) {
            // Prevent location change
            event.preventDefault();
            // Init empty gallery array
            var container = [];
            var allPhotos = $(this).parents('.photo-swip-gallery-wrap').find('.listing-gallery-item');

            var currentClickedIndex = allPhotos.index($(this).parent());
            // Loop over gallery items and push it to the array
            allPhotos.each(function() {
                var $link = $(this).find('.listing-popup-btn'),
                    item = {
                        src: $link.attr('href'),
                        w: $link.data('width'),
                        h: $link.data('height'),
                    };
                container.push(item);
            });

            // Define object and gallery options
            var $pswp = $('.pswp')[0],
                options = {
                    index: currentClickedIndex,
                    bgOpacity: 0.85,
                    showHideOpacity: true,
                    zoomEl: true,
                    tapToClose: true,
                    fullscreenEl: true,
                    preloaderEl: true,
                    shareEl: true,
                    captionEl: false,
                };

            // Initialize PhotoSwipe
            var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
            gallery.init();
        });
    }
    listygo_lightbox2();

})(jQuery);