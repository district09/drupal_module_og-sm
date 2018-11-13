# Changelog

All Notable changes to **Organic Groups Site Manager** module suite.

## [7.x-1.16]

### Added

- PPL-1240 : Added permission to access the site structure overview page.

### Fixed

- DMOGSM-139 : Added scrolling to the site admin menu site switcher dropdown if
  the list contains more sites then the screen height.

## [7.x-1.15]

### Added

- PPL-1081 : Added the option to allow users to override the comment settings on
  per site content item.

## [7.x-1.14]

### Added

- DMOGSM-78 : Added the og_sm_user_create module.
- DMOGSM-78 : Make sure the create account tab is the primary tab when
  og_sm_user_create is enabled.
- DMOGSM-111 : Added og_sm_feature_form_defaults and
  og_sm_feature_form_defaults_alter hooks.

### Changed

- DMOGSM-108 : Overwrite Site content types filter handler in og_sm_feature to
  exclude types based on feature dependencies.

## 7.x-1.13

### Changed

- DMOGSM-106 : Added the old & new path values to the 
  hook_og_sm_site_path_change hook.

## 7.x-1.12

### Added

- PPL-957 : Added og_sm_global_roles_user_access function to check global
  permissions within a site context.

### Changed

- DMOGSM-105 : Changed width of the Site items in the admin menu and moved
  the Platform item to the top.

## 7.x-1.11

### Fixed

- PPL-909 : Fixed double encoded username in Views links.

## 7.x-1.10

### Added

- PPL-772 : Added alter hook so we can link to the Site user profile when a
  Site context is active.
- PPL-772 : Added the possibility to override the views in use to show the
  Site content admin overview pages.
- PPL-772 : Added the possibility to override the view in use to show the
  Site comments admin overview page.
- PPL-831 : Added the possibility to override the title on node add forms.

## 7.x-1.9

### Added

- PPL-745 : Added og_sm_feature_feature_info().

### Fixed

- DMOGSM-104 : Fixed notice when called outside Site context.
- PPL-805 : Fixed action link classes to avoid name clashing with other 
  modules & themes.

## 7.x-1.8

### Added

- DMOGSM-90 : Added centralised functionality to provide Site specific menu
  items.

### Removed

- PPL-652 : Added hook_og_sm_ajax_paths_alter to allow modules to register
  ajax paths that needs to be auto-prefixed with the Site path to keep the
  current Site context.
- DMOGSM-90 : Removed the obsolete pg_sm_pathauto module.
- DMOGSM-93 : Added link to the Administration pages overview (fallback to
  Site node edit) on the platform dashboard.
- DMOGSM-98 : Added fallback message when Group content add overview page has
  no items and og_sm_feature module is enabled.

### Updated

- DMOGSM-95 : Improved the help text for the content type comments settings
  form.

### Fixed

- DMOGSM-92 : Fixed order of items on site admin overview pages.
- DMOGSM-100 : Fixed access check to the platform administration page.
- DMOGSM-101 : Fixed notice about passing non-variables by reference.
- DMOGSM-102 : Fixed fallback "file path" to the info retrieved from
  hook_og_sm_menu().

## 7.x-1.7

### Fixed

- DMOGSM-66 : Fixed orphan form submit handlers in feature settings form.

## 7.x-1.6

### Added

- PPL-652 : Added hook_og_sm_ajax_paths_alter.
- PPL-679 : Added og_sm_dashboard module.
- DMOGSM-66 : Automatically clear Site variable cache when an
  og_sm_variable_settings_form is submitted.

## 7.x-1.5

### Added

- DMOGSM-72 : Added site node type machine name to content type name in content
  overview.
- DMOGSM-73 : Added module to support cloning existing Sites.
- DMOGSM-75 : Added configuration to allow anonymous comments per content type.
- PPL-605 : Added option to disable editing of user profiles.
- DMOGSM-87 : Added extra site action hooks (_save, _post_insert, _post_update,
  _post_save, _post_delete).
- DMOGSM-65 : Added static cache for variable calls.
- PPL-679 : Added og_sm_dashboard module.

### Fixed

- DMOGSM-81 : Show/hide breadcrumbs, depending on the settings, on admin paths
  when the user has no access to the admin theme.
- PPL-434: Added the comment/reply/%node path to the comment context handler.
- PPL-448: Trim comment in comments overview to 200 chars.
- DMOGSM-84 : Adjust breadcrumb logic so the site homepage link is always
  included in the breadcrumb.
