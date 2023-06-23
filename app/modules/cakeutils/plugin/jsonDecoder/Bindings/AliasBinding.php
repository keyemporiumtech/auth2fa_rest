<?php
App::uses("Binding", "modules/cakeutils/plugin/jsonDecoder");
App::uses("JsonValueException", "modules/cakeutils/plugin/jsonDecoder/Exceptions");
App::uses("JsonDecoder", "modules/cakeutils/plugin/jsonDecoder");
App::uses("PropertyAccessor", "modules/cakeutils/plugin/jsonDecoder");

class AliasBinding implements Binding
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $jsonField;

    /**
     * @var bool
     */
    private $isRequired;

    /**
     * AliasBinding constructor.
     *
     * @param string $property   the property to bind to
     * @param string $jsonField  the json field
     * @param bool   $isRequired defines if the field value is required during decoding
     */
    public function __construct($property, $jsonField, $isRequired = false)
    {
        $this->property = $property;
        $this->jsonField = $jsonField;
        $this->isRequired = $isRequired;
    }

    /**
     * executes the defined binding method on the class instance.
     *
     * @param JsonDecoder      $jsonDecoder
     * @param mixed            $jsonData
     * @param PropertyAccessor $propertyAccessor the class instance to bind to
     *
     * @throws JsonValueException if given json field is not available
     *
     * @return mixed
     */
    public function bind($jsonDecoder, $jsonData, $propertyAccessor)
    {
        if (array_key_exists($this->jsonField, $jsonData)) {
            $propertyAccessor->set($jsonData[$this->jsonField]);

            return;
        }

        if ($this->isRequired) {
            throw new JsonValueException(
                sprintf('the value "%s" for property "%s" does not exist', $this->jsonField, $this->property)
            );
        }
    }

    /**
     * @return string the name of the property to bind
     */
    public function property()
    {
        return $this->property;
    }
}
