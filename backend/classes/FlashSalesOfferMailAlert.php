<?php
class FlashSalesOfferMailAlert extends ObjectModel
{
	public $id
	public $id_flashsales_offer;
	public $id_customer;
	public $customer_email;

	public $date_add;
	public $date_upd;

	protected $table = 'flashsales_offer_mailalert';
	protected $identifier = 'id_flashsales_offer_mailalert';
	protected $fieldsRequired = array('customer_email');
	protected $fieldsSize = array('customer_email' => 128);

	protected $fieldsValidate = array(
		'customer_email' => 'isEmail',
		'id_flashsales_offer' => 'isUnsignedId',
		'id_customer' => 'isUnsignedId'
	);

	public function getFields()
	{
		parent::validateFields();
		if (isset($this->id))
			$fields['id_flashsales_offer_mailalert'] = (int)($this->id);
		$fields['id_flashsales_offer'] = (int)($this->id_flashsales_offer);
		$fields['id_customer'] = (int)($this->id_customer);
		$fields['customer_email'] = pSQL($this->customer_email);

		$fields['date_add']	 = pSQL($this->date_add);
		$fields['date_upd']	 = pSQL($this->date_upd);

		return $fields;
	}
}

?>