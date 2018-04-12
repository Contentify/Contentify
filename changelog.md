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

## Other changes

- Lots of bug fixes
- Backend styling improvements
- Want more? Take a look: https://github.com/Contentify/Contentify/issues/264