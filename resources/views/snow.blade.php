<<<<<<< HEAD
<canvas id="sky" data-color="{{ Config::get('app.theme_snow_color') }}" style="position: fixed;top: 0;left: 0;height: 100%;width: 100%;z-index: 0;"></canvas>
=======
<canvas id="sky" data-color="{{ Config::get('app.theme_snow_color') }}" style="position: fixed;top: 0;left: 0;height: 100%;width: 100%;z-index: 3;"></canvas>
>>>>>>> 322ca8635d31f488b308565495a0e8b0e50cee68
{!! HTML::script('vendor/jsnow/jsnow.js') !!}