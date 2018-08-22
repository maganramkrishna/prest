# Change Log

All notable changes to Prest will be documented in the version
specific changelog file.

The format is based on [Keep a Changelog][keep-cl] and this project adheres
to [Semantic Versioning][semver] .

## [Unreleased]

## [3.6.0] - 2017-10-14
### Fixed
- Fixed error code in case of failed to authenticate with use of provided identity

### Added
- Allow override login constants during to extend `Prest\Auth\Manager`
- Added ability to pass middleware priority by using `Api::attach`
- Added ability to add stack trace to error response

### Changed
- Improved `ResourceController`:
  - The `ResourceController::getTransformer` always return a `Transformer` instance
  - All response aware methods guaranteed to receive item key and transformer
  - The metadata of all response aware methods always is array
- Update `CrudResourceController::getFindData` to prevent throw error when resource isn't instance of `Prest\Api\Resource`
- Changed prototype for `CrudResourceController`'s methods to expect `ModelInterface` instead of `Model`:
  - `createItem`
  - `beforeAssignData`
  - `afterAssignData`
  - `beforeSave`
  - `beforeCreate`
  - `afterCreate`
  - `afterSave`

## [3.5.0] - 2017-10-01
### Changed
- Merged projects `Papil` and `Prest` into one
- The `AuthorizationMiddleware` now throws `AuthorizationException` instead of `DomainException`
- Reduced Cyclomatic Complexity
- The `Prest\Auth\Manager::getAccountType` will throw  `AuthException` if desired type does not exist

## [3.4.0] - 2017-09-30
### Fixed
- Fixed `FractalMiddleware::__construct` to enable parse includes by default
- Fixed throwing domain exceptions
- Changed Packagist name from `preferans/phalcon-rest` to `preferans/prest`

### Changed
- Renamed namespace `PhalconRest` to `Prest`
- Renamed `ApiCollection` to `Collection`
- Renamed `ApiEndpoint` to `Endpoint`
- Renamed `ApiResource` to `Resource`
- Move all `beforeHandle*` and `afterHandle*` methods from `CrudResourceController` to the `HandleTrait`
- All middleware has a common form and explicitly return boolean

## [3.3.0] - 2017-09-28
### Added
- Added support of code style check on each PR and integration with:
  - PHP Code Sniffer
  - PHP Mess detector
  - PHP Code Sniffer
  - PHP Static Analysis Tool

### Changed
- Used latest Phalcon API version
- All the code reflect changes from the new Phalcon API library

### Added
- Introduced domain exceptions to throw more specific errors
- Introduced `PhalconRest\Di\InjectableTrait` to use for classes which can't extend Injectable

### Fixed
- Fixed code style to follow PSR2

## [3.2.4] - 2017-09-26
### Fixed
- Fixed `ModelTransformer::transform`

## [3.2.3] - 2017-09-26
### Removed
- Simplified FractalController. Removed `PhalconRest\Mvc\Controllers\FractalController::createResponse`

## [3.2.2] - 2017-09-26
### Changed
- Completely transfer library to Preferans org

## [3.2.1] - 2017-09-25
### Fixed
- Fixed `PhqlQueryParser::applyQuery`

## [3.2.0] - 2017-09-25
### Added
- Introduced `PhalconRest\Mvc\Model\FillableInterface`

## [3.1.3] - 2017-09-24
### Changed
- Used latest Phalcon API version

## [3.1.2] - 2017-09-23
### Added
- `ApiCollection`: Added collection endpoint methods: `HEAD`and `PATCH`

### Fixed
- `CrudResourceController`: Fixed after read hook
- `ApiCollection`: Fixed endpoint empty name overwriting

## [3.1.1] - 2017-09-23
### Changed
- Move suggested libraries to the required

## [3.1.0] - 2017-09-23
### Changed
- Used latest Phalcon API version

