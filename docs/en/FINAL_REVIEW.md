# Final review

## Fixed
- `src/Model/Fields/DBClientSidePassword.php`: import `Sunnysideup\UUDI\Api\HashCreator` corrected to `Sunnysideup\UUID\Api\HashCreator`. The `UUDI` namespace does not exist, so the class would fatal.
- `composer.json`: added the missing `sunnysideup/uuid` dependency, which provides `HashCreator`. Its tagged releases (1.x) only support SS4/5, so `dev-master` (SS6 compatible) is required.
- `src/Model/Fields/DBClientSidePassword.php`: SS6 signatures. The constructor is now typed. `scaffoldFormField(?string, array): ?FormField` is added, because the missing return type is a fatal error. `saveInto(ModelData $dataObject)` replaces `DataObject`, which is removed from the `DBField` API.
- `src/Model/Fields/DBClientSidePassword.php`: `formInner()` now escapes `$link` and the value with `htmlspecialchars`. This fixes XSS in the generated form HTML.
- `src/Form/ClientSidePasswordField.php`: the value in `getFormattedValue()` is now escaped (XSS). The implicit nullable `string $link = null` became `?string`, which is deprecated in PHP 8.4.

## ⚠️ Upgrade work still to do
- `composer.json`: `sunnysideup/uuid` is pinned to `dev-master`. Change it to a stable `^2.0` or similar constraint once an SS6 compatible tag is released.
- `src/Form/ClientSidePasswordField.php`: `getDescription()` returns HTML. Check that it still renders unescaped in SS6 CMS forms.
