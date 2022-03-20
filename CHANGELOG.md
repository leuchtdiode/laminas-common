# leuchtdiode/laminas-common Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 2.1.4 - 2022-03-20

### Changed

* Bugfix: Forgot constructor in `Common\Db\EntitFilterDeleter`

## 2.1.3 - 2022-03-20

### Changed

* Bufix type hint for `Common\Dto\Mapping::createMultiple()`

## 2.1.2 - 2022-03-20

### Changed

* Removed `JsonModel` as return type for `Common\Action\BaseAction::executeAction()` and added it to `Common\Action\BaseJsonAction::executeAction()` 

## 2.1.1 - 2022-03-20

### Changed

* `Common\Db\Filter\Equals::getParameterName()` is now optional, random string is used if omitted by child class

## 2.1.0 - 2022-03-20

### Changed

* Increased doctrine-orm-module to ^5.1.0

## 2.0.0 - 2022-03-19

This major release breaks backward compatibility as PHP >= 8 require and type hints were added all over the library.

### Added

* Base DataTransferObject (DTO) classes (Interface, Mapping, Provider, etc.)
* Interface for databases entity

### Changed

* [BC] Migrated whole library to PHP
* ObjectToArrayHydratorProperty: Use attributes instead of DocBlock comment
* Randomize parameter in Equals filter