## [3.0.0] - 2017-09-23
### Changed
- Changed project requirements:
  - PHP >= `7.0`
  - Phalcon >= `3.2.0`
  - `league/fractal` >= `0.17`
  - `firebase/php-jwt` >= `5.0.0`

## [2.1.0] - 2017-09-23
### Added
- `API`: Introduced methods to get matched resource / endpoint

## [2.0.0] - 2016-11-18
### Changed
- Changed requirements: PHP >= `5.4`

### Removed
- Removed a lot of code from `PhalconRest` in favor of `PhalconApi`

## [1.5.0] - 2016-10-10
### Changed
- Changed requirements: PHP >= `5.5` and changes to support PHP < `5.6`
- `PhalconRest\Api\Collection`: Pass roles as arguments, Used func_get_args() internally

### Added
- New JSON contains and fix query
- `PhqlQueryParser`: New JSON contains
- Support for PUT method for `CrudResourceController::update`

### Fixed
- Fixed Query syntax. Now you can Used `?fields=` with `PhqlQueryParser`

## [1.4.2] - 2016-07-13
### Fixed
- Add endpoint name as key on mounting to collection

## [1.4.1] - 2016-06-23
### Fixed
- Fixed `PhalconRest\Data\Query\QueryParsers\UrlQueryParser::createQuery`

## [1.4.0] - 2016-06-17
### Changed
- Response usable without error helper in DI

### Added
- `CrudResourceController`: whitelist functions
- `UrlQueryParser` can be configured to enable or disable features

### Fixed
- `PhqlQueryParser` fixes
- Fixes PHP 7 Resource confusion

## [1.3.1] - 2016-03-22
### Added
- Introduced `MiddlewareInterface`

### Removed
- Removed version from `composer.json`

## [1.3.0] - 2016-03-20
### Changed
- Renamed single/multiple key to item/collection key
- Refactored `PhalconRest\Api\Collection` collection class
- Documentations transformers extend from base transformer
- Decouple documentation transformers from CustomSerializer
- Moved default error messages to error helper
- `Response`: Exception message has priority above default message

### Added
- Added `PhalconRest\Helpers\FormatHelper`
- Easy access to DI from transformers

### Fixed
- `Resource`: Don't overwrite configuration done in initialize method
- `JWTTokenParser`: Expiry fix

## [1.2.2] - 2016-03-06
### Fixed
- `JWTTokenParser`: Expiry fix

## [1.2.1] - 2016-02-28
### Fixed
- Fixes in `FactoryDefault` related to registering services

## [1.2.0] - 2016-02-28
### Changed
- Changed requirements: PHP >= `5.6`
- `PhalconRest\Auth\Manager::authenticateToken` now throws `PhalconRest\Exception\Exception` on failed authentication
- `CrudResourceController`: Model refreshed in a safer way
- Resources/Endpoints can set an expected post data method
- Simplified function names

### Added
- Support for multiple result keys in `CrudResourceController`
- `CrudResourceController`: Add more hooks
- `PostedDataMethod`: setting on request
- Added `PhalconRest\Exception::getInfo`
- `Documentation`: Added example response
- `CrudResource`: Allow hooks
- Introduced `User` Service
- Base controller gets API attached
- Introduced `PhalconRest\Acl\Helper`
- Added `PhalconRest\Api::getMatchedRouteNamePart`, `PhalconRest\Api::getMatchedResource`, `PhalconRest\Api::getMatchedEndpoint`
- `Api`: Shortcut function to attach middleware
- Introduced `PhalconRest\Api`

### Removed
- Removed from `CrudResourceController`:
  - `getFindData`
  - `_getMessages`
- Removed `PhalconRest\Acl\Role`, `PhalconRest\Acl\Service`
- Removed API service & model from query
- Removed redundant `PhalconRest\Data\Query::$options` property

### Fixed
- Bug fixes in CORS/Options middleware
- Bug fixes in `ModelTransformer`

