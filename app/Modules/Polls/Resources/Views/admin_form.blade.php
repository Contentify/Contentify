{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.polls.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/polls', 'files' => true]) !!}
@endif
{!! Form::smartText('title', trans('app.title')) !!}

{!! Form::smartCheckbox('open', trans('app.open'), true) !!}

{!! Form::smartCheckbox('internal', trans('app.internal')) !!}

<?php
    // Create range including 0, so the array keys start with 0 and the key of the option with value 1 is 1 instead of 0
    $options = range(0, \App\Modules\Polls\Poll::MAX_OPTIONS);

    // Remove the 0-element
    unset($options[0]);
?>
{!! Form::smartSelect('max_votes', trans('app.max_votes'), $options, 1) !!}

@for ($counter = 1; $counter <= \App\Modules\Polls\Poll::MAX_OPTIONS; $counter++)
    {!! Form::smartText('option'.$counter, trans('app.option').' #'.$counter) !!}
@endfor

{!! Form::actions() !!}
{!! Form::close() !!}

<script>
    (function () {
        var maxOptions = {{ \App\Modules\Polls\Poll::MAX_OPTIONS }};
        var element;

        for (var counter = 1; counter <= maxOptions; counter++) {
            element = document.querySelector('#option' + counter);

            // Initially hide extra options
            if (element.value === '' && counter > 1) {
                element.parentElement.parentElement.classList.add('hidden');
            }

            // Ensure max one empty extra option is visible
            element.addEventListener('keyup', function() {
                for (var counter = maxOptions; counter > 1; counter--) {
                    var elementCurrent = document.querySelector('#option' + counter);
                    var elementAbove = document.querySelector('#option' + (counter - 1));

                    if (elementCurrent.value === '') {
                        elementCurrent.parentElement.parentElement.classList.add('hidden');
                    }
                    if (elementAbove.value !== '') {
                        elementCurrent.parentElement.parentElement.classList.remove('hidden');
                        break;
                    }
                }
            });
        }
    })();
</script>