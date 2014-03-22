var baseUrl;

$(document).ready(function()
{
    /*
     * Initizalize meta variables
     */
    baseUrl     = $('meta[name="base-url"]').attr('content');
    csrfToken   = $('meta[name="csrf-token"]').attr('content');

    /*
     * Add CSRF token
     */
    jQuery.ajaxPrefilter(function(options, request, xhr) 
    {
        if (! xhr.crossDomain)  {
            options.data += '&_token=' + csrfToken;
        }
    });
});
