/*
 *  Contentify Built-in Scroller
 */
(function ($) {
    'use strict';

    /**
     * This function is available and called via $('...').contentifyScroller()
     *
     * @param {object} opts The options / parameters
     */
    $.fn.contentifyScroller = function(opts) 
    {
        // Go through all jQuery-objects the function might have received.
        this.each(function() {
            new ContentifyScroller(this, opts);
        });
    };

    /**
     * Creates an actual scroller instance.
     * 
     * @param {object} el   The scroller DOM element
     * @param {object} opts The options object
     */
    function ContentifyScroller(el, opts)
    {
        this.$el  = $(el);  // Save the DOM element that this plugin was initialized on for further use
        this.defaults = {   // These are the default options
            transitionTime : 500 // 'slow' / 'normal / 'fast' / milliseconds. Used for the transition animation.
        };
        this.opts       = $.extend(this.defaults, opts); // Override the default options (if possible)
        this.itemIndex  = 1; 
        this.scrolling  = false;

        var self = this; // Keep an instance of this widget for further use

        var $ul = self.$el.find('ul');
        var $lis = $ul.find('li');

        $lis.css('width', $lis.first().css('width'));
        var firstItem = $lis.first().clone().addClass('clone'); // Create clone items
        var lastItem = $lis.last().clone().addClass('clone');

        $ul.css('width', $lis.first().width() * ($lis.length + 2));
        $ul.append(firstItem); // Add clone items to the list
        $ul.prepend(lastItem);
        $ul.css('margin-left', -(self.itemIndex * self.$el.width())); // Focus the first "real" item
        $lis = $ul.find('li'); // Update $lis so they contain the clones
        
        // Slide to the left
        $('.to-left').click(function(event) 
        {
            event.preventDefault();

            if (self.scrolling) return;

            self.scrolling = true;
            self.itemIndex -= 1; // Go to the previous (left) item

            $ul.animate(
                {marginLeft: -(self.itemIndex * self.$el.width())}, 
                {duration: self.opts.transitionTime, queue: false, complete: function ()
                {
                    self.scrolling = false;
                    if (self.itemIndex == 0) {
                        self.itemIndex = $lis.length - 2; // Go to the last "real" item
                        $ul.css('margin-left', -(self.itemIndex * self.$el.width()));
                    }
                }}
            );
        });
        
        // Slide to the right
        $('.to-right').click(function(event) 
        {
            event.preventDefault();

            if (self.scrolling) return;

            self.scrolling = true;
            self.itemIndex += 1; // Go to the next (right) item

            $ul.animate(
                {marginLeft: -(self.itemIndex * self.$el.width())}, 
                {duration: self.opts.transitionTime, queue: false, complete: function ()
                {
                    self.scrolling = false;
                    if (self.itemIndex == $lis.length - 1) {
                        self.itemIndex = 1; // Go to the first "real" item
                        $ul.css('margin-left', -(self.itemIndex * self.$el.width()));
                    }
                }}
            );
        });
    }
})(jQuery);