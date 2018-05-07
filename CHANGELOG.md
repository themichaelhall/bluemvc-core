## Unreleased
- **Backward compatibility break**: Replaced getException/setException methods with getThrowable/setThrowable in ErrorController.
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

[1.1.0]: https://github.com/themichaelhall/bluemvc-core/compare/v1.0.0...v1.1.0