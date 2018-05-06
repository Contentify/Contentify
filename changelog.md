## Changelog - v2.4-dev

Welcome! We finally added a `changelog.md` file - so basically this is the first change to be mentioned in this file.
 
## Breaking changes

- We updated the underlying framework, Laravel, from version 5.3 to 5.4. Updates of Laravel tend to cause bugs.
We hope there are none but chances are we did not find them all.
- Internet Explorer below 9 (especially 6-8) is no longer supported **at all**. 
Yes, before version 2.4 we supported good old IE down to version 6. 
Well, at least some how, we did not do any testing with these old version but we still had jQuery 1.x 
plus the HTML5 Shiv lib loaded. This stops now. We updated to jQuery 2 - which is outdated as well 
but we want a smooth shift to jQuery 3 instead of causing too much trouble in v2.4. 
We also dropped HTML5 shiv for good.
- Hitbox has been deprecated since version 2.3. With 2.4 support has been removed at all. 
The update script is able to migrate your old Hitbox streams to Smashcast. However, please check them!
- The names of several variables in the LESS files have been renamed. 
They use [kebap case](http://wiki.c2.com/?KebabCase) instead now.

## Other changes

- New FAQ module
- New cash flows module
- Lots of Backend UI and UX improvements
- Now users can click on table headers to sort table columns
- Added support for these social links: Twitch, Instagram, Discord
- Theme (CSS) compiling now works without a JS task runner
- Made website title editable in the admin backend
- If a user accounts is created via Steam, username and email now have to be set
- Forum moderators now have a badge
- Added Steam login button to login page
- Added language switcher widget (with flags to click)
- Improved visuals and UX of form error messages
- Added missing model type annotations (e.g. created_at)
- Fixed color in date time picker (in the backend) for years & months
- Added preview images for themes
- Refactoring
- Lots of bug fixes