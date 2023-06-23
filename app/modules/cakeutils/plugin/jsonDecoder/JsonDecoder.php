<?php

//use jsonDecoder\Bindings\RawBinding;
App::uses("RawBinding", "modules/cakeutils/plugin/jsonDecoder/Bindings");
App::uses("ClassBindings", "modules/cakeutils/plugin/jsonDecoder");

class JsonDecoder {
    /**
     * @var array
     */
    private $transformers = [];

    private $decodePrivateProperties;

    private $decodeProtectedProperties;

    /**
     * JsonDecoder constructor.
     *
     * @param bool $decodePrivateProperties
     * @param bool $decodeProtectedProperties
     */
    public function __construct($decodePrivateProperties = false, $decodeProtectedProperties = false) {
        $this->decodePrivateProperties = $decodePrivateProperties;
        $this->decodeProtectedProperties = $decodeProtectedProperties;
    }

    /**
     * registers the given transformer.
     *
     * @param Transformer $transformer
     */
    public function register(Transformer $transformer) {
        $this->transformers[$transformer->transforms()] = $transformer;
    }

    public function decode($json, $classType) {
        return $this->decodeArray(json_decode($json, true), $classType);
    }

    public function decodeMultiple($json, $classType) {
        $data = json_decode($json, true);

        return $data ? array_map(
            function ($element) use ($classType) {
                return $this->decodeArray($element, $classType);
            },
            $data
        ) : array();
    }

    /**
     * decodes the given array data into an instance of the given class type.
     *
     * @param $jsonArrayData array
     * @param $classType string
     *
     * @return mixed an instance of $classType
     */
    public function decodeArray($jsonArrayData, $classType) {
        $instance = new $classType();

        if (array_key_exists($classType, $this->transformers)) {
            $instance = $this->transform($this->transformers[$classType], $jsonArrayData, $instance);
        } else {
            $instance = $this->transformRaw($jsonArrayData, $instance);
        }

        return $instance;
    }

    public function decodesPrivateProperties() {
        return $this->decodePrivateProperties;
    }

    public function decodesProtectedProperties() {
        return $this->decodeProtectedProperties;
    }

    private function transform($transformer, $jsonArrayData, $instance) {
        if (!is_array($jsonArrayData)) {
            return null;
        }

        $classBindings = new ClassBindings($this);
        $transformer->register($classBindings);

        return $classBindings->decode($jsonArrayData, $instance);
    }

    protected function transformRaw($jsonArrayData, $instance) {
        if (empty($jsonArrayData)) {
            return null;
        }

        if (!is_array($jsonArrayData)) {
            return null;
        }

        $classBindings = new ClassBindings($this);

        foreach (array_keys($jsonArrayData) as $property) {
            $classBindings->register(new RawBinding($property));
        }

        return $classBindings->decode($jsonArrayData, $instance);
    }
}
