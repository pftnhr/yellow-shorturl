# Shorturl 0.9.1

Converts links into short URLs with the help of YOURLS

(Since you can't see anything, there is no screenshot.)

## How to install the extension

[Download ZIP file](https://github.com/pftnhr/yellow-shorturl/archive/refs/heads/main.zip) and copy it into your `system/extensions` folder. [Learn more about extensions](https://github.com/annaesvensson/yellow-update).

A self-hosted [YOURLS](https://github.com/YOURLS) installation and the [yourls-api-edit-url](https://github.com/timcrockford/yourls-api-edit-url) plugin are required.

## How to convert links into short URLs

Insert `<?php echo $page->parseContentElement("shorturl", "", "", "inline") ?>` at the desired position in the href attribute and let the magic happen.

## Examples

    <a title="Short URL to this post" aria-label="Short URL to <?php echo $page->getHtml("title") ?>" href="<?php echo $page->parseContentElement("shorturl", "", "", "inline") ?>">#</a>

## Settings

The following setting can be configured in file `system/extensions/yellow-system.ini`:

    ShorturlApi: <your YOURLS>/yourls-api.php
    ShorturlSecret: secret signature token from <your YOURLS>/admin/tools.php

## Acknowledgements

This extension uses [YOURLS](https://github.com/YOURLS) with the [yourls-api-edit-url](https://github.com/timcrockford/yourls-api-edit-url) plugin. Many thanks to [Ozh](https://github.com/ozh) and [Tim](https://github.com/timcrockford) for the great work!            


## Developer

Robert Pfotenhauer. [Get help](https://datenstrom.se/yellow/help/).

