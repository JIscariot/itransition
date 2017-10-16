<?php

namespace Tests\AppBundle\Validator;

use AppBundle\Extractor\ProductExtractor;
use AppBundle\Validator\ProductValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

Class ProductValidatorTest extends KernelTestCase
{
    /**
     * @var array
     */
    public $items = [];

    public function setUp()
    {
        self::bootKernel();

        // Get csv file path.
        $file = self::$kernel->getContainer()->getParameter('storage.stock');
        // Init Serializer.
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        // Decoding CSV contents.
        $this->items = $serializer->decode(file_get_contents($file), 'csv');
    }

    /**
     * @dataProvider validateNameProvider
     */
    public function testValidateName($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);

        $validator = new ProductValidator($product);
        $this->assertEquals($result, $validator->validateName());
    }

    public function validateNameProvider()
    {
        return [
            [10, true],
            [17, true],
            [27, true],
            [28, true],
        ];
    }

    /**
     * @dataProvider validateCodeProvider
     */
    public function testValidateCode($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $validator = new ProductValidator($product);
        $this->assertEquals($result, $validator->validateCode());
    }

    public function validateCodeProvider()
    {
        return [
            [10, true],
            [17, true],
            [27, true],
            [28, true],
        ];
    }

    /**
     * @dataProvider validateDescriptionProvider
     */
    public function testValidateDescription($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $validator = new ProductValidator($product);
        $this->assertEquals($result, $validator->validateDescription());
    }

    public function validateDescriptionProvider()
    {
        return [
            [10, true],
            [17, true],
            [27, true],
            [28, true],
        ];
    }

    /**
     * @dataProvider validatePriceProvider
     */
    public function testValidatePrice($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $validator = new ProductValidator($product);
        $this->assertEquals($result, $validator->validatePrice());
    }

    public function validatePriceProvider()
    {
        return [
            [10, true],
            [17, true],
            [27, true],
            [28, true],
        ];
    }

    /**
     * @dataProvider validateMaxPriceProvider
     */
    public function testValidateMaxPrice($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $validator = new ProductValidator($product);
        $this->assertEquals($result, $validator->validateMaxPrice());
    }

    public function validateMaxPriceProvider()
    {
        return [
            [10, true],
            [17, true],
            [27, false],
            [28, false],
        ];
    }

    /**
     * @dataProvider validateMinPriceAndQuantityProvider
     */
    public function testValidateMinPriceAndQuantity($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $validator = new ProductValidator($product);
        $this->assertEquals($result, $validator->validateMinPriceAndQuantity());
    }

    public function validateMinPriceAndQuantityProvider()
    {
        return [
            [10, false],
            [17, false],
            [27, true],
            [28, true],
        ];
    }

    /**
     * @dataProvider testValidateQuantityProvider
     */
    public function testValidateQuantity($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $validator = new ProductValidator($product);
        $this->assertEquals($result, $validator->validateQuantity());
    }

    public function testValidateQuantityProvider()
    {
        return [
            [10, true],
            [17, true],
            [27, true],
            [28, true],
        ];
    }

    /**
     * @dataProvider testValidateIsValidProvider
     */
    public function testValidateIsValid($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $validator = new ProductValidator($product);
        $this->assertEquals($result, $validator->isValid());
    }

    public function testValidateIsValidProvider()
    {
        return [
            [10, false],
            [17, false],
            [27, false],
            [28, false],
        ];
    }
}