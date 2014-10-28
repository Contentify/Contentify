{{ Form::errors($errors) }}

@if (isset($model))
    {{ Form::model($model, ['route' => ['admin.streams.update', $model->id], 'method' => 'PUT']) }}
@else
    {{ Form::open(['url' => 'admin/streams']) }}
@endif
    {{ Form::smartText('title', trans('app.title')) }}

    {{ Form::smartText('url', trans('app.url')) }}  

    {{ Form::smartText('permanent_id', trans('app.id')) }}

    {{ Form::smartSelect('provider', trans('app.provider'), $modelClass::$providers); }}

    {{ Form::actions() }}
{{ Form::close() }}

<script>
    $(document).ready(function()
        {   
            // Object with the names of all available providers:
            var providers = {{ json_encode($modelClass::$providers) }}; 

            /**
             * Selects a provider in the provider UI element
             * @param  {string} provider The providers unique name
             * @return void
             */
            function selectProvider(provider)
            {
                $("#provider option[value='" + provider + "']").attr('selected', true);
                $('#provider').selecter('update');
            }

            /**
             * Retursn the name of the currently selected provider
             * 
             * @return {string} The provider's name
             */
            function getProvider()
            {
                return $('#provider').val();
            }

            /**
             * Get the Twitch stream ID from a URL
             * @param  {string}         url The Twitch stream URL
             * @return {string|bool}    Returns the ID or false
             */
            function getTwitchId(url)
            {
                var pos = url.lastIndexOf('/');
                if (pos === -1) return false;

                var id = url.substr(pos + 1);

                if (id) {
                    return id;
                }
                return false;
            }

            $('#url').keyup(function()
            {
                if (providers.twitch) {
                    var result = getTwitchId($(this).val());
                    if (result !== false) {
                        $('#permanent_id').val(result);
                        selectProvider('twitch');
                    }
                }
            });

            $('#permanent_id').keyup(function()
            {
                switch (getProvider()) {
                    case 'twitch':
                        $('#url').val('http://www.twitch.tv/' + $(this).val());
                        break;
                }
            });
        }
    );
</script>