<?php

namespace Sunnysideup\PasswordSaver\Form;

use SilverStripe\Forms\ReadonlyField;
use SilverStripe\ORM\FieldType\DBHTMLText;

class ClientSidePasswordField extends ReadonlyField
{

    protected $template = 'Sunnysideup/PasswordSaver/Form/ClientSidePasswordField';
    protected $ManagerLink = null;

    function __construct($name, $title = null, $value = null, string $link = null)
    {
        parent::__construct($name, $title, $value);
        if ($link) {
            $this->ManagerLink = $link;
        }
    }

    public function ManagerLink()
    {
        return $this->ManagerLink;
    }

    public function setManagerLink(string $link)
    {
        $this->ManagerLink = $link;
    }

    public function Type()
    {
        return 'text password client-side-passsword';
    }

    public function Title()
    {
        return 'Password Key';
    }

    public function getDescription()
    {
        return implode(
            '<br />',
            array_filter(
                [
                    parent::getDescription(),
                    '<em>By separating username and password, you increase security.  Make sure to save the passwords somewhere securely, with the exact key listed here to ensure referential integrity.</em>',
                ]
            )
        );
    }

    /**
     * @return mixed|string
     */
    public function Value()
    {
        // Get raw value
        $value = $this->dataValue();
        if ($value) {
            return DBHTMLText::create_field('HTMLText', "<pre>{$value}</pre>");
        }

        // "none" text
        $label = _t('SilverStripe\\Forms\\FormField.NONE', 'none');

        return "<pre>{$label}</pre>";
    }

    public function isReadonly()
    {
        return false;
    }

    public function getTemplate()
    {
        return $this->template;
    }
}
