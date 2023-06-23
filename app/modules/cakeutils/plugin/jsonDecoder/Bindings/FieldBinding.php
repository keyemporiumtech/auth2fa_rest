<?php
App::uses("Binding", "modules/cakeutils/plugin/jsonDecoder");
App::uses("JsonValueException", "modules/cakeutils/plugin/jsonDecoder/Exceptions");
App::uses("JsonDecoder", "modules/cakeutils/plugin/jsonDecoder");
App::uses("PropertyAccessor", "modules/cakeutils/plugin/jsonDecoder");

class FieldBinding implements Binding
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
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $isRequired;

    /**
     * FieldBinding constructor.
     *
     * @param string $property   the property to bind to
     * @param string $jsonField  the json field
     * @param string $type       the desired type of the property
     * @param bool   $isRequired defines if the field value is required during decoding
     */
    public function __construct($property, $jsonField, $type, $isRequired = false)
    {
        $this->property = $property;
        $this->jsonField = $jsonField;
        $this->type = $type;
        $this->isRequired = $isRequired;
    }

    /**
     * executes the defined binding method on the class instance.
     *
     * @param JsonDecoder      $jsonDecoder
     * @param array            $jsonData
     * @param PropertyAccessor $propertyAccessor the class instance to bind to
     *
     * @throws JsonValueException if given json field is not available
     *
     * @return mixed
     */
    public function bind($jsonDecoder, $jsonData, $propertyAccessor)
    {
        if (array_key_exists($this->jsonField, $jsonData)) {
            $data = $jsonData[$this->jsonField];
            $propertyAccessor->set($jsonDecoder->decodeArray($data, $this->type));
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
