<div class="boxer-plain">
    <select>
        @foreach ($teams as $team)
            <option value="{{ $team->id }}">
                {{ $team->title }}
            </option>
        @endforeach
    </select>
<div>
<br>