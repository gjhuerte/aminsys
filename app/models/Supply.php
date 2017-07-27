<?php

class Supply extends Eloquent{

	protected $table = 'supply';
	protected $fillable = ['stocknumber','entityname','fundcluster','supplytype','unit','unitprice','reorderpoint'];
	protected $primaryKey = 'stocknumber';
	public $timestamps = true;
	public static $rules = array(
	'Stock Number' => 'required|unique:supply,stocknumber',
	'Entity Name' => 'required',
	'Fund Cluster' => '',
	'Supply Type' => 'required|unique:supply,supplytype',
	'Unit' => 'required',
	'Unit Price' => '',	
	'Reorder Point' => 'required|integer'
	);

	public static $updateRules = array(
	'Stock Number' => '',
	'Entity Name' => '',
	'Fund Cluster' => '',
	'Supply Type' => '',
	'Unit' => '',
	'Unit Price' => '',
	'Reorder Point' => 'integer'
	);

	public function supplytransaction()
	{
		return $this->hasMany('supplytransaction','stocknumber','stocknumber');
	}

	public function getUnitPriceAttribute($value)
	{
		return number_format($value,2,'.',',');
	}

}
