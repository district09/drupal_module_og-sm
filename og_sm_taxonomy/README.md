# Organic Groups : Site Taxonomy

Module to support sharing a common vocabulary in multiple Sites. Each Site has
its own taxonomy terms.

## Functionality

This module provides:

* Support global vocabularies with Site specific terms.
* Manage terms per Site.
* Select only from terms within the Site when creating content.
* A token provider for terms within a Site (used for path aliases). Only
  available when the **og_sm_path module** is enabled.
* An Organic Groups context handler to get the context by the Sites a taxonomy
  term belongs to.

> **NOTE** : vocabulary terms will be automatically filtered to only those
> related to the current Site context.
> Make sure that you have setup the context detection properly.
> See og_sm_context and og_sm_path modules.

## Requirements

* Organic Groups Site Manager
* Taxonomy

## Installation

1. Enable the module.
1. Create a global vocabulary.
1. Add the Organic Groups audience field to the vocabulary.
1. Grant Organic Groups roles the proper taxonomy permissions.
1. Setup the OG context providers on admin/config/group/context:
    * Enable the "**Site Taxonomy Term**" detection method.

### Configure auto path aliases for terms

The module adds extra tokens for taxonomy paths (only when the og_sm_path module
is also enabled).

1. Configure the alias for content on admin/config/search/path/patterns:
    * Overall or per Vocabulary  : `[term:site-path]/...`.

### TIP: hide the OG audience field

You can hide the OG Audience field when creating/editing Site terms within a
Site context.

* Install the entityreference_prepopulate module and edit the OG Audience field
  of the vocabularies.
* Enable "Entity reference prepopulate".
* Set the action to "Hide field".
* Check "Apply action on edit".
* Enable OG Context as provider and move it to the first position.
* Disable URL as provider.
