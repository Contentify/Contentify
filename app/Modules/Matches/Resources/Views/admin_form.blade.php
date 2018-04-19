{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.matches.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/matches', 'files' => true]) !!}
@endif
    {!! Form::smartSelect('state', trans('app.state'), $modelClass::$states) !!}

    {!! Form::smartSelectRelation('leftTeam', trans('matches::left_team'), $modelClass) !!}

    {!! Form::smartSelectRelation('rightTeam', trans('matches::right_team'), $modelClass) !!}

    {!! Form::smartSelectRelation('game', trans('app.object_game'), $modelClass) !!}

    {!! Form::smartSelectRelation('tournament', trans('app.object_tournament'), $modelClass) !!}

    {!! Form::smartUrl() !!}

    {!! Form::smartUrl('broadcast', trans('matches::broadcast')) !!}

    {!! Form::smartText('left_lineup', trans('matches::left_lineup')) !!}

    {!! Form::smartText('right_lineup', trans('matches::right_lineup')) !!}

    {!! Form::smartTextarea('text', trans('app.description'), true) !!}

    {!! Form::smartDateTime('played_at', trans('matches::played_at')) !!}
    
    {!! Form::smartCheckbox('featured', trans('app.featured')) !!}

    <!-- We can't add scores to a match that doesn't actually exist -->
    @if (isset($model))
        {!! Form::smartGroupOpen(null, trans('app.object_match_scores')) !!}
            <div class="scores">
                @foreach ($model->match_scores as $matchScore)
                    @include('matches::admin_map', compact('matchScore'))
                @endforeach

                <span class="add-new">+</span>
            </div>
        {!! Form::smartGroupClose() !!}
    @endif

    {!! Form::actions() !!}
{!! Form::close() !!}

<script>
    $(document).ready(function()
    {
        var leftTeamLineup = function()
        {
            var id = $(this).val();
            $.get(contentify.baseUrl + 'admin/teams/' + id + '/lineup', function(data)
            {
                $('#left_lineup').val(data);
            });
        };

        $('select#_relation_leftTeam').change(leftTeamLineup);

        $('select#_relation_rightTeam').change(function ()
        {
            var id = $(this).val();
            $.get(contentify.baseUrl + 'admin/opponents/' + id + '/lineup', function(data)
            {
                $('#right_lineup').val(data);
            });
        });

        @if (! isset($model))
            leftTeamLineup.call($('select#_relation_leftTeam'));
        @endif
        
        // We can't add scores to a match that doesn't actually exist
        @if (isset($model))
            var template = '{!! Form::smartSelectForeign('map_id', trans('app.object_map')) !!} {!! Form::smartGroupOpen('left_score', trans('matches::score')) !!} <input type="text" name="left_score" style="display: inline-block; max-width: 50px" value="%%scoreLeft%%"> : <input type="text" name="right_score" style="display: inline-block; max-width: 50px" value="%%scoreRight%%"> {!! Form::smartGroupClose() !!}';

            contentify.templateManager.add('mapForm', template);

            $('.scores .add-new').click(function()
            {
                var $el = $(this);
                var compiled = contentify.templateManager.get('mapForm', {scoreLeft: 0, scoreRight: 0});

                var $footer = $('<button>').text('{!! trans('app.save') !!}').click(function()
                {
                    $.ajax({
                        url: contentify.baseUrl + 'admin/matches/scores/store',
                        type: 'POST',
                        data: {
                            match_id:       "{!! $model->id !!}",
                            map_id:         $('#map_id').val(),
                            left_score:     $('input[name=left_score]').val(),
                            right_score:    $('input[name=right_score]').val(),
                        }
                    }).done(function(data) 
                    {
                        $el.before(data);
                    }).fail(function(response)
                    {
                        contentify.alertRequestFailed(response);
                    });
                    contentify.closeModal();
                });

                contentify.modal('{{ trans('app.object_map') }}', compiled, $footer);
            });

            $('.page').on('click', '.scores .item', function()
            {
                var $el = $(this);
                var id = $el.attr('data-id');

                var compiled = contentify.templateManager.get('mapForm', 
                    {scoreLeft: $el.attr('data-left-score'), scoreRight: $el.attr('data-right-score')});

                var $compiled = $(compiled);
                $compiled.find('select').val($el.attr('data-map-id'));

                var $footer = $('<div>').append(
                    $('<button>').text('{!! trans('app.save') !!}').click(function()
                    {
                        $.ajax({
                            url: contentify.baseUrl + 'admin/matches/scores/' + $el.attr('data-id'),
                            type: 'PUT',
                            data: {
                                map_id:         $('#map_id').val(),
                                left_score:     $('input[name=left_score]').val(),
                                right_score:    $('input[name=right_score]').val(),
                            }
                        }).done(function(data) 
                        {
                            $el.replaceWith(data);
                        }).fail(function(response)
                        {
                            contentify.alertRequestFailed(response);
                        });
                        contentify.closeModal();
                    })
                ).append(
                    $('<button>').text('{!! trans('app.delete') !!}').click(function()
                    {
                        $.ajax({
                            url: contentify.baseUrl + 'admin/matches/scores/' + id,
                            type: 'DELETE'
                        }).done(function(data) 
                        {
                            $el.remove();
                        }).fail(function(response)
                        {
                            contentify.alertRequestFailed(response);
                        });
                        contentify.closeModal();
                    })
                );

                contentify.modal('{{ trans('app.object_map') }}', $compiled, $footer);
            });
        @endif

    });
</script>