## [1.1.9] - 2016-02-27
### Changed
- Update `composer.json` for version
- Amended `PhalconRest\Api\Resource`:
  - Added `Resource::getModelPrimaryKey`
  - Added `Resource::getController`
  - Added `Resource::setController`
  - Added `Resource::getEndpoints`
  - Added `Resource::setEndpoints`
  - Added `Resource::getSingleKey`
  - Added `Resource::setSingleKey`
  - Added `Resource::getMultipleKey`
  - Added `Resource::setMultipleKey`
- Moved `PhalconRest\Data\Query\Query` to  `PhalconRest\Data\Query`

### Added
- Introduced `PhalconRest\Collection\Http`
- Introduced `PhalconRest\Api\Resource\Crud`
- Introduced `PhalconRest\Middleware\Cors`
- Introduced `PhalconRest\Mvc\Model`
- Implemented `PhalconRest\Transformer\Model`
- Added:
  - `PhalconRest\Api\Endpoint`
  - `PhalconRest\Api\Endpoint\All`
  - `PhalconRest\Api\Endpoint\Create`
  - `PhalconRest\Api\Endpoint\Delete`
  - `PhalconRest\Api\Endpoint\Find`
  - `PhalconRest\Api\Endpoint\Update`
- Added new controllers:
  - `PhalconRest\Mvc\Controller\ResourceCrud`
- Amended `PhalconRest\Api\Service` by adding:
  - `Service::setResource`
  - `Service::removeResource`
  - `Service::setResources`

### Removed
- Removed `PhalconRest\Collection\ResourceCollection`

## [1.1.8] - 2015-12-16
### Changed
- Update `composer.json` for version
- Moved `ResourceCollection` to `PhalconRest\Collection\ResourceCollection`
- Moved `PhalconRest\Mvc\FractalController` to `PhalconRest\Mvc\Controller\Fractal`
- Moved `PhalconRest\Mvc\ResourceController` to `PhalconRest\Mvc\Controller\Resource`

## [1.1.7] - 2015-12-16
### Added
- Added `PhalconRest\Api\Resource::getPrimaryKey`, `PhalconRest\Api\Resource::setPrimaryKey`
- Introduced `ResourceCollection`
- Amended `FactoryDefault` for services by default
- Added `PhalconRest\Mvc\Controller`
- Added `PhalconRest\Mvc\ResourceController`

### Changed
- Update `composer.json` for version
- Moved `PhalconRest\Middleware\UrlQuery` to `PhalconRest\Middleware\Queries`

### Removed

## [1.1.6] - 2015-12-15
### Added
- Amended `Services`' constants
- Added `PhalconRest\Middleware\Queries`

### Changed
- Update `composer.json` for version

## [1.1.5] - 2015-12-15
### Changed
- Moved library from `olivierandriessen/phalcon-rest` to `redound/phalcon-rest`
- Update `composer.json` for version

## [1.1.4] - 2015-12-15
### Changed
- Renamed `PhalconRest\Api\ApiService` to `PhalconRest\Api\Service`

## [1.1.3] - 2015-12-15
### Changed
- Update `composer.json` for version

## [1.1.2] - 2015-12-15
### Changed
- Update `composer.json` for version

## [1.1.0] - 2015-12-15
### Added
- Added:
  - `PhalconRest\Api\Resources`
  - `PhalconRest\Api\ApiService`
  - `PhalconRest\Data\Query\Query`
  - `PhalconRest\Data\Query\Sorter`
  - `PhalconRest\Data\Query\Condition`
  - `PhalconRest\Data\Query\Parser\Phql`
  - `PhalconRest\Data\Query\Parser\Url`

## [1.0.1] - 2015-11-07
### Changed
- Update `composer.json` for version

## [1.0.0] - 2015-11-07
### Added
- Added default error messages to `Response`
- Added service constants for Phalcon services
- Auth: Implemented session start time
- Introduced `FactoryDefault` DI

### Changed
- Improved `Request` for authentication
- AuthManager: Better exception handling
- Changed JWT to firebase dependency & suggestions in composer
- Improvements in error codes
- Response sets status code on error
- Moved JsonResponse middleware to response object
- Structural improvements
- Enabled PSR4 in `composer.json`

### Removed
- Removed redundant `Session::$isValid`

### Fixed
- Fixed Postman Export

### Security
- Fixed JWT parser
- Fixes in Auth system
- Fixed JWT parser

## 0.0.4 - 2015-11-07
### Added
- Initial public release

[Unreleased]: https://github.com/preferans/prest/compare//v3.6.0...HEAD
[3.6.0]: https://github.com/preferans/prest/compare/v3.5.0...v3.6.0
[3.5.0]: https://github.com/preferans/prest/compare/v3.4.0...v3.5.0
[3.4.0]: https://github.com/preferans/prest/compare/v3.3.0...v3.4.0
[3.3.0]: https://github.com/preferans/prest/compare/v3.2.4...v3.3.0
[3.2.4]: https://github.com/preferans/prest/compare/v3.2.3...v3.2.4
[3.2.3]: https://github.com/preferans/prest/compare/v3.2.2...v3.2.3
[3.2.2]: https://github.com/preferans/prest/compare/v3.2.1...v3.2.2
[3.2.1]: https://github.com/preferans/prest/compare/v3.2.0...v3.2.1
[3.2.0]: https://github.com/preferans/prest/compare/v3.1.3...v3.2.0
[3.1.3]: https://github.com/preferans/prest/compare/v3.1.2...v3.1.3
[3.1.2]: https://github.com/preferans/prest/compare/v3.1.1...v3.1.2
[3.1.1]: https://github.com/preferans/prest/compare/v3.1.0...v3.1.1
[3.1.0]: https://github.com/preferans/prest/compare/v3.0.0...v3.1.0
[3.0.0]: https://github.com/preferans/prest/compare/v2.1.0...v3.0.0
[2.1.0]: https://github.com/preferans/prest/compare/v2.0.0...v2.1.0
[2.0.0]: https://github.com/preferans/prest/compare/v1.5.0...v2.0.0
[1.5.0]: https://github.com/preferans/prest/compare/v1.4.2...v1.5.0
[1.4.2]: https://github.com/preferans/prest/compare/v1.4.1...v1.4.2
[1.4.1]: https://github.com/preferans/prest/compare/v1.4.0...v1.4.1
[1.4.0]: https://github.com/preferans/prest/compare/v1.3.1...v1.4.0
[1.3.1]: https://github.com/preferans/prest/compare/v1.3.0...v1.3.1
[1.3.0]: https://github.com/preferans/prest/compare/v1.2.2...v1.3.0
[1.2.2]: https://github.com/preferans/prest/compare/v1.2.1...v1.2.2
[1.2.1]: https://github.com/preferans/prest/compare/v1.2.0...v1.2.1
[1.2.0]: https://github.com/preferans/prest/compare/v1.1.9...v1.2.0
[1.1.9]: https://github.com/preferans/prest/compare/v1.1.8...v1.1.9
[1.1.8]: https://github.com/preferans/prest/compare/v1.1.7...v1.1.8
[1.1.7]: https://github.com/preferans/prest/compare/v1.1.6...v1.1.7
[1.1.6]: https://github.com/preferans/prest/compare/v1.1.5...v1.1.6
[1.1.5]: https://github.com/preferans/prest/compare/v1.1.4...v1.1.5
[1.1.4]: https://github.com/preferans/prest/compare/v1.1.3...v1.1.4
[1.1.3]: https://github.com/preferans/prest/compare/v1.1.2...v1.1.3
[1.1.2]: https://github.com/preferans/prest/compare/v1.1.0...v1.1.2
[1.1.0]: https://github.com/preferans/prest/compare/v1.0.1...v1.1.0
[1.0.1]: https://github.com/preferans/prest/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/preferans/prest/compare/v0.0.4...v1.0.0
[keep-cl]: http://keepachangelog.com
[semver]: http://semver.org
