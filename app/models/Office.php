<?php

class Office extends Eloquent{

	protected $table = 'office';

	
	protected $fillable = ['deptcode','deptname'];
	protected $primaryKey = 'deptcode';
	public $timestamps = false;
	
	public static $rules = array(
		'Department Code' => 'required|max:20',
		'Department Name' => 'required|max:200'
	);

	public static $updateRules = array(
		'Department Code' => 'required|max:20',
		'Department Name' => 'required|max:200'
	);

}

			
