The process of creating a website with Contentify heavily depends on creating the main front layout. This chapter deals with hints for this work step.

## Breadcrumb Navigation

A breadcrumb navigation is a simple navigation that represents the current location inside the navigation hierarchy of the website. For example:

    Home >> News >> We invented a new cookie recipe

The navigation module includes a widget to display a breadcrumb navigation. Add the widget to the frontend layout template (or other templates) to render a breadcrumb navigation:

    @widget('Navi::Breadcrumb', compact('breadcrumb'))

The variable `breadcrumb` is filled by the constrcutor of the base controller. It only contains the name of the current module. To pass trough a more detailed navigation you may use the breadcrumb method provided by the base controller:

    // Inside of a controller inheriting from BaseController:
    $links = ['News' => url('news'), 'We invented a new cookie recipe' => url('news/123')];
    $this->breadcrumb($links);

The rendered links are wrapped into a `<div>` element with the class `breadcrumb-navi`. You are free to change the style of this class.

## Title Tag

The base controller offers a method to set the title for the related template:

    $this->title('Great cookie recipe');

The title is stored in the `title`variable. There's a macro available to render the title:

    {{ HTML::title($title) }}

Controllers may use the title method to assign a custom title to the web page.

## Meta Tags

The base controller offers a method to add meta tags to the related template:

    $this->metaTag('name', 'content');

The tags are store in the `metaTags` variable. It's easy to render these tags inside the template:

    {{ HTML::metaTags($metaTags) }}

> The metaTag method is not aware of tags that are already hard-coded in the template's HTML code. Take care of this behaviour.

## Open Graph Tags

Take a look at [the chapter about Open Graph tags](Open_Graph_Builder).