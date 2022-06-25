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

namespace Adobe\EmiAssignment\Block\Adminhtml\System\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class DynamicRowsEmiOptions extends AbstractFieldArray
{
    /**
     * @var DynamicRows\Gender
     */
    private $genderRenderer;

    /**
     * Prepare rendering the new field by adding all the needed columns
     */
    protected function _prepareToRender()
    {
        $this->addColumn('interest_rate', [
            'label' => __('Interest Rate'),
            'class' => 'required-entry validate-number'
        ]);

        $this->addColumn('tenure_month', [
            'label' => __('Tenure (Months)'),
            'class' => 'required-entry validate-number'
        ]);

        $this->addColumn('gender', [
            'label' => __('Gender'),
            'class' => 'required-entry',
            'renderer' => $this->getGenderRenderer()
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        $emiOptions = $row->getEmiOptions();
        if ($emiOptions !== null) {
            $options['option_' . $this->getGenderRenderer()->calcOptionHash($cmsPage)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * Return gender column
     *
     * @return DynamicRows\Gender
     * @throws LocalizedException
     */
    private function getGenderRenderer()
    {
        if (!$this->genderRenderer) {
            $this->genderRenderer = $this->getLayout()->createBlock(
                DynamicRows\Gender::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->genderRenderer;
    }
}
