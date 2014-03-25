The Open Graph protocol is supported by Facebook and Google. It aims to make wep pages objects in a social graph. Clients are able to get additional information about the content of a web page. This is not completly new since properties such as the title are already existing (title meta tag). But the Open Graph protocol provides more details.

Contentify brings in the Open Graph (builder) class. It's a helper to create these tags. The example shows a method of a controller.

    $og = new OpenGraph();

    $og->title($this->title)
        ->type('article')
        ->image('uploads/newscats/'.$this->newscat->image)
        ->description($this->intro)
        ->url();

    $this->openGraph($og);

Render these tags in a template as follows:

    {{ HTML::openGraphTags($openGraph) }}

Providing Open Graph tags enriches web pages. The downside is some extra time to spend, because every model has its own way to generate these tags. It's also important to follow the [official protocol](http://ogp.me/). Read the documentation to learn more about the tags that are available and the values they support or [check out examples](https://github.com/niallkennedy/open-graph-protocol-examples). Please note that this implementation sticks to the specification of OGP.me and does not support the enhancements created by Facebook.

## Add Tags And Attributes

### Add Basic Tags

    $og->title('Apple Cookie')
        ->type('article')
        ->description('A delicious recipe')
        ->url()
        ->locale('en_US')
        ->localeAlternate(['en_UK'])
        ->siteName('Cookie Recipes Website')
        ->determiner('an');

> If no argument is passed to the `url` method the current URL is applied.

### Add Tags With Attributes

    $og->image($imageUrl, [
            'width'     => 300,
            'height'    => 200
        ]);

    $og->audio($audioUrl, [
            'type'     => 'audio/mpeg'
        ]);

    $og->video($videoUrl, [
            'width'     => 300,
            'height'    => 200,
            'type'      => 'application/x-shockwave-flash'
        ]);

### Add Type Attributes

Some object types (determined by the `type` tag) have their own attributes.

    $og->article([
        'author'        => 'Jane Doe'
    ]);

    $og->book([
        'author'        => 'John Doe'
    ]);

    $og->profile([
        'first_name'    => 'Kim'
        'last_name'     => 'Doe'
    ]);

### Add Attributes

Facebook supports more than just the basic object types. To add attributes for off-the-record object types you may use the `attributes`method.

Without custom validation rule:

    $og->attributes('product', ['product:color' => 'red']);

With custom validation rule:

    $og->attributes('product', ['product:color' => 'red'], ['product:color']);

The only validation this method performs is to check if all attribute names match with the list of attribute names.

### Add A Tag Several Times

A property can have multiple values. Add the tag several times to achieve this effect.

    $og->image('http://example.org/apple.jpg')
        ->image('http://example.org/tree.jpg');

> Adding a basic tag a second time will override the value of the first tag. Basic tags must not exist several times.

## Validation

If validation is enabled (default is disabled) adding tags will trigger validation. Validation is not covering the complete specification but some important parts. If validation fails the method will throw an exception.

Validation checks if tag values are legit and if attribute types are known.

Enable validation by method:

    $og->validate();

By constructor:

    $og = new OpenGraph(true);

Disable validation:

    $og->validate(false);

## Miscellaneous

### Determine If A Tag Exists

    $hasTitle = $og->has('title');

### Remove Tag From The List

    $og->forget('title');

### Add A Custom Tag

    $og->tag('apples', 7);

### Get The Last Tag (By Name)

    $tag = $og->lastTag('image');
    $value = $tag['value'];

> Tags are stored as arrays consisting of name-value-pairs.