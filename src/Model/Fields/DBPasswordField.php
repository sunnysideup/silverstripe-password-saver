<?php

namespace Sunnysideup\PasswordSaver\Model\Fields;

use SilverStripe\ORM\FieldType\DBVarchar;
use Sunnysideup\PasswordSaver\Form\ClientSidePasswordField;

class DBPasswordField extends DBVarchar
{
    // private static $casting = array(
    //     "Domain" => ExternalURL::class,
    //     "URL" => ExternalURL::class,
    // );
    //

    public function __construct($name = null, $size = 2083, $options = [])
    {
        parent::__construct($name, $size, $options);
    }

    /**
     * Remove ugly parts of a url to make it nice.
     */
    public function Nice()
    {
        return '****';
    }

    /**
     * Scaffold the ExternalURLField for this ExternalURL.
     *
     * @param null|mixed $title
     * @param null|mixed $params
     */
    public function scaffoldFormField($title = null, $params = null)
    {
        $field = new ClientSidePasswordField($this->name, $title);
        $field->setMaxLength($this->getSize());

        return $field;
    }

    public function forTemplate()
    {
        if ($this->value) {
            return $this->Nice();
        }

        return '(password not set)';
    }
}
