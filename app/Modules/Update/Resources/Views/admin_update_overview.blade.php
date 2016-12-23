<p>Update started. Process-ID is {{ $threadId }}.</p>

<p>Please wait... Updating since <span id="update-status">0</span> seconds...</p>

<script>
    $(document).ready(function()
    {
        var $span = $('#update-status');

        var callback = function() {
            $.get(contentify.baseUrl + 'admin/update/status', function(data)
            {
                if (data == -1) {
                    window.location.href = contentify.baseUrl + 'admin/update/completed';
                } else {
                    $span.text(data);
                }
            });
        };

        var intervalId = window.setInterval(callback, 2000);        
</script>