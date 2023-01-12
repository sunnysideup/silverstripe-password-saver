<?php

namespace Sunnysideup\PasswordSaver\Model\Fields;

use SilverStripe\ORM\FieldType\DBVarchar;
use SilverStripe\ORM\DataObjectInterface;
use SilverStripe\Core\ClassInfo;
use Sunnysideup\PasswordSaver\Form\ClientSidePasswordField;

class DBClientSidePassword extends DBVarchar
{

    public static function get_unique_value(DataObjectInterface $record) : string
    {
        return str_replace('-', '_', $record->getUniqueKey());
    }

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
        return new ClientSidePasswordField($this->name, $title);
    }

    public function forTemplate()
    {
        if ($this->value) {
            return $this->Nice();
        }

        return '(password not set)';
    }

    public function setValue($value, $record = null, $markChanged = true)
    {
        if($record) {
            $this->value = self::get_unique_value($record);
        }

        return $this;
    }

        /**
         * Saves this field to the given data object.
         *
         * @param DataObject $dataObject
         */
        public function saveInto($dataObject)
        {
            $fieldName = $this->name;
            if (empty($fieldName)) {
                throw new \BadMethodCallException(
                    "DBField::saveInto() Called on a nameless '" . static::class . "' object"
                );
            }

            $dataObject->$fieldName = self::get_unique_value($dataObject);
        }

}
