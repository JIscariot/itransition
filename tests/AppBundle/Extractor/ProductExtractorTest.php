<?php

namespace Tests\AppBundle\Extractor;

use AppBundle\Extractor\ProductExtractor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

Class ProductExtractorTest extends KernelTestCase
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
     * @dataProvider getNameProvider
     */
    public function testGetName($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $this->assertEquals($result, $product->getName());
    }

    public function getNameProvider()
    {
        return [
            [0, 'TV'],
        ];
    }

    /**
     * @dataProvider getCodeProvider
     */
    public function testGetCode($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $this->assertEquals($result, $product->getCode());
    }

    public function getCodeProvider()
    {
        return [
            [0, 'P0001'],
        ];
    }

    /**
     * @dataProvider getDescriptionProvider
     */
    public function testGetDescription($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $this->assertEquals($result, $product->getDescription());
    }

    public function getDescriptionProvider()
    {
        return [
            [0, '32” Tv'],
        ];
    }

    /**
     * @dataProvider getPriceProvider
     */
    public function testGetPrice($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $this->assertEquals($result, $product->getPrice());
    }

    public function getPriceProvider()
    {
        return [
            [0, '399.99'],
            [10, '0'],
            [14, '4.33'],
        ];
    }

    /**
     * @dataProvider getQuantityProvider
     */
    public function testGetQuantity($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $this->assertEquals($result, $product->getQuantity());
    }

    public function getQuantityProvider()
    {
        return [
            [10, '0'],
            [17, '0'],
        ];
    }

    /**
     * @dataProvider getDiscontinuedProvider
     */
    public function testGetDiscontinued($data, $result)
    {
        $product = new ProductExtractor($this->items[$data]);
        $this->assertEquals($result, $product->getDiscontinued());
    }

    public function getDiscontinuedProvider()
    {
        return [
            [0, false],
            [2, true],
            [17, false],
        ];
    }
}