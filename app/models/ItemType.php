<?php

class ItemType extends Eloquent{

	protected $table = 'itemtype';

	public $timestamps = false;

	protected $fillable = ['itemtype','description'];
	protected $primaryKey = 'id';
	public static $rules = array(
		'Item Type' => 'required',
		'Description' => ''
	);

	public static $updateRules = array(
		'Item Type' => 'required',
		'Description' => ''
	);

}
