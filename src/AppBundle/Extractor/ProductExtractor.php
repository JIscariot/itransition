<?php

namespace AppBundle\Extractor;

class ProductExtractor
{
    /**
     * @var array;
     */
    private $item;

    /**
     * ProductMapper constructor.
     * @param array $item
     */
    public function __construct(array $item)
    {
        $this->item = $item;
    }

    /**
     * @return mixed|null
     */
    public function getName()
    {
        return isset($this->item['Product Name']) ? $this->item['Product Name'] : NULL;
    }

    /**
     * @return mixed|null
     */
    public function getCode()
    {
        return isset($this->item['Product Code']) ? $this->item['Product Code'] : NULL;
    }

    /**
     * @return mixed|null
     */
    public function getDescription()
    {
        return isset($this->item['Product Description']) ? $this->item['Product Description'] : NULL;
    }

    /**
     * @return integer|float
     */
    public function getPrice()
    {
        $price = isset($this->item['Cost in GBP']) ?
            filter_var($this->item['Cost in GBP'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : 0;

        return !empty($price) ? $price : 0;
    }

    /**
     * @return integer
     */
    public function getQuantity()
    {
        $quantity = isset($this->item['Stock']) ?
            filter_var($this->item['Stock'], FILTER_SANITIZE_NUMBER_INT) : 0;

        return !empty($quantity) ? $quantity : 0;
    }

    /**
     * @return boolean
     */
    public function getDiscontinued()
    {
        $discontinued = isset($this->item['Discontinued']) ?
            $this->item['Discontinued'] : NULL;

        return $discontinued == 'yes' ? true : false;
    }
}