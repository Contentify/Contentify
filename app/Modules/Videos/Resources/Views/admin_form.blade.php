{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.videos.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/videos']) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::smartUrl() !!}

    {!! Form::smartText('permanent_id', trans('app.id')) !!}

    {!! Form::smartSelect('provider', trans('app.provider'), $modelClass::$providers) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}

<script>
    $(document).ready(function()
        {
            // Object with the names of all available providers:
            var providers = {!! json_encode($modelClass::$providers) !!}; 

            /**
             * Selects a provider in the provider UI element
             * @param  {string} provider The providers unique name
             * @return void
             */
            function selectProvider(provider)
            {
                $("#provider option[value='" + provider + "']").attr('selected', true);
            }

            /**
             * Returns the name of the currently selected provider
             * 
             * @return {string} The provider's name
             */
            function getProvider()
            {
                return $('#provider').val();
            }

            /**
             * Get the YouTube video ID from a URL
             * @param  {string}         url The YouTube video URL
             * @return {string|bool}        Returns the ID or false
             */
            function getYoutubeId(url)
            {
                var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
                return (url.match(p)) ? RegExp.$1 : false ;
            }

            /**
             * Get the Vimeo video ID from a URL
             * @param  {string}         url The Vimeo video URL
             * @return {string|bool}        Returns the ID or false
             */
            function getVimeoId(url)
            {
                var p = /https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/;
                return (url.match(p)) ? RegExp.$3 : false ;
            }

            $('#url').keyup(function()
            {
                var result;

                if (providers.vimeo) {
                    result = getVimeoId($(this).val());
                    if (result !== false) {
                        $('#permanent_id').val(result);
                        selectProvider('vimeo');
                    }
                }

                if (providers.youtube) {
                    result = getYoutubeId($(this).val());
                    if (result !== false) {
                        $('#permanent_id').val(result);
                        selectProvider('youtube');
                    }
                }
            });

            $('#permanent_id').keyup(function()
            {
                switch (getProvider()) {
                    case 'youtube':
                        $('#url').val('https://www.youtube.com/watch?v=' + $(this).val());
                        break;
                    case 'vimeo':
                        $('#url').val('https://www.vimeo.com/' + $(this).val());
                        break;
                }
            });
        }
    );
</script>
