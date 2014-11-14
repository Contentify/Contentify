## Slides

If you want to enhance your website with a real eyecatcher a slider is your method of choice. A slider consists of slides. To maintain continuity sliders are called "slide categories" in the backend, so every slide belongs to a slide category. Many JavaScript sliders exist out there but for your convenience Contentify comes with a slider already built-in. Inserting it into a template is a piece of cake:

    @widget('Slides::Slides', ['categoryId' => 1])

`categoryId` is the ID of the corresponding slide category aka the slider. You may add multiple slider widgets to one view. The widget basically adds the `modules/slides/views/widget.blade.php` template to the parent template. That's where the jQuery slider plugin is instanciated, like so:

	$('#slider{{ $categoryId }}').contentifySlider();

It's possible to pass options to it - take a look at `libs/slider.js` for a complete list:

	$('#slider{{ $categoryId }}').contentifySlider({ autoplay: false });

The slider widget comes with a default CSS styling. Don't hesistate to make any changes you want to adapt the styling to your website. It's located in the `frontend.css` stylesheet at the `.slider` class. You probably want to set the slider's height to an appropriate value.

    .slider { position: relative; width: 100%; height: 245px; /* Height of a single slide */ }

## Scrollers

If you just need a UI container with items that can slide horizontally consider to implement a scroller insteaf of a slider. A scroller is a HTML container with a list of items and two scrolling buttons. Clicking on one of the buttons slides to the previous or the next item. Endless sliding in one direction is possible. Example markup:

    <div class="scroller">
        <ul class="layout-h">
            <li>
                Item 1
            </li>
            <li>
                Item 2
            </li>
        </ul>
        <a class="to-left" href="#">&lt;</a>
        <a class="to-right" href="#">&gt;</a>
    </div>

    {{ HTML::script('libs/scroller.js') }}
    <script>
        $(document).ready(function()
        {
            $('.scroller').contentifyScroller();
        });
    </script>

It's possible to display content with a JS scroller instead of a JS slider and vice versa.

## Videos

Every video belongs to a provider. The only default provider is `youtube`. As you might guess, a provider is a platform that hosts videos. The `Modules\Videos\Models\Video` model stores a `$providers` array with available providers. You may add aditional providers to this array. If you do so you have to care for handling videos from these providers by modifying corresponding templates - at least `modules/videos/views/index.blade.php` and `show.blade.php`. You may also take a look at `admin_form.blade.php`.

## Streams

Streams - most likely hosted by [Twitch](http://twitch.tv/) - usually tend to be online or offline and to have at least zero viewers. The `Streams` module can display such information but it needs to frequently update them. Therefore it adds a job to the jobs manager. This job will be executed each time when the job manager is triggered by a cron job and the job is not cooling down. Therefore you need to create a cron job if you want automated updates. Read more at the [GitHub page of the Jobs package](https://github.com/chriskonnertz/Jobs).