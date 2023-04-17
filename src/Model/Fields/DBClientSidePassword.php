<?php

namespace Sunnysideup\PasswordSaver\Model\Fields;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataObjectInterface;
use SilverStripe\ORM\FieldType\DBVarchar;
use Sunnysideup\PasswordSaver\Form\ClientSidePasswordField;
use Sunnysideup\UUDI\Api\HashCreator;

class DBClientSidePassword extends DBVarchar
{
    private static $casting = [
        'FormGet' => 'HTMLText',
        'FormSet' => 'HTMLText',
    ];

    public function __construct($name = null, $size = 7, $options = [])
    {
        parent::__construct($name, $size, $options);
    }

    public static function get_unique_value(DataObjectInterface $record): string
    {
        return HashCreator::generate_hash(7);
    }

    /**
     * Remove ugly parts of a url to make it nice.
     */
    public function Nice()
    {
        return $this->value;
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

        return '(no code available)';
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
        if (! $dataObject->{$fieldName}) {
            $dataObject->{$fieldName} = self::get_unique_value($dataObject);
        }
    }

    public function FormGet(string $link, ?string $name = 'get'): string
    {
        return $this->formInner($link, $name);
    }

    public function FormSet(string $link, ?string $name = 'set'): string
    {
        return $this->formInner($link, $name);
    }

    protected function formInner(string $link, string $name): string
    {
        return '
            <form method="post" action="'.$link.'" class="password-getter-setter-form" formtarget="_blank" rel="noreferrer noopener">
                <input type="hidden" value="'.$this->value.'" name="Code" />
                <button type="submit" value="'.$name.'" /></button>
            </form>
        ';
    }
}
