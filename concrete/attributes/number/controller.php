<?php
namespace Concrete\Attribute\Number;

use Concrete\Core\Attribute\FontAwesomeIconFormatter;
use Concrete\Core\Entity\Attribute\Key\Type\NumberType;
use Concrete\Core\Entity\Attribute\Value\Value\NumberValue;
use Loader;
use Concrete\Core\Attribute\Controller as AttributeTypeController;

class Controller extends AttributeTypeController
{
    protected $searchIndexFieldDefinition = array(
        'type' => 'decimal',
        'options' => array('precision' => 14, 'scale' => 4, 'default' => 0, 'notnull' => false),
    );

    public function getIconFormatter()
    {
        return new FontAwesomeIconFormatter('hashtag');
    }

    public function getDisplayValue()
    {
        return floatval($this->attributeValue->getValue());
    }

    public function searchForm($list)
    {
        $numFrom = intval($this->request('from'));
        $numTo = intval($this->request('to'));
        if ($numFrom) {
            $list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $numFrom, '>=');
        }
        if ($numTo) {
            $list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $numTo, '<=');
        }

        return $list;
    }

    public function search()
    {
        $f = Loader::helper('form');
        $html = $f->number($this->field('from'), $this->request('from'));
        $html .= ' ' . t('to') . ' ';
        $html .= $f->number($this->field('to'), $this->request('to'));
        echo $html;
    }

    public function form()
    {
        if (is_object($this->attributeValue)) {
            $value = $this->getAttributeValue()->getValue();
        }
        echo Loader::helper('form')->number($this->field('value'), $value, array('style' => 'width:80px'));
    }

    public function validateForm($p)
    {
        return $p['value'] != false;
    }

    public function validateValue()
    {
        $val = $this->getValue();

        return $val !== null && $val !== false;
    }

    // run when we call setAttribute(), instead of saving through the UI
    public function saveValue($value)
    {
        $av = new NumberValue();
        $value = ($value == false || $value == '0') ? 0 : $value;
        $av->setValue($value);

        return $av;
    }

    public function saveForm($data)
    {
        if (isset($data['value'])) {
            return $this->saveValue($data['value']);
        }
    }

    public function createAttributeKeyType()
    {
        return new NumberType();
    }
}
