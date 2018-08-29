## Unreleased
- Added ActionResult.
- Added type hints functionality in action method parameters.
- Added ActionResultException handling in action methods.
- Added BadRequestResult.
- Session cookie is marked as "secure" if request is secure (https).
- Deprecated AbstractActionResult.

## [2.0.1] 2018-06-19
- Fixed [#1](https://github.com/themichaelhall/bluemvc/issues/1) - Empty action method parameter when "0" is last in url. 

## [2.0.0] 2018-05-14
- **Backward compatibility break**: Replaced getException/setException methods with getThrowable/setThrowable in ErrorController.
- **Backward compatibility break**: Moved session handling from Application to Request.
- Updated PHP requirement to 7.1.

## [1.1.0] 2018-04-29
- Added getClientIp method to Request.
- Added getReferrer method to Request.
- Added getCookieValue method to Request.
- Added setCookieValue method to Response.
- Added ErrorControllerTrait.
- Added isPreActionEventEnabled and isPostActionEventEnabled methods to Controller.
- Prevented onPreActionEvent and onPreActionEvent methods from running on error controller if an exception occurred.

## 1.0.0 - 2018-02-03
- First stable revision.

[2.0.1]: https://github.com/themichaelhall/bluemvc-core/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/themichaelhall/bluemvc-core/compare/v1.1.0...v2.0.0
[1.1.0]: https://github.com/themichaelhall/bluemvc-core/compare/v1.0.0...v1.1.0