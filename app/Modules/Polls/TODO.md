TODO POLLS
===

This is just a quick 'n dirty todo list for the development of the polls module.

- Store each vote in the database to ensure every person can only take part once per poll
- This means that voting is only possible for logged in members
- Maybe: Checkbox "internal" so only members of the organisation can vote?
- Add option: how many votes can pe made for a specific poll? (1-n)
- Up to 15 voting options 
- Which are hardcoded in the polls table, to make development easier
- But only show as many as necessary in the backend edit form
- For better performance, always cache poll results, but clear the cache result whenever someone votes (so the cached result will always be up to date)
- Frontend: add poll widget + poll page
- Use custom style checkboxes/radio buttons in the frontend when voting
- Add "open" flag to the poll model

