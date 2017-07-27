<?php

class SupplyTransaction extends Eloquent{

	protected $table = 'supplytransaction';

	public $timestamps = true;
	protected $fillable = ['date','stocknumber','reference','receiptquantity','issuequantity','office','daystoconsume'];
/*	protected $fillable = ['date','stocknumber','reference','referencetag','quantity','balancequantity','office','daystoconsume'];*/	
	protected $primaryKey = 'id';
	public static $rules = array(
	'Date' => 'required',
	'Stock Number' => 'required',
	'Reference' => 'required',
	'Office' => '',
	'Receipt Quantity' => 'integer',
	'Issue Quantity' => 'integer',
	'Days To Consume' => ''
	);

	public static $receiptRules = array(
	'Date' => 'required',
	'Stock Number' => 'required',
	'Purchase Order' => 'required',
	'Office' => '',
	'Receipt Quantity' => 'required|integer',
	'Days To Consume' => ''
	);

	public static $issueRules = array(
	'Date' => 'required',
	'Stock Number' => 'required',
	'Requisition and Issue Slip' => 'required',
	'Office' => '',
	'Issue Quantity' => 'required|integer',
	'Days To Consume' => ''
	);

	public static $updateRules = array(
	'Date' => '',
	'Stock Number' => '',
	'Reference' => '',
	'Office' => '',
	'Receipt Quantity' => 'integer',
	'Issue Quantity' => 'integer',
	'Days To Consume' => ''
	/*
	'Date' => 'required',
	'Stock Number' => 'required',
	'Reference' => 'required',
	'Referencetag' => 'required',
	'Office' => 'required',
	'Quantity' => 'integer',
	'Balance Quantity' => 'integer',
	'Days To Consume' => 'required'
	*/
	);

	public function getDateAttribute($value)
	{
		return Carbon\Carbon::parse($value)->format('F d Y');
	}

	public function supply()
	{
		return $this->belongsTo('Supply','stocknumber','stocknumber');
	}

}
	