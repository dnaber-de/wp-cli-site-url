# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

(This document follows the strucutre described in http://keepachangelog.com/)

## [Unreleased]

### Breaking changes
* Remove `johnpbloch/wordpress` and `wp-cli/wp-cli` as dependencies as WP-CLI depends on Symfony 2 components which conflicts with many other tools that requires Symfony 3 components
 
### Changed
* Internal: codestyling
* Internal: Pass instance to `WP_CLI::add_command()` instead of class name

## [2.0.0]
### Changed
* Set dependency version of `wp-cli/wp-cli` to `^1.1`

## [1.0.0]
### Added
* Command `wp site-url get <ID>`
* Command `wp site-url update <ID> <URL>`

[Unreleased]: http://github.com//inpsyde/wp-cli-site-url/compare/2.0.0...master
[2.0.0]: https://github.com/inpsyde/wp-cli-site-url/compare/1.0.0...2.0.0
[1.0.0]: https://github.com/inpsyde/wp-cli-site-url/tree/1.0.0
