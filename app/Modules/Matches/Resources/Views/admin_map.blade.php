<span class="item" data-id="{!! $matchScore->id !!}" data-map-id="{!! $matchScore->map->id !!}" data-left-score="{!! $matchScore->left_score !!}" data-right-score="{!! $matchScore->right_score !!}">
    @if ($matchScore->map->image)
        <img src="{!! $matchScore->map->uploadPath().'16/'.$matchScore->map->image !!}" alt="Icon">
    @endif
    
    {!! $matchScore->map->title !!}: <span class="score">{!! $matchScore->left_score !!}:{!! $matchScore->right_score !!}</span>
</span>