- DMOGSM-86 : Fixed broken tests introduced in DMOGSM-84.

## 7.x-1.4

### Added

- DMOGSM-62 : Added support to include a node type in multiple features.
- DMOGSM-62 : Add vocabularies to features.
- DMOGSM-64 : Added callback and hook to clear all cache for a specific Site.
- DMOGSM-74 : Added configurable breadcrumbs per Site.
- PPL-493 : Added function and hook to get the path to the Site homepage.
- PPL-493 : Added configuration and to set a custom link on the theme logo.

### Fixed

- DMOGSM-58 : Fixed link to add the first Site vocabulary term.
- DMOGSM-63 : Too strict node access check on group content types.
- DMOGSM-76 : Rewrite term_access query tag alter to loop recursively to find
  vocabularies
- PPL-602 : Fixed user/autocomplete ajax URL within Site context.

### Security

- DMOGSM-67 : If the non-member role (OG role) is granted the permission to
  create certain content types, then the anonymous user also gets access to
  create that content within a Site context.
  Fix added to the og_sm_content_get_types_by_site() function. That function is
  used to check access to the content/add pages within a Site.

## 7.x-1.3

### Added

- DMOGSM-57 : Added extra permissions so Site Users can get granted to delete
  Site terms.
- DMOGSM-5: Added the og_sm_user module to have a user profile per site.

### Fixed

- WEBSECOND-248 : Added Views exposed filters support for og_sm_taxonomy.
- DMOGSM-60 : Added fix for entityreference_prepopulate and Site taxonomy terms
  that are created with hidden group fields.

## 7.x-1.2

### Added

- DMOGSM-12 : Functionality to enable/disable & configure features per Site.

## 7.x-1.1

### Added

- DMOGSM-37 : Added permissions to limit access to change the path of existing
  Sites.
- DMOGSM-41 : Added the og_sm_comment module.
- DMOGSM-13 : Added the og_sm_taxonomy module.
- DMOGSM-12 : Added functionality to define default Site variable values when a
  new Site is created.

### Changed

- DMOGSM-36 : Changed storing the mapping between Global roles and Site roles by
  role name instead of Role ID. Makes it easier to export the mapping using
  features (strongarm).

### Fixed

- DMOGSM-16 : Added support for og_variables to the admin menu.
- DMOGSM-16 : Removed the default OG UI admin overview page as this is replaced
  by the functionality provided by og_sm_admin_menu.

## 7.x-1.0

First stable release of the og_sm module.

### Added

- DMOGSM-2 : Site administration overview.
- DMOGSM-7 : Identify node (types) as being a Site node/type.
- DMOGSM-8 : Site management menu that replaces the Administration menu.
- DMOGSM-9 : Site Access based on Site published status.
- DMOGSM-11 : Added the og_sm_content module to create & manage Site content
  within the Site context.
- DMOGSM-14 : Site variable module to store Site specific configuration.
- DMOGSM-17 : Site content path aliases based on the Site path alias.
- DMOGSM-19 : Added the og_sm_theme module to configure a theme per Site.
- DMOGSM-26 : Added the og_sm_global_roles module to grant users global roles
  when they have a specific Site role (when a Site context is active).
- DMOGSM-27 : Added extra permission and node_access implementation to grant
  Site roles admin access to all Site content.
- DMOGSM-31 : Added extra og_context handler to detect the Site context based on
  the node/\[nid](/\[action]) paths.
- DMOGSM-35 : Added new module og_sm_path that contains all logic about hosting
  a subsite on its own path (eg. `http://domain/[site-path]`).
- DMOGSM-35 : Added OG Context Site Path handler to detect context based on the
  current path (or its alias) starting with a known Site Path value.
- DMOGSM-35 : Added the Site Path field to the extra fields so the placement in
  the Site node forms can be altered.
- DMOGSM-39 : Added access to the authoring data of a node for users with
  "administer site" permission within a Site.
- PPL-310 : Rewrite the paths of ajax callbacks to they get the proper Site
  context.

[7.x-1.16]: https://github.com/digipolisgent/drupal_module_og-sm/compare/7.x-1.15...7.x-1.16
[7.x-1.15]: https://github.com/digipolisgent/drupal_module_og-sm/compare/7.x-1.14...7.x-1.15
[7.x-1.14]: https://github.com/digipolisgent/drupal_module_og-sm/releases/tag/7.x-1.14
[Unreleased]: https://github.com/digipolisgent/drupal_module_og-sm/tree/7.x-1.x