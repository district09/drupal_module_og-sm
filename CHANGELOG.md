# Changelog

All Notable changes to **Organic Groups Site Manager** module suite.

## [2.0.0]

This release adds proper support for Drupal 9.1 and higher, older versions will
not work with this release!

### Changed

- Change minimal PHP version to 7.2.
- Change minimal Drupal version to 9.1.

### Updated

- Update qa-drupal to 1.4.x.
- Update info files.

### Fixed

- Fixe PHPCS, PHPMD & PHPStan issues.
- Fixe deprecations.

## [1.1.0]

### Added

- Add qa-drupal to validate code quality using GrumPHP.
- Add access handling for entity reference fields that cannot reference the
  current site.
- Add support for Paragraphs type permissions.
- Add support for taxonomy with an optional group reference.

### Updated

- Update info files and fix Drupal 9 incompatibility issues.

### Changed

- Change vendor name.

### Fixed

- Fix issues detected by qa-drupal.
- Fix changed AdminNegotiator constructor signature.
- Fix InvalidArgumentException if a node isn't group content.
- Fix deprecated usage of getUserGroups() from the membership manager.
- Fix taxonomy access issues.
- Fix faulty namespace for the DefaultSelection plugin.
- Fix exception while checking field permissions.
- Fix taxonomy access issues.
- Fix usage of deprecated path alias storage service.
- Fix "Call to a member function getAlias() on array" in lookupPathAlias().
- Fix inaccessible terms being shown in the overview.
- Fix compatibility with Admin Toolbar 2.1.
- Fix bugs when content is in multiple sites.

### Removed

- Remove Paragraphs Type Permissions integration.
- Remove incompatible modules.

## [8.x-1.0]

### Updated

- First stable release of Drupal 8 port.

[1.2.0]: https://github.com/digipolisgent/drupal_module_og-sm/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/digipolisgent/drupal_module_og-sm/compare/8.x-1.0...1.1.0
[8.x-1.0]: https://github.com/digipolisgent/drupal_module_og-sm/tree/8.x-1.0
[Unreleased]: https://github.com/digipolisgent/drupal_module_og-sm
