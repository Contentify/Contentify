<h1 class="page-title">{{ trans('app.object_questions') }}</h1>

<div class="questions clearfix">
    @foreach ($questions as $question)
        <div id="question_{{ $question->id }}" class="question">
            <h3 class="title">{{ $question->title }}</h3>

            <div class="text">{!! $question->answer !!}</div>
        </div>
    @endforeach
</div>

{!! $questions->render() !!}