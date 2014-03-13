Take a look into `app/helpers.php` for a complete list of the global helper functions Contentify offers.

## User

The user() helper function returns an instance of the current user (of class User) or null if the user is not authenticated. In Contentify speech a user is a user agent that is authenticated (logged in). If a user agent isn't authenticated we refer to it as a visitor.

    // Retrieve the instance:
    $user = user();

    // Check if user is authenticatd:
    if (user()) { 
        echo 'User authenticated!';
    }

