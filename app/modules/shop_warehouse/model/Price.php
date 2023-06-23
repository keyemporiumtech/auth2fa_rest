<?php
App::uses("AppModel", "Model");
App::uses("CurrencyUtility", "modules/util_currency/utility");
App::uses("AmountUtility", "modules/shop_warehouse/utility");

/**
 * Entity Price
 *
 * @author Giuseppe Sassone
 *
 */
class Price extends AppModel {
    public $avoidConverter = false;
    public $avoidTotalSum = false;
    public $currencyFields = array(
        "price",
        "total",
        "iva",
        "discount",
        "tax",
    );
    public $floatFields = array(
        "price",
        "total",
        "iva",
        "iva_percent",
        "discount",
        "discount_percent",
        "tax",
    );
    public $arrayBelongsTo = array(
        'currency_fk' => array(
            'className' => 'Currency',
            'foreignKey' => 'currencyid',
        ),
    );

    public function beforeSave($options = array()) {
        if (!$this->skipBeforeSave) {
            if (!ArrayUtility::isEmpty($this->floatFields)) {
                AmountUtility::setFieldFloat($this->data, $this->alias, $this->floatFields);
            }
            if (empty($data[$this->alias]['currencyid'])) {
                $data[$this->alias]['currencyid'] = CurrencyUtility::getCurrencySystem()['Currency']['id'];
            }
            return parent::beforeSave($options);
        }
    }

    public function afterFind($results, $primary = false) {
        if (!$this->avoidConverter) {
            CurrencyUtility::setFieldCurrency($results, $this->alias, $this->currencyFields);
        }
        if (!$this->avoidTotalSum) {
            foreach ($results as &$obj) {
                if (array_key_exists($this->alias, $obj)) {
                    $RN_price = array_key_exists('price', $obj[$this->alias]) ? round($obj[$this->alias]['price'], 2) : 0.00;
                    $RN_iva = array_key_exists('iva', $obj[$this->alias]) ? round($obj[$this->alias]['iva'], 2) : 0.00;
                    $RN_discount = array_key_exists('discount', $obj[$this->alias]) ? round($obj[$this->alias]['discount'], 2) : 0.00;
                    $RN_tax = array_key_exists('tax', $obj[$this->alias]) ? round($obj[$this->alias]['tax'], 2) : 0.00;
                    $obj[$this->alias]['totalsum'] = round(($RN_price + $RN_iva - $RN_discount + $RN_tax), 2);
                }
            }
        }
        return parent::afterFind($results, $primary);
    }
}
