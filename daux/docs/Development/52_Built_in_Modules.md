## Slides

If you want to enhance your website with a real eyecatcher a slider is your method of choice. A slider consists of slides. To maintain continuity sliders are called "slide categories" in the backend, so every slide belongs to a slide category. Many JavaScript sliders exist out there but for your convenience Contentify comes with a slider already built-in. Inserting it into a template is a piece of cake:

    @widget('Slides::Slides', ['categoryId' => 1])

`categoryId` is the ID of the corresponding slide category aka the slider. You may add multiple slider widgets to one view. The widget basically adds the `modules/slides/views/widget.blade.php` template to the parent template. That's where the jQuery slider plugin is instanciated, like so:

	$('#slider{{ $categoryId }}').contentifySlider();

It's possible to pass options to it - take a look at `libs/slider.js` for a complete list:

	$('#slider{{ $categoryId }}').contentifySlider({ autoplay: false });

The slider widget comes with a default CSS styling. Don't hesistate to make any changes you want to adapt the styling to your website. It's located in the `frontend.css` stylesheet at the `.slider` class. You probably want to set the slider's height to an appropriate value.

    .slider { position: relative; width: 100%; height: 245px; /* Height of a single slide */ }