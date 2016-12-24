<section class="hover-show-match page-matches">
    <div class="overview clearfix">
        <div class="left">
            @include('cups::participant', ['cup' => $match->cup, 'participant' => $match->left_participant, 'images' => true])
        </div>
        <div class="mid">
            {{ $match->left_score }}:{{ $match->right_score }}
        </div>
        <div class="right">
            @include('cups::participant', ['cup' => $match->cup, 'participant' => $match->right_participant, 'images' => true])
        </div>
    </div>

    <p class="text-center">
        <a href="{{ url('cups/matches/'.$match->id) }}" class="btn btn-success">{{ trans('cups::view_match') }}</a>
    </p>
</section>