{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.streams.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/streams']) !!}
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
         *
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
         * Retrieve the stream ID from a URL such as "https://www.example.com/<id>"
         *
         * @param  {string}         provider    The provider's name
         * @param  {string}         url         The stream URL
         * @return {string|bool}    Returns the ID or false
         */
        function getStreamId(provider, url)
        {
            if (url.indexOf(provider + '.') == -1) {
                return false;
            }

            var pos = url.lastIndexOf('/');
            if (pos === -1) {
                return false;
            }

            var id = url.substr(pos + 1);

            if (id) {
                return id;
            }
            return false;
        }

        $('#url').keyup(function()
        {
            var provider, result;

            if (providers.twitch) {
                provider = 'twitch';
                result = getStreamId(provider , $(this).val());
                if (result !== false) {
                    $('#permanent_id').val(result);
                    selectProvider(provider);
                }
            }

            if (providers.smashcast) {
                provider = 'smashcast';
                result = getStreamId(provider , $(this).val());
                if (result !== false) {
                    $('#permanent_id').val(result);
                    selectProvider(provider);
                }
            }
        });

        $('#permanent_id').keyup(function()
        {
            switch (getProvider()) {
                case 'twitch':
                    $('#url').val('https://www.twitch.tv/' + $(this).val());
                    break;
                case 'smashcast':
                    $('#url').val('https://www.smashcast.tv/' + $(this).val());
                    break;
            }
        });
    });
</script>
