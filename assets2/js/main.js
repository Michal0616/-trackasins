$(function(){
    /* Script for navbar scrolling */
    var nav = $('.topHeaderMain'); // Change to nav div
    var nav_class = 'shadow-nav'; // Change to class name
    var threshold = 1; // Change to pixels scrolled

    var nav2 = $('.topHeaderMainLogged'); // Change to nav div
    var nav_class2 = 'shadow-nav'; // Change to class name
    var threshold2 = 1; // Change to pixels scrolled

    /* ============================================================
     * flatui-radiocheck v0.1.0
     * ============================================================ */

    +function (global, $) {
        'use strict';

        var Radiocheck = function (element, options) {
            this.init('radiocheck', element, options);
        };

        Radiocheck.DEFAULTS = {
            checkboxClass: 'custom-checkbox',
            radioClass: 'custom-radio',
            checkboxTemplate: '<span class="icons"><span class="icon-unchecked"></span><span class="icon-checked"></span></span>',
            radioTemplate: '<span class="icons"><span class="icon-unchecked"></span><span class="icon-checked"></span></span>'
        };

        Radiocheck.prototype.init = function (type, element, options) {
            this.$element = $(element);
            this.options = $.extend({}, Radiocheck.DEFAULTS, this.$element.data(), options);
            if (this.$element.attr('type') == 'checkbox') {
                this.$element.addClass(this.options.checkboxClass);
                this.$element.after(this.options.checkboxTemplate);
            } else if (this.$element.attr('type') == 'radio') {
                this.$element.addClass(this.options.radioClass);
                this.$element.after(this.options.radioTemplate);
            }
        };

        Radiocheck.prototype.check = function () {
            this.$element.prop('checked', true);
            this.$element.trigger('change.radiocheck').trigger('checked.radiocheck');
        },

            Radiocheck.prototype.uncheck = function () {
                this.$element.prop('checked', false);
                this.$element.trigger('change.radiocheck').trigger('unchecked.radiocheck');
            },

            Radiocheck.prototype.toggle = function () {
                this.$element.prop('checked', function (i, value) {
                    return !value;
                });
                this.$element.trigger('change.radiocheck').trigger('toggled.radiocheck');
            },

            Radiocheck.prototype.indeterminate = function () {
                this.$element.prop('indeterminate', true);
                this.$element.trigger('change.radiocheck').trigger('indeterminated.radiocheck');
            },

            Radiocheck.prototype.determinate = function () {
                this.$element.prop('indeterminate', false);
                this.$element.trigger('change.radiocheck').trigger('determinated.radiocheck');
            },

            Radiocheck.prototype.disable = function () {
                this.$element.prop('disabled', true);
                this.$element.trigger('change.radiocheck').trigger('disabled.radiocheck');
            },

            Radiocheck.prototype.enable = function () {
                this.$element.prop('disabled', false);
                this.$element.trigger('change.radiocheck').trigger('enabled.radiocheck');
            },

            Radiocheck.prototype.destroy = function () {
                this.$element.removeData().removeClass(this.options.checkboxClass + ' ' + this.options.radioClass).next('.icons').remove();
                this.$element.trigger('destroyed.radiocheck');
            };

        // RADIOCHECK PLUGIN DEFINITION
        // ============================

        function Plugin(option) {
            return this.each(function () {
                var $this   = $(this);
                var data    = $this.data('radiocheck');
                var options = typeof option == 'object' && option;

                if (!data && option == 'destroy') { return; }
                if (!data) {
                    $this.data('radiocheck', (data = new Radiocheck(this, options)));
                }
                if (typeof option == 'string') {
                    data[option]();
                }

                // Adding 'nohover' class for mobile devices

                var mobile = /mobile|tablet|phone|ip(ad|od)|android|silk|webos/i.test(global.navigator.userAgent);

                if (mobile === true) {
                    $this.parent().hover(function () {
                        $this.addClass('nohover');
                    }, function () {
                        $this.removeClass('nohover');
                    });
                }
            });
        }

        var old = $.fn.radiocheck;

        $.fn.radiocheck             = Plugin;
        $.fn.radiocheck.Constructor = Radiocheck;

        // RADIOCHECK NO CONFLICT
        // ======================

        $.fn.radiocheck.noConflict = function () {
            $.fn.radiocheck = old;
            return this;
        };

    }(this, jQuery);


    $(window).scroll(function () {
        var distance = $(this).scrollTop();
        if (distance > threshold) { // If scrolled past threshold
            nav.addClass(nav_class); // Add class to nav
        } else { // If user scrolls back to top
            if (nav.hasClass(nav_class)) { // And if class has been added
                nav.removeClass(nav_class); // Remove it
            }
        }
    });

    $(window).scroll(function () {
        var distance2 = $(this).scrollTop();
        if (distance2 > threshold2) { // If scrolled past threshold
            nav2.addClass(nav_class2); // Add class to nav
        } else { // If user scrolls back to top
            if (nav2.hasClass(nav_class2)) { // And if class has been added
                nav2.removeClass(nav_class2); // Remove it
            }
        }
    });

});