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

namespace Adobe\EmiAssignment\Block\Adminhtml\System\Form\Field\DynamicRows;

use Magento\Framework\View\Element\Html\Select;

class Gender extends Select
{
    /**
     * Set "name" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Set "id" for <select> element
     *
     * @param mixed $value
     * @return $this
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }
        return parent::_toHtml();
    }

    /**
     * Return gender options
     *
     * @return array[]
     */
    private function getSourceOptions(): array
    {
        return [
            ['value' => 1, 'label' => __('Male')],
            ['value' => 2, 'label' => __('Female')]
        ];
    }
}
