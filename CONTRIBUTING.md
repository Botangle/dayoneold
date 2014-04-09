# How to work with our setup

## Code Formatting
We use PSR-2 as the base standard for code formatting at this point.  Older code may not be formatted that way, but all
new code going in should have that style applied in your editor.  Pull requests may get rejected (or need further updates)
if these styles aren't applied.  Details and samples available here:
[https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#11-example](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#11-example)

When updating formatting in files you are working on, please do so all in one commit and make the formatting change
  separate from any other changes you are making.  Otherwise deciphering what you've been doing is really painful.

## Line Endings
We intend to have all files end in LF (Unix-style line endings).  Pull requests with different line endings will
need to be updated to the correct settings before we can merge them.  More details available here:
[https://help.github.com/articles/dealing-with-line-endings](https://help.github.com/articles/dealing-with-line-endings)

## Branching model
We use the Gitflow branching model, described here: http://nvie.com/posts/a-successful-git-branching-model/

All bug fixes / feature requests should be based off the development branch unless your fix is a hotfix for production.

## Workflow model
We use the Integration Manager workflow described here: http://git-scm.com/book/en/Distributed-Git-Distributed-Workflows

You should fork our main repo and submit pull requests from your repository.  This keeps things cleaner and prevents
major issues if someone accidentally rebases something wrong ;-)

## Editor
If you're looking for a good IDE, we'd suggest PhpStorm.  It's not free, but it's one of the best-known out there
and reliably keeps up with the PHP community as it grows and improves. [http://www.jetbrains.com/phpstorm/](http://www.jetbrains.com/phpstorm/)