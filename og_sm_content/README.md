# Organic Groups : Site Content

This module provides content management functionality within a Site context.

## Functionality

### Create content within the Site context

Add new content within a Site context:

* `[site-path]/content/add` : Overview of all content types a user can create
  within a Site.
* `[site-path]/content/add/\[node-type]` : Add new content of a specific content
  type.

### TIP: Aliases for node/NID/edit & node/NID/delete

This module does not provide path aliases for `node/NID/edit` and
`node/NID/delete` paths.

Install the [Extended Path Aliases][link-path_alias_xt] module to provide this
functionality.

### Manage content within a Site

Two new Site admin pages are provided by this module:

* `[site-path]/admin/content` : Overview of all content within the Site.
* `[site-path]/admin/content/my` : Preview of all content created by the
  logged-in user.
* Allow users with the Organic Groups "administer site" to alter the authoring
  data (author, published status, last update date).

## Requirements

* Organic Groups Site Manager

## Installation

1. Enable the module.

[link-path_alias_xt]: https://www.drupal.org/project/path_alias_xt
