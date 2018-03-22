@if ($cup->start_at->timestamp > time())
    {!! Form::open(['url' => 'admin/cups/participants/'.$cup->id, 'class' => 'form-inline']) !!}
        <select class="form-control" name="participant_id">
            @foreach($newParticipants as $newParticipant)
                <option value="{{ $newParticipant->id }}">{{ $cup->forTeams() ? $newParticipant->title : $newParticipant->username }} (ID: {{$newParticipant->id }})</option>
            @endforeach
        </select>

        <button class="btn btn-default" type="submit">{!! HTML::fontIcon('plus-circle') !!} {{ trans('app.add') }}</button>
    {!! Form::close() !!}
@endif
<br>

@if ($cup->participants)
    <table class="table table-hover">
        <tr>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('cups::checked_in') }}</th>
            <th>{{ trans('app.actions') }}</th>
        </tr>
        @foreach($cup->participants as $participant)
            <tr>
                <td>
                    @if ($cup->forTeams())
                        <a href="{{ url('cups/teams/'.$participant->id.'/'.$participant->slug) }}">{{ $participant->title }}</a>
                    @else
                        <a href="{{ url('users/'.$participant->id.'/'.$participant->slug) }}">{{ $participant->username }}</a>
                    @endif
                </td>
                <td>{!! $participant->pivot->checked_in ? HTML::fontIcon('check') : HTML::fontIcon('times') !!}</td>
                <td>
                    @if ($cup->start_at->timestamp > time())
                        {!! icon_link('trash', trans('app.delete'), url('admin/cups/participants/delete/'.$cup->id.'/'.$participant->id), false, ['data-confirm-delete' => true]) !!}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
@endif