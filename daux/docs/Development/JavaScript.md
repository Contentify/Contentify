> This chapter deals with a part of the frontend development. The term "frontend" is ambiguous. If we talk about the frontend we refer to the frontend interface - in contrast to the backend (admin) interface. Therefore this chapter is named "JavaScript" instead of "Frontend".

The JavaScript files are located in the `libs` directory. Include a JS script to a Blade template like so:

    {{ HTML::script('libs/frontend.js') }}

You should at least include jQuery, html5shiv (for Internet Explorer support), and the Contentify framework. The Contentify framework script relies on jQuery. It provides access to useful variables and methods such as:

* *baseUrl*: The base URL of the website, e. g. `http://localhost.com/contentify/`
* *assetUrl*: The URL of websites assets (the public directory of the website), e. g. `http://localhost.com/contentify/` As can bee seen, `baseUrl` and `assetUrl` are most likely the same - but there is no guarantee so you should use `assetUrl` for assets.
* *locale*: The locale of the current user, e. g. `en`
* *date-format*: The localised date format of the current user, e. g. `d.m.Y`
* *formatDate*: A method that can format dates. Example: `contentify.formatDate(new Date(), contentify.dateFormat)`
* *templateManager*: A very simple template manager. Add templates that include variables and retrieve them with those variables rendered. Examples: `contentify.templateManager.add('example', '<div>%%var&&</div>')` adds a template and `contentify.templateManager.get('example', {var: 'Hello World!'})` retrieves it
* *alert*: Creates a UI alert in [Bootstrap style](http://getbootstrap.com/components/#alerts). Example: `contentify.alert('success', 'This is an example alert!')` or shorter `contentify.alertSuccess('This is an example alert!')`
* Adds support for text form fields that have the `numeric-input` class. These fields will only accept numbers but they won't show special controls the way input fields with the type attribute set to "numeric" do.
* Adds support for Boxer if Boxer is available. Boxer is part of the [Formstone UI elements](http://formstone.it) and a simple modal overlay. Example: `$.boxer($('<div class="boxer-plain">Hello world!</div>'))`
* Adds support for spoilers
* Adds support for filter UI elements


It also automatically adds the CSRF token to all AJAX requests.