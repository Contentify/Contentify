{{ Form::errors($errors) }}

@if (isset($user))
{{ Form::model($user, ['route' => ['users.update', $user->id], 'files' => true, 'method' => 'PUT']) }}
@else
{{ Form::open(['url' => 'users']) }}
@endif
    {{ Form::smartText('username', trans('users::username')) }}

    {{ Form::smartEmail('email', trans('users::email')) }}

    {{ Form::smartGroupOpen(trans('users::password')) }}
        {{ button(trans('users::change'), url('users/'.$user->id.'/password')) }}
    {{ Form::smartGroupClose() }}

    {{ Form::smartText('first_name', trans('users::first_name')) }}
    {{ Form::smartText('last_name', trans('users::last_name')) }}
    {{ Form::smartGroupOpen(trans('users::gender')) }}
        {{ Form::select('gender', array('0' => trans('users::unknown'), '1' => trans('users::female'), '2' => trans('users::male'), '3' => trans('users::other'))) }}
    {{ Form::smartGroupClose() }}
    {{ Form::smartSelectForeign('country_id', trans('users::country')) }}
    {{ Form::smartSelectForeign('language_id', trans('users::language')) }}
    {{ Form::smartText('birthdate', trans('users::birthdate')) }}
    {{ Form::smartText('occupation', trans('users::occupation')) }}
    {{ Form::smartText('website', trans('users::website')) }}

    {{ Form::smartText('skype', trans('users::skype')) }}
    {{ Form::smartText('steam_id', trans('users::steam_id')) }}

    {{ Form::smartText('cpu', trans('users::cpu')) }}
    {{ Form::smartText('graphics', trans('users::graphics')) }}
    {{ Form::smartText('ram', trans('users::ram')) }}
    {{ Form::smartText('motherboard', trans('users::motherboard')) }}
    {{ Form::smartText('drives', trans('users::drives')) }}
    {{ Form::smartText('display', trans('users::display')) }}
    {{ Form::smartText('mouse', trans('users::mouse')) }}
    {{ Form::smartText('keyboard', trans('users::keyboard')) }}
    {{ Form::smartText('headset', trans('users::headset')) }}
    {{ Form::smartText('mousepad', trans('users::mousepad')) }}

    {{ Form::smartText('game', trans('users::game')) }}
    {{ Form::smartText('food', trans('users::food')) }}
    {{ Form::smartText('drink', trans('users::drink')) }}
    {{ Form::smartText('music', trans('users::music')) }}
    {{ Form::smartText('film', trans('users::film')) }}

    {{ Form::smartTextarea('about', trans('users::about')) }}

    {{ Form::smartImageFile('image', trans('users::image')) }}
    {{ Form::smartImageFile('avatar', trans('users::avatar')) }}
   
    {{ Form::actions(['submit' => trans('app.update')]) }}
{{ Form::close() }}