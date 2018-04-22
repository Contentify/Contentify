<h1 class="page-title">{{ trans('app.object_questions') }}</h1>

<div class="questions clearfix">
    <?php $category = null ?>
    @foreach ($questions as $question)
        @if ($question->questionCat != $category)
            <?php $category = $question->questionCat ?>
            <h2 class="category-title">{{ $category->title }}</h2>
        @endif
        <div id="question_{{ $question->id }}" class="question">
            <h3 class="title">{{ $question->title }}</h3>

            <div class="text">{!! $question->answer !!}</div>
        </div>
    @endforeach
</div>

{!! $questions->render() !!}