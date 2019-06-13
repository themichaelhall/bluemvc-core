## Unreleased

## [2.1.0] - 2019-06-13
- Added ActionResult.
- Added type hints functionality in action method parameters.
- Added ActionResultException handling in action methods.
- Added BadRequestResult and BadRequestResultException.
- Added UnauthorizedResult and UnauthorizedResultException.
- Added ViewOrActionResultInterface.
- Session cookie is marked as "secure" if request is secure (https).
- Session cookie and session is not created when reading from an empty session.
- Session cookie and session is destroyed if session is empty.
- Deprecated AbstractActionResult.

## [2.0.4] - 2019-01-07
- Fixed [#4](https://github.com/themichaelhall/bluemvc/issues/4) - Exception thrown when using backslash in query string.

## [2.0.3] - 2018-10-17
- Fixed [#3](https://github.com/themichaelhall/bluemvc/issues/3) - Request url protocol is always https in IIS.

## [2.0.2] - 2018-09-25
- Fixed [#2](https://github.com/themichaelhall/bluemvc/issues/2) - Header Authorization not present with Apache/2.4.7.

## [2.0.1] - 2018-06-19
- Fixed [#1](https://github.com/themichaelhall/bluemvc/issues/1) - Empty action method parameter when "0" is last in url. 

## [2.0.0] - 2018-05-14
- **Backward compatibility break**: Replaced getException/setException methods with getThrowable/setThrowable in ErrorController.
- **Backward compatibility break**: Moved session handling from Application to Request.
- Updated PHP requirement to 7.1.

## [1.1.0] - 2018-04-29
- Added getClientIp method to Request.
- Added getReferrer method to Request.
- Added getCookieValue method to Request.
- Added setCookieValue method to Response.
- Added ErrorControllerTrait.
- Added isPreActionEventEnabled and isPostActionEventEnabled methods to Controller.
- Prevented onPreActionEvent and onPreActionEvent methods from running on error controller if an exception occurred.

## 1.0.0 - 2018-02-03
- First stable revision.

[2.1.0]: https://github.com/themichaelhall/bluemvc-core/compare/v2.0.4...v2.1.0
[2.0.4]: https://github.com/themichaelhall/bluemvc-core/compare/v2.0.3...v2.0.4
[2.0.3]: https://github.com/themichaelhall/bluemvc-core/compare/v2.0.2...v2.0.3
[2.0.2]: https://github.com/themichaelhall/bluemvc-core/compare/v2.0.1...v2.0.2
[2.0.1]: https://github.com/themichaelhall/bluemvc-core/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/themichaelhall/bluemvc-core/compare/v1.1.0...v2.0.0
[1.1.0]: https://github.com/themichaelhall/bluemvc-core/compare/v1.0.0...v1.1.0
