<?php

namespace Sunnysideup\PasswordSaver\Form;

use SilverStripe\Forms\ReadonlyField;
use SilverStripe\ORM\FieldType\DBHTMLText;

class ClientSidePasswordField extends ReadonlyField
{
    protected $template = 'Sunnysideup/PasswordSaver/Form/ClientSidePasswordField';
    protected $ManagerLink;

    public function __construct($name, $title = null, $value = null, string $link = null)
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
        $fontSize = '2em';
        if (!$value) {
            $value = 'Code not set yet - please save this record first';
            $fontSize = '1em';
        }
        return DBHTMLText::create_field(
            'HTMLText',
            '<pre style="margin-bottom:0; font-size: ' . $fontSize . ';">' . $value . '</pre>'
        );

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
