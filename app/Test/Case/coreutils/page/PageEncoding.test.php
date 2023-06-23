<?php
App::uses("PageUtility", "modules/coreutils/utility");

class PageEncodingTest extends CakeTestCase {
    public $arrayParams;

    public function setUp() {
        parent::setUp();

        $this->arrayParams = array(
            "key1" => 1,
            "key2" => 2,
            "key3" => 3,
        );
    }

    public function testEncodeParametersPath() {
        $result1 = PageUtility::encodeParametersPath($this->arrayParams);
        $result2 = PageUtility::encodeParametersPath($this->arrayParams, "-");
        $this->assertEquals($result1, "1,2,3");
        $this->assertEquals($result2, "1-2-3");
    }

    public function testEncodeParametersPathWithKeyValue() {
        $result1 = PageUtility::encodeParametersPathWithKeyValue($this->arrayParams);
        $result2 = PageUtility::encodeParametersPathWithKeyValue($this->arrayParams, "-");
        $this->assertEquals($result1, "key1=1,key2=2,key3=3");
        $this->assertEquals($result2, "key1=1-key2=2-key3=3");
    }

    public function testDecodeParametersPath() {
        $result1 = PageUtility::decodeParametersPath("1,2,3");
        $result2 = PageUtility::decodeParametersPath("1,2,3", "-");
        $result3 = PageUtility::decodeParametersPath("1-2-3", "-");
        $this->assertEquals($result1[0], "1");
        $this->assertEquals($result2[0], "1,2,3");
        $this->assertEquals($result3[0], "1");
    }

    public function testDecodeParametersPathWithKeyValue() {
        $result1 = PageUtility::decodeParametersPathWithKeyValue("key1=1,key2=2,key3=3");
        $result2 = PageUtility::decodeParametersPathWithKeyValue("key1=1,key2=2,key3=3", "-");
        $result3 = PageUtility::decodeParametersPathWithKeyValue("key1=1-key2=2-key3=3", "-");
        $this->assertEquals($result1['key1'], "1");
        $this->assertEquals(array_key_exists("key2", $result1), true);
        $this->assertEquals(count($result2) == 0, true);
        $this->assertEquals($result3['key1'], "1");
        $this->assertEquals(array_key_exists("key2", $result3), true);
    }

    public function testEncodeUrlPath() {
        $result1 = PageUtility::encodeUrlPath("/page/webroot\\..\\ciao");
        $result2 = PageUtility::encodeUrlPath("/page/webroot\\..\\ciao", ",");
        $this->assertEquals($result1, "-page-webroot-..-ciao");
        $this->assertEquals($result2, ",page,webroot,..,ciao");
    }

    public function testDecodeUrlPath() {
        $result1 = PageUtility::decodeUrlPath("-page-webroot-..-ciao");
        $result2 = PageUtility::decodeUrlPath(",page,webroot,..,ciao", ",");
        $result3 = PageUtility::decodeUrlPath("-page-webroot-..-ciao", "-", true);
        $this->assertEquals($result1, "/page/webroot/../ciao");
        $this->assertEquals($result2, "/page/webroot/../ciao");
        $this->assertEquals($result3, "\\page\\webroot\\..\\ciao");
    }
}
