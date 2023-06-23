<?php
App::uses("AppModel", "Model");
App::uses("CurrencyUtility", "modules/util_currency/utility");
App::uses("AmountUtility", "modules/shop_warehouse/utility");

/**
 * Entity Producttax
 * 
 * @author Giuseppe Sassone
 *
 */
class Producttax extends AppModel {
	public $avoidConverter= false;
	public $currencyFields= array (
			"tax" 
	);
	public $floatFields= array (
			"tax",
			"tax_percent" 
	);
	public $arrayBelongsTo= array (
			'product_fk' => array (
					'className' => 'Product',
					'foreignKey' => 'product' 
			),
			'currency_fk' => array (
					'className' => 'Currency',
					'foreignKey' => 'currencyid' 
			) 
	);

	public function beforeSave($options= array()) {
		if (! $this->skipBeforeSave) {
			if (! ArrayUtility::isEmpty($this->floatFields)) {
				AmountUtility::setFieldFloat($this->data, $this->alias, $this->floatFields);
			}
			if (empty($data [$this->alias] ['currencyid'])) {
				$data [$this->alias] ['currencyid']= CurrencyUtility::getCurrencySystem() ['Currency'] ['id'];
			}
			return parent::beforeSave($options);
		}
	}

	public function afterFind($results, $primary= false) {
		if (! $this->avoidConverter) {
			CurrencyUtility::setFieldCurrency($results, $this->alias, $this->currencyFields);
		}
		return parent::afterFind($results, $primary);
	}
}
