# Upgrading to Silverstripe CMS 6

## Requirements

### ⚠️ BREAKING CHANGE: Silverstripe 4 and 5 are no longer supported

- `silverstripe/framework` constraint changed from `^4.0 || ^5.0` to `^6.0`.
- Stay on the previous release of this module if your project is still on SS4 or SS5.
- Silverstripe CMS 6 requires a newer PHP version than SS5 (PHP 8.3 or higher). Check your hosting before upgrading.

### ⚠️ BREAKING CHANGE: New dependency `sunnysideup/uuid`

- `sunnysideup/uuid` is now required. It provides `HashCreator`, which `DBClientSidePassword` uses.
- Run `composer update` so that it is installed.

**🚨 CRITICAL REVIEW REQUIRED: `sunnysideup/uuid` is pinned to `dev-master`. The tagged releases (1.x) only support SS4/5, so no stable SS6-compatible release exists yet. Your root `composer.json` decides the stability rules, not this module's `minimum-stability: dev`. Either allow `sunnysideup/uuid` at `dev-master` (for example `"sunnysideup/uuid": "dev-master"` in your root `require`) or set `minimum-stability` to `dev` with `prefer-stable: true`. Replace the `dev-master` constraint with a stable tag once an SS6-compatible release is published.**

## Class and namespace changes

### ⚠️ BREAKING CHANGE: `HashCreator` import corrected

- `Sunnysideup\UUDI\Api\HashCreator` is now `Sunnysideup\UUID\Api\HashCreator`.
- The old `UUDI` namespace never existed, so the old import was a fatal error.
- Fix any of your own code that copied this import.

## API changes

### ⚠️ BREAKING CHANGE: `ClientSidePasswordField::Value()` renamed to `getFormattedValue()`

- `Value()` was deprecated in SS 5.4 and is removed in SS6, so this module's field now overrides `getFormattedValue()`.
- Replace any calls to `ClientSidePasswordField::Value()` with `getFormattedValue()`.
- Replace any subclass overrides of `Value()` with `getFormattedValue()`.
- Check custom templates that render `$Value` for this field.

### ⚠️ BREAKING CHANGE: Stricter method signatures on `DBClientSidePassword`

SS6 added type declarations to the `DBField`/`DBVarchar` API, so the overrides changed to match. Update any subclass of `DBClientSidePassword` to use the same signatures:

| Method | New signature |
| --- | --- |
| `__construct` | `__construct(?string $name = null, int $size = 7, array $options = [])` |
| `scaffoldFormField` | `scaffoldFormField(?string $title = null, array $params = []): ?FormField` |
| `forTemplate` | `forTemplate(): string` |
| `saveInto` | `saveInto(ModelData $dataObject): void` |

- `saveInto()` now takes `SilverStripe\Model\ModelData` instead of `SilverStripe\ORM\DataObject`. `DataObject` is no longer the declared type in the `DBField` API.
- `saveInto()` now returns `void` and `forTemplate()` must return a `string`.

**🚨 CRITICAL REVIEW REQUIRED: Only the signatures of `saveInto()` and `forTemplate()` were changed. Review the method bodies. Confirm that every code path in `forTemplate()` returns a `string` (never `null`). Confirm that `saveInto()` doesn't call `DataObject`-only methods on the `ModelData` argument.**

### `ClientSidePasswordField` constructor

- The `$link` parameter is now explicitly nullable (`?string $link = null`). Implicit nullable types are deprecated in PHP 8.4. Calling code doesn't need to change.

## Security fixes

### ⚠️ BREAKING CHANGE: Output is now HTML-escaped

The following output was previously unescaped (XSS risk). It is now escaped with `htmlspecialchars(..., ENT_QUOTES)`:

- `ClientSidePasswordField::getFormattedValue()`: the field value shown in the `<pre>` block.
- `DBClientSidePassword::formInner()`: the form `action` link and the hidden `Code` input value.

Impact:

- Remove any manual escaping you added around these outputs, otherwise they will be double-escaped.
- If you stored intentional HTML in the field and relied on it rendering, it will now be shown as text.

**🚨 CRITICAL REVIEW REQUIRED: In `formInner()` the button label `$name` is still not escaped, and the button markup (`<button type="submit" />...</button>`) is malformed (self-closing tag with a closing tag). Make sure `$name` never contains user-controlled input. Fix the markup if it renders incorrectly.**

**🚨 CRITICAL REVIEW REQUIRED: `ClientSidePasswordField::getDescription()` returns HTML. Check that it still renders unescaped in SS6 CMS forms. It was not changed in this upgrade.**

## Upgrade checklist

1. Make sure the project runs on SS6 and a supported PHP version.
2. Allow `sunnysideup/uuid` at `dev-master`, then run `composer update sunnysideup/password-saver sunnysideup/uuid`.
3. Update your own subclasses and calls for the changes above.
4. Run `vendor/bin/sake db:build --flush` (or `/dev/build?flush=all`).
5. Open a DataObject that uses `DBClientSidePassword` in the CMS. Check that the field renders, the value is escaped correctly, the form link works and saving works.
