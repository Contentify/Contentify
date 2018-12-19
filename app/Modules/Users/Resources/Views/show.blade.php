<h1 class="page-title">{{ $user->username }}</h1>

<div class="profile-basics row">
    <div class="col-md-8">
        <table class="table horizontal">
            <tbody>
                <tr>
                    <th class="title">{!! trans('app.name') !!}</th>
                    <td>
                        @if ($user->country->icon)
                            {!! HTML::image($user->country->uploadPath().$user->country->icon, $user->country->title) !!}
                        @endif
                        {{ $user->getRealName() }}
                    </td>
                </tr>
                <tr>
                    <th class="title">{!! trans('users::gender') !!}:</th>
                    <td>
                        @if ($user->gender == 0)
                            {!! HTML::fontIcon('genderless') !!} {{ trans('users::unknown') }}
                        @elseif ($user->gender == 1)
                            {!! HTML::fontIcon('venus') !!} {{ trans('users::female') }}
                        @elseif ($user->gender == 2)
                            {!! HTML::fontIcon('mars') !!} {{ trans('users::male') }}
                        @elseif ($user->gender == 3)
                            {!! HTML::fontIcon('genderless') !!} {{ trans('users::other') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="title">{!! trans('users::birthdate') !!}:</th>
                    <td>{{ $user->birthdate }}</td>
                </tr>
                <tr>
                    <th class="title">{!! trans('users::occupation') !!}:</th>
                    <td>{{ $user->occupation }}</td>
                </tr>
                <tr>
                    <th class="title">{!! trans('app.website') !!}:</th>
                    <td>
                        @if(e($user->website))
                            @if(e($user->website))
                                @if (filter_var($user->website, FILTER_VALIDATE_URL))
                                    {!! HTML::link(e($user->website)) !!}
                                @else
                                    {!! HTML::link('//www.'.e($user->website), e($user->website)) !!}
                                @endif
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="title">{!! trans('users::about') !!}:</th>
                    <td>{{ $user->about }}</td>
                </tr>
                @if ($user->cup_points > 0)
                    <tr>
                        <th class="title">{!! trans('app.cup_points') !!}:</th>
                        <td>{{ $user->cup_points }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="details col-md-4">
        @if ($user->image)
            <img src="{!! $user->uploadPath().$user->image !!}" alt="{{ $user->username }}">
        @else
            <img src="{!! asset('img/default/no_user.png') !!}" alt="{{ $user->username }}">
        @endif

        <div class="actions">
            @if (user())
                <a class="btn btn-default" href="{!! url('messages/create/'.$user->username) !!}" title="{!! trans('users::send_msg') !!}">{!! HTML::fontIcon('envelope') !!}</a>
                <a class="btn btn-default" href="{!! url('friends/add/'.$user->id) !!}" title="{!! trans('users::add_friend') !!}" <?php if (user()->id == $user->id or user()->isFriendWith($user->id)) echo 'disabled="disabled"' ?>>{!! HTML::fontIcon('user-plus') !!}</a>
            @endif
            <a class="btn btn-default" href="{!! url('forums/posts/user/'.$user->id.'/'.$user->slug) !!}" title="{!! trans('forums::show_user_posts') !!}">{!! HTML::fontIcon('comment') !!}</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="profile-socials">
            <table class="table horizontal">
                <tbody>
                    <tr>
                        <th class="title">{!! HTML::fontIcon('facebook') !!}&nbsp; Facebook:</th>
                        <td>
                            @if (filter_var($user->facebook, FILTER_VALIDATE_URL))
                                <a href="{{ $user->facebook }}" target="_blank">{{ trans('app.link') }}</a>
                            @else
                                <a href="https://www.facebook.com/{{ $user->facebook }}" target="_blank">{{ $user->facebook }}</a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="title">{!! HTML::fontIcon('twitter') !!}&nbsp; Twitter:</th>
                        <td>
                            @if (filter_var($user->twitter, FILTER_VALIDATE_URL))
                                <a href="{{ $user->twitter }}" target="_blank">{{ trans('app.link') }}</a>
                            @else
                                <a href="https://www.twitter.com/{{ $user->twitter }}" target="_blank">{{ $user->twitter }}</a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="title">{!! HTML::fontIcon('discord') !!}&nbsp; Discord:</th>
                        <td>{{ $user->discord }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! HTML::fontIcon('skype') !!}&nbsp; Skype:</th>
                        <td><a href="skype:35?{{ $user->skype }}" target="_blank">{{ $user->skype }}</a></td>
                    </tr>
                    <tr>
                        <th class="title">{!! HTML::fontIcon('steam') !!}&nbsp; {!! trans('users::steam_id') !!}:</th>
                        <td>
                            @if (filter_var($user->steam_id, FILTER_VALIDATE_URL))
                                <a href="{{ $user->steam_id }}" target="_blank">{{ trans('app.link') }}</a>
                            @else
                                <a href="https://steamcommunity.com/id/{{ $user->steam_id }}" target="_blank">{{ $user->steam_id }}</a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="profile-favs">
            <table class="table horizontal">
                <tbody>
                    <tr>
                        <th class="title">{!! trans('app.object_game') !!}:</th>
                        <td>{{ $user->game }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::food') !!}:</th>
                        <td>{{ $user->food }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::drink') !!}:</th>
                        <td>{{ $user->drink }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::music') !!}:</th>
                        <td>{{ $user->music }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::film') !!}:</th>
                        <td>{{ $user->film }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="profile-pc">
            <table class="table horizontal">
                <tbody>
                    <tr>
                        <th class="title">{!! trans('users::cpu') !!}:</th>
                        <td>{{ $user->cpu }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::graphics') !!}:</th>
                        <td>{{ $user->graphics }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::ram') !!}:</th>
                        <td>{{ $user->ram }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::motherboard') !!}:</th>
                        <td>{{ $user->motherboard }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::drives') !!}:</th>
                        <td>{{ $user->drives }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::display') !!}:</th>
                        <td>{{ $user->display }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::mouse') !!}:</th>
                        <td>{{ $user->mouse }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::keyboard') !!}:</th>
                        <td>{{ $user->keyboard }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::headset') !!}:</th>
                        <td>{{ $user->headset }}</td>
                    </tr>
                    <tr>
                        <th class="title">{!! trans('users::mouse_pad') !!}:</th>
                        <td>{{ $user->mouse_pad }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
