<?php

/**
 * Adobe_EmiAssignment
 *
 * @category  PHP
 * @package   Adobe_EmiAssignment
 * @author    Adobe <support@adobe.com>
 * @copyright 2022 Copyright (c) Adobe
 * @link      https://www.adobe.com/
 */

namespace Adobe\EmiAssignment\Block\Catalog\Product\View;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Block\Product\View as ProductViewBlock;
use Magento\Framework\Locale\FormatInterface;

class Emi extends Template
{
    /**
     * @var Json
     */
    private $json;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ProductViewBlock
     */
    private $productViewBlock;

    /**
     * @var FormatInterface
     */
    private $localeFormat;

    /**
     * @param Template\Context $context
     * @param Json $json
     * @param SerializerInterface $serializer
     * @param ScopeConfigInterface $scopeConfig
     * @param ProductViewBlock $productViewBlock
     * @param FormatInterface $localeFormat
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Json $json,
        SerializerInterface $serializer,
        ScopeConfigInterface $scopeConfig,
        ProductViewBlock $productViewBlock,
        FormatInterface $localeFormat,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->json = $json;
        $this->serializer = $serializer;
        $this->scopeConfig = $scopeConfig;
        $this->productViewBlock = $productViewBlock;
        $this->localeFormat = $localeFormat;
    }

    /**
     * Provide json config to the component
     *
     * @return bool|string
     */
    public function getJsonConfig()
    {
        $emiOptions = $this->scopeConfig->getValue('adobe_emiassignment/general/emi_options');
        if ($emiOptions) {
            if (is_string($emiOptions)) {
                $emiOptions = $this->json->unserialize($emiOptions);
            }

            $emiOptions = array_values($emiOptions);
            $emiOptions = $this->json->serialize($emiOptions);
        }

        $product = $this->productViewBlock->getProduct();
        $initialPrice = $product->getFinalPrice(1);

        return $this->serializer->serialize([
            'storeConfigEmiOptions' => $emiOptions,
            'initialPrice' => $initialPrice,
            'priceFormat' => $this->localeFormat->getPriceFormat()
        ]);
    }

    /**
     * Supports only simple and configurable products
     *
     * @return bool
     */
    public function isProductTypeSupported()
    {
        $productType = $this->productViewBlock->getProduct()->getTypeId();
        return in_array($productType, [
            'simple',
            'configurable'
        ]);
    }
}
