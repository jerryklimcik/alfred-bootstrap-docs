# Bootstrap 5 Docs Workflow for Alfred

[Bootstrap docs](https://getbootstrap.com/docs) search workflow for [Alfred 3+](https://www.alfredapp.com).

![Screenshot](screenshot.png)

## Installation

1. [Download the latest version](https://github.com/tillkruss/alfred-laravel-docs/releases/download/v0.3.0/Laravel.Docs.alfredworkflow)
2. Install the workflow by double-clicking the `.alfredworkflow` file
3. You can add the workflow to a category, then click "Import" to finish importing. You'll now see the workflow listed in the left sidebar of your Workflows preferences pane.

## Usage

Just type `bs` followed by your search query.

```
ld <query>
```

Either press `⌘Y` to Quick Look the result, or press `<enter>` to open it in your web browser.

### Note

Heavily inspired by [Alfred-laravel-docs by Till Krüss](https://github.com/tillkruss/alfred-laravel-docs) and big kudos to him.
The lightning fast search is powered by [Algolia](https://www.algolia.com) using the _same_ index as the official [Bootstrap CSS](https://getbootstrap.com) website.
