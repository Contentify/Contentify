/*
 *  Contentify Built-in Slider
 */
(function ($) {
    'use strict';

    /*
     *  This function is available and called via $('...'').contentifySlider()
     *  opts = options / parameters
     */
    $.fn.contentifySlider = function(opts) {
        /*
         *  Go through all jQuery-objects the function might have received.
         *  Rembember: $('not-existing-el') -> 0 objects, $('#existing-id') -> 1 object, $('.severalels') -> >1 elements
         */
        this.each(function() {
            new ContenfitySlider(this, opts);
        });
    }

    /*
     *  This function contains the main code.
     */
    function ContenfitySlider(el, opts) {
        this.$el  = $(el);  // Save the DOM element that this plugin was initialized on for further use
        this.defaults = {   // These are the default options
            itemindex   : 1,        // Index of the item to activate at the beginning. The first item has index 1!
            delay       : 10000,    // Milliseconds, used by autoplay
            fadetime    : 'slow',   // Used by jquery.fadein() / fadeout()
            autoplay    : true      // Enable or disable autoplaying
        };
        this.opts       = $.extend(this.defaults, opts);        // Override the default options (if possible)
        this.itemindex  = this.opts.itemindex;                  // Protect itemindex by hiding it in a var outside of opts
        this.itemcount  = this.$el.find('.slides li').length;   // Amount of items
        this.interval   = null                                  // To keep the interval id

        var self = this; // Keep an instance of this widget for further use

        self.$el.find('.slides li').css('display','none');                                      // Hide all slides
        self.$el.find('.slides li:nth(' + (self.itemindex-1) + ')').css('display','block');     // Show a certain slide
        self.$el.find('.buttons li:nth(' + (self.itemindex-1) + ')').addClass('active');        // Set a certain button as active
        var text = self.$el.find('.slides li:nth(' + (self.itemindex - 1) + ')').attr('data-title');

        self.$el.find('.to-left').click(function(event)
        {
            event.preventDefault();
            goToPrevious();
        });
        self.$el.find('.to-right').click(function(event)
        {
            event.preventDefault();
            goToNext();
        });

        self.$el.find('.buttons li').click(function() { // Add the click-event to the buttons
            var index = self.$el.find('.buttons li').index(this) + 1;
            goTo(index);
        });

        if (self.itemcount > 0 && self.opts.autoplay === true) {
            startAutoplay(); // Itemcount may be 0 if the wrong DOM element was selected as slider
        }

        function startAutoplay() {
            if (self.interval != null) {
                window.clearInterval(self.interval);
                delete self.interval;
            }
            self.interval = window.setInterval(doAutoplay, self.opts.delay);
        }

        function doAutoplay() {
            goToNext();
        }

        function goToPrevious() {
            var index = self.itemindex - 1;
            if (index < 1) index = self.itemcount;
            goTo(index);
        }

        function goToNext() {
            var index = self.itemindex + 1;
            if (index > self.itemcount) index = 1;
            goTo(index);
        }

        function goTo(index) {
            self.itemindex = index; // Apply the new index

            self.$el.find('.slides li').each(function(i) { // Change slide
                if (i + 1 == index) {
                    $(this).fadeIn(self.opts.fadetime);
                } else {
                    $(this).fadeOut(self.opts.fadetime);
                }
            });

            self.$el.find('.buttons li').each(function(i) { // Change active button
                if (i + 1 == index) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });

            if (self.interval != null) { 
                startAutoplay(); // Restart autoplay, so it cannot switch to another slide during the next moment
            }
        }
    }
})(jQuery);