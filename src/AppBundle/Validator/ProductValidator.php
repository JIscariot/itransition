<?php

namespace AppBundle\Validator;

use AppBundle\Extractor\ProductExtractor;

class ProductValidator
{
    const MIN_PRICE = 5;
    const MAX_PRICE = 1000;
    const MIN_QUANTITY = 10;

    /**
     * @var Product
     */
    private $product;

    /**
     * ProductValidator constructor.
     * @param ProductExtractor $product
     */
    public function __construct(ProductExtractor $product)
    {
        $this->product = $product;
    }

    /**
     * @return bool
     */
    public function validateName()
    {
        return !empty($this->product->getName()) ? true : false;
    }

    /**
     * @return bool
     */
    public function validateCode()
    {
        return !empty($this->product->getCode()) ? true : false;
    }

    /**
     * @return bool
     */
    public function validateDescription()
    {
        return !empty($this->product->getDescription()) ? true : false;
    }

    /**
     * @return bool
     */
    public function validatePrice()
    {
        if (is_numeric($this->product->getPrice()) ||
            is_float($this->product->getPrice())
        ) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function validateQuantity()
    {
        return is_numeric($this->product->getQuantity()) ? true : false;
    }

    /**
     * @return bool
     */
    public function validateMinPriceAndQuantity()
    {
        if (self::MIN_PRICE > $this->product->getPrice() &&
            self::MIN_QUANTITY > $this->product->getQuantity()
        ) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function validateMaxPrice()
    {
        if (self::MAX_PRICE > $this->product->getPrice()) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (
            $this->validateName() &&
            $this->validateCode() &&
            $this->validateDescription() &&
            $this->validatePrice() &&
            $this->validateQuantity() &&
            $this->validateMinPriceAndQuantity() &&
            $this->validateMaxPrice()
        ) {
            return true;
        }

        return false;
    }
}