<?php

namespace Sunnysideup\PasswordSaver\Form;

use SilverStripe\Forms\PasswordField;


/**
 * ExternalURLField
 *
 * Form field for entering, saving, validating external urls.
 */
class ClientSidePasswordField extends PasswordField
{

    /**
     * @var array
     */
    protected $config;

    public function __construct($name, $title = null, $value = null)
    {
        $this->config = $this->config()->default_config;

        parent::__construct($name, $title, $value);
    }

    public function Type()
    {
        return 'client-side passsword';
    }

    /**
     * @param string $name
     * @param mixed $val
     */
    public function setConfig($name, $val = null)
    {
        if (is_array($name) && $val == null) {
            foreach ($name as $n => $value) {
                $this->setConfig($n, $value);
            }

            return $this;
        }
        if (is_array($this->config[$name])) {
            if (!is_array($val)) {
                user_error("The value for $name must be an array");
            }
            $this->config[$name] = array_merge($this->config[$name], $val);
        } elseif (isset($this->config[$name])) {
            $this->config[$name] = $val;
        }

        return $this;
    }

    /**
     * @param String $name Optional, returns the whole configuration array if empty
     * @return mixed|array
     */
    public function getConfig($name = null)
    {
        if ($name) {
            return isset($this->config[$name]) ? $this->config[$name] : null;
        } else {
            return $this->config;
        }
    }

    /**
     * Set additional attributes
     * @return array Attributes
     */
    public function getAttributes()
    {
        $parentAttributes = parent::getAttributes();
        $attributes = [];

        if (!isset($parentAttributes['placeholder'])) {
            $attributes['placeholder'] = $this->config['defaultparts']['scheme'] . "://example.com"; //example url
        }

        return array_merge(
            $parentAttributes,
            $attributes
        );
    }

    /**
     * Server side validation
     */
    public function validate($validator)
    {
        $this->value = trim($this->value);
        if ($this->value && strlen($this->value) < 32) {
            $validator->validationError(
                $this->name,
                'Value must be encrypted.',
                "validation"
            );
            return false;
        }
        return true;
    }
}
