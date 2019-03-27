## Changelog - v3

**Important Notes**: 
- PHP >= 7.0 is required!
- We recommend to manually delete all client sessions. Do so by deleting all files in the directory
`storage/famework/sessions`.
- If you use AWS S3 and set the configuration via environment keys, note that their names have been changed 
( [see diff](https://github.com/laravel/laravel/commit/f1253690c5374c42fe54b7336063605380c39d56#diff-7b9241412b3dab19230761bbdde0b3c8) ).

**Changes**
- The global `sort_switcher` helper function has been removed. You may use the `HTML::sortSwitcher()` method instead.
- News entries are now allowed to have an individual image. If no image has been set, the image of the news category will be displayed instead.
- You have now more control over enabling/disabling comments
- Laravel has been updated from v5.4 to 5.5
- Events have been added - if you think an event is missing, please let us know!
- There is a new `install` console command to install Contentify without using the web interface (which is still possible though)
- A lot of refactoring has been done
- Docker update
- PSR-2 code formatting; done programmatically
- TBA...
