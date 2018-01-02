define(['app', 'cookies'], function (App, Cookies) {
    'use strict';

    App.AdminPanel = {
        timeout: 0,
        panel: $('.adminpanel'),
        close: $('.ap-close'),
        scroll: $('.ap-scroll-up'),

        config: {
            inactive_opacity: 0.9,
            active_opacity: 1
        },

        init: function () {
            var $this = this;
            this.close.on('click touchend', function (e) {
                e.preventDefault();
                if ($this.panel.hasClass('ap-opened')) {
                    $this.closePanel();
                } else {
                    $this.openPanel();
                }
            });

            $(document).on('mouseenter', '.ap-close, .adminpanel', function () {
                clearTimeout($this.timeout);
                $this.timeout = setTimeout(function () {
                    $('.adminpanel').animate({
                        opacity: $this.config.active_opacity
                    }, 100);
                }, 1);
            });

            $(document).on('mouseleave', '.ap-close, .adminpanel', function () {
                clearTimeout($this.timeout);
                $this.timeout = setTimeout(function () {
                    $('.adminpanel').animate({
                        opacity: $this.config.inactive_opacity
                    }, 100);
                }, 1);
            });

            this.adjustHeight();
            this.panel.css('opacity', $this.config.inactive_opacity);
            this.close.css('opacity', $this.config.inactive_opacity);

            $(window).on('resize', function() {
                App.AdminPanel.adjustHeight();
            });

            if (this.scroll.length) {
                $(window).on('scroll', function () {
                    if ($(this).scrollTop() > 100) {
                        $this.scroll.fadeIn().css('display', 'block');
                    }
                    else {
                        $this.scroll.fadeOut();
                    }
                });
                $this.scroll.on('click', function (e) {
                    e.preventDefault();
                    $('html, body').animate({scrollTop: 0}, $(window).height());
                });
                $(document).trigger('scroll');
            }
        },


        openPanel: function () {
            var $this = this;
            this.panel.animate({
                opacity: $this.config.active_opacity,
                left: 0,
            }, 300, function () {
                $this.close.find('.ap-caret').removeClass('ap-closed').addClass('ap-opened');
                $this.panel.removeClass('ap-closed').addClass('ap-opened');
                Cookies.remove('adminpanel_closed', {path: '/', domain: App.domain});
                $this.adjustHeight();
            });
        },

        closePanel: function () {
            var $this = this;
            this.panel.animate({
                opacity: $this.config.inactive_opacity,
                left: '-100%',
            }, 300, function () {
                $this.close.find('.ap-caret').removeClass('ap-opened').addClass('ap-closed');
                $this.panel.removeClass('ap-opened').addClass('ap-closed');
                Cookies.set('adminpanel_closed', true, {expires: 365, path: '/', domain: App.domain});
                $this.adjustHeight();
            });
        },

        adjustHeight: function () {
            var height = this.panel.height();
            if (height) {
                this.close.css('height', height)
                    .find('.ap-caret').css('bottom', height - (height / 2) - 5);
            }
        }
    };

    App.AdminPanel.init();
});