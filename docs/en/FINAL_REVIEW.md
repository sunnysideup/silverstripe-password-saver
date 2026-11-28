# Final review

## Fixed
- src/Form/ClientSidePasswordField.php: `string $link = null` changed to `?string $link = null` (implicit nullable is deprecated in PHP 8.4).

## 🚨 Security issues needing a human
- src/Form/ClientSidePasswordField.php: `$value` (the field's value) and the `ManagerLink` are output into HTML without escaping. The value is generated, but not guaranteed to be.
- src/Model/Fields/DBClientSidePassword.php: `formInner()` puts `$link` and `$this->value` into HTML unescaped. Use `Convert::raw2att()`.

## ⚠️ Upgrade work still to do
- composer.json: the code uses `Sunnysideup\UUDI\Api\HashCreator` (sunnysideup/uudi) but the package isn't in `require`. Add it with an SS6-compatible version.
