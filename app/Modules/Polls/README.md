Polls Module
===

- Polls can be "internal", so only logged in members of your organisation are able to see these polls and participate in them
- Polls have one of two states: Open and closed. Only if a poll is open, users are able to vote.
- Only logged in users are able to vote. This ensures a person can only participate once per poll.
- If you limit the votes per user per poll to one, radio buttons will be showed (so only one option can be selected). 
If you limit it to a number greater than one, check boxes will be displayed and multiple selections can be made.
- You may add up to 15 voting options to each poll
- Results of the polls will be cached to increase performance
- _ATTENTION_: We have not been very cautious about validation in the backend. So when administrators create new polls it is possible to create absurd polls, for example with options that only consist of spaces. It is up to you to only create reasonable polls.
