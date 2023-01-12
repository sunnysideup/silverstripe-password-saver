<?php

namespace Sunnysideup\PasswordSaver\Form;

use SilverStripe\Forms\ReadonlyField;

/**
 * ExternalURLField.
 *
 * Form field for entering, saving, validating external urls.
 */
class ClientSidePasswordField extends ReadonlyField
{
    public function Type()
    {
        return 'text password client-side-passsword';
    }

    public function Title()
    {
        return 'Password Key (save password safely offline with only this key as reference)';
    }

    public function getDescription()
    {
        return implode(
            '<br />',
            array_filter(
                [
                    parent::getDescription(),
                    '<em>By separing username and password, you increase security.  Make sure to save the passwords somewhere securely, with the exact key listed here to ensure referential integrity.</em>',
                ]
            )
        );
    }

    public function isReadonly()
    {
        return false;
    }
}
