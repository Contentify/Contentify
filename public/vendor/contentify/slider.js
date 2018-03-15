/*
 *  Contentify Built-in Slider
 */
(function ($) {
    'use strict';

    /**
     *  This function is available and called via $('...'').contentifySlider()
     *  
     *  @param {object} opts The options / parameters
     */
    $.fn.contentifySlider = function(opts) 
    {
        // Go through all jQuery-objects the function might have received.
        this.each(function() {
            new ContentifySlider(this, opts);
        });
    };

    /**
     * Creates an actual slider instance.
     * 
     * @param {object} el   The slider DOM element
     * @param {object} opts The options object
     */
    function ContentifySlider(el, opts) 
    {
        this.$el  = $(el);  // Save the DOM element that this plugin was initialized on for further use
        this.defaults = {   // These are the default options
            itemIndex   : 1,        // Index of the item to activate at the beginning. The first item has index 1!
            delay       : 10000,    // Milliseconds, used by autoplay
            fadeTime    : 'slow',   // 'slow' / 'normal / 'fast' / milliseconds. Used for the transition animation.
            autoplay    : true,     // Enable or disable autoplaying
            showTitle   : true      // Show the title of the slides in a h2.slide-title element?
        };
        this.opts       = $.extend(this.defaults, opts);        // Override the default options (if possible)
        this.itemIndex  = this.opts.itemIndex;                  // Protect itemIndex by hiding it outside of opts
        this.itemCount  = this.$el.find('.slides li').length;   // Amount of items
        this.interval   = null;                                 // To keep the interval ID

        var self = this; // Keep an instance of this widget for further use
        var $h2 = $('<h2>').addClass('slide-title');
        
        if (self.opts.showTitle) {
            $(el).find('.container').append($h2); // Add h2 that displays the title of the active slide
        }

        self.$el.find('.slides li').css('display','none'); // Hide all slides

        var $li = self.$el.find('.slides li:nth(' + (self.itemIndex - 1) + ')');
        $li.css('display','block'); // Show a certain slide
        self.$el.find('.buttons li:nth(' + (self.itemIndex-1) + ')').addClass('active'); // Set button as active
        var text = $li.attr('data-title');
        var url = $li.find('a').attr('href');

        if (text == 'notitle') {
            $h2.html('');
        } else {
            $h2.html('<a href="' + url + '">' + text + '</a>');
        }

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

        if (self.itemCount > 0 && self.opts.autoplay === true) {
            startAutoplay(); // itemCount may be 0 if the wrong DOM element was selected as slider
        }

        /**
         * Start auto playing
         * @return void
         */
        function startAutoplay() 
        {
            if (self.interval != null) {
                window.clearInterval(self.interval);
                delete self.interval;
            }
            self.interval = window.setInterval(doAutoplay, self.opts.delay);
        }

        /**
         * When autoplaying, switch to the next slide 
         * @return void
         */
        function doAutoplay() 
        {
            goToNext();
        }

        /**
         * Switch to the previous slide (or the last if index becomes < 1)
         * @return void
         */
        function goToPrevious() 
        {
            var index = self.itemIndex - 1;
            if (index < 1) {
                index = self.itemCount;
            }
            goTo(index);
        }

        /**
         * Switch to the next slide (or the first if index becomes > number of slides)
         * @return void
         */
        function goToNext() 
        {
            var index = self.itemIndex + 1;
            if (index > self.itemCount) {
                index = 1;
            }
            goTo(index);
        }

        /**
         * Switch to a certain slide
         * @param  {int} index Index of the slide
         * @return void
         */
        function goTo(index) 
        {
            self.itemIndex = index; // Apply the new index

            self.$el.find('.slides li').each(function(i) { // Change slide
                if (i + 1 == index) {
                    $(this).fadeIn(self.opts.fadeTime);
                } else {
                    $(this).fadeOut(self.opts.fadeTime);
                }
            });

            self.$el.find('.buttons li').each(function(i) { // Change active button
                if (i + 1 == index) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });

            var $li =  self.$el.find('.slides li:nth(' + (index - 1) + ')');
            var text = $li.attr('data-title');
            var url = $li.find('a').attr('href');

            if (text == 'notitle') {
                $h2.html('');
            } else {
                $h2.html('<a href="' + url + '">' + text + '</a>');
            }

            if (self.interval != null) { 
                startAutoplay(); // Restart autoplay, so it cannot switch to another slide during the next moment
            }
        }
    }
})(jQuery);