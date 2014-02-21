# Widgets

Widgets are little helpers that make it a piece of cake to display certain content on a page. You can add widgets to templates with a clean and pretty syntax:

    <!-- Blade syntax style: -->
    @widget('Auth::Login')

This will render a template including a login form. "Auth" is the name of the module and "login" the name of the widget. You can even pass arguments to a widget:

    @widget('Cookies::Recipes', 3)
    @widget('Cookies::Recipes', ['limit' => 3])

Both widget instances will do the same: They load up to three cookie recipes. You have to use an array as arguemtn to pass more than one value, but if you want to pass only a single argument you do not have to use an array.

As the CMS uses Laravel you should know that widgets are implemented as classes inheriting from the Widget base class. Widget classes live inside the controllers directory. Using widgets is an alternative way to what vanilla Laravel offers: View composers. Unlike view composers we can directly pass arguments to a widget with a clean syntax. A view composer is tied to a single view, but a widget can use as many views as needed.

Creating widgets is simple. Here is an example:

    class ExampleWidget extends Widget {
        public function render($parameters = array())
        {
            return 'Hello world!';
        }
    }

> Don't forget to use "dump-autoload" to update the autoloader!

Widgets implement the render method. They always return a string. Controllers return a response, but widgets do not. It's recommended to never return a view. Instead call the render method:

    return View::make('example')->render();

This may seem like an overhead but it makes debugging easier. If an error occurs inside the view's render method and you do not use render() Laravel will blame "View::__toString() must not throw an exception" instead of explaining what the actual error is.
