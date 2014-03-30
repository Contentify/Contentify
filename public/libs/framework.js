var contentify;

$(document).ready(function()
{
    function Contentify() {
        var frameWork = this;

        /*
         * Initizalize meta variables
         */
        this.baseUrl     = $('meta[name="base-url"]').attr('content');
        this.csrfToken   = $('meta[name="csrf-token"]').attr('content');

        /*
         * Add CSRF token
         */
        jQuery.ajaxPrefilter(function(options, request, xhr) 
        {
            if (! xhr.crossDomain)  {
                options.data += '&_token=' + frameWork.csrfToken;
            }
        });

        /*
         * Numeric inputs
         */
        $('.numeric-input').keypress(function()
        {
            if (! isNaN($(this).val())) {
                $(this).attr('valid-value', $(this).val());
            }
        }).focusout(function()
        {
            if (isNaN($(this).val())) {
                var valid = $(this).attr('valid-value');

                if (valid) {
                    $(this).val(valid);
                } else {
                    $(this).val(0);
                }
            }
        });

        /*
         * Add delete confirm dialogue
         */
        $('body').append($('<div />').boxer({})); // Bugfix or Boxer won't open due to a JS error
        $('*[data-confirm-delete]').click(function(event)
        {
            event.preventDefault();
            event.stopPropagation();

            var $self = $(this);

            var $ui = $('<div class="boxer-confirm"><h2>Delete this item?</h2></div>')
                .append(
                    $('<button>').text('Yes').click(function()
                    {
                        window.location = $self.attr('href');
                    })
                ).append(
                    $('<button>').text('No').click(function()
                    {
                        $.boxer('close');
                    })
                );
            $.boxer($ui);
        });

        /*
         * Add confirm dialogue
         */
        $('*[data-confirm]').click(function(event)
        {       
            event.preventDefault();
            event.stopPropagation();

            var $self = $(this);
            var message = $self.attr('data-confirm');

            var $ui = $('<div class="boxer-confirm"><h2>Execute this action?</h2><p>' + message + '</p></div>')
                .append(
                    $('<button>').text('Yes').click(function()
                    {
                        window.location = $self.attr('href');
                    })
                ).append(
                    $('<button>').text('No').click(function()
                    {
                        $.boxer('close');
                    })
                );
            $.boxer($ui);
        });    

    };

    contentify = new Contentify();

    console.log(contentify.baseUrl);
});