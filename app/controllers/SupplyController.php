<?php

class SupplyController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Request::ajax())
		{
			return json_encode([
				'data' => Supply::all()
			]);
		}
		return View::make('maintenance.supply.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('maintenance.supply.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
		$entityname = $this->sanitizeString(Input::get('entityname'));
		$fundcluster = $this->sanitizeString(Input::get('fundcluster'));
		$description = $this->sanitizeString(Input::get('description'));
		$unit = $this->sanitizeString(Input::get('unit'));
		$price = $this->sanitizeString(Input::get('price'));
		$reorderpoint = $this->sanitizeString(Input::get("reorderpoint"));
		$supplytype = $this->sanitizeString(Input::get('supplytype'));

		$validator = Validator::make([
			'Stock Number' => $stocknumber,
			'Entity Name' => $entityname,
			'Fund Cluster' => $fundcluster,
			'Supply Type' => $supplytype,
			'Unit' => $unit,
			'Unit Price' => $price,
			'Reorder Point' => $reorderpoint
		],Supply::$rules);

		if($validator->fails())
		{
			return Redirect::to('maintenance/supply/create')
					->withInput()
					->withErrors($validator);
		}

		$supply = new Supply;
		$supply->stocknumber = $stocknumber;
		$supply->entityname = $entityname;
		$supply->supplytype = $supplytype;
		$supply->fundcluster = $fundcluster;
		$supply->unit = $unit;
		$supply->unitprice = $price;
		$supply->reorderpoint = $reorderpoint;
		$supply->save();

		Session::flash('success-message','Supplies added to inventory');
		return Redirect::to('maintenance/supply');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Request::ajax())
		{
			$supply = Supply::find($id);
			return json_encode([ 'data' => $supply ]);
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$supply = Supply::find($id);
		return View::make('maintenance.supply.edit')
				->with('supply',$supply);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
		$entityname = $this->sanitizeString(Input::get('entityname'));
		$fundcluster = $this->sanitizeString(Input::get('fundcluster'));
		$description = $this->sanitizeString(Input::get('description'));
		$unit = $this->sanitizeString(Input::get('unit'));
		$price = $this->sanitizeString(Input::get('price'));
		$reorderpoint = $this->sanitizeString(Input::get("reorderpoint"));
		$supplytype = $this->sanitizeString(Input::get('supplytype'));

		$validator = Validator::make([
			'Stock Number' => $stocknumber,
			'Entity Name' => $entityname,
			'Fund Cluster' => $fundcluster,
			'Supply Type' => $supplytype,
			'Unit' => $unit,
			'Unit Price' => $price,
			'Reorder Point' => $reorderpoint
		],Supply::$updateRules);

		if($validator->fails())
		{
			return Redirect::to("maintenance/supply/$id/edit")
					->withInput()
					->withErrors($validator);
		}

		$supply = Supply::find($id);
		$supply->stocknumber = $stocknumber;
		$supply->entityname = $entityname;
		$supply->supplytype = $supplytype;
		$supply->fundcluster = $fundcluster;
		$supply->unit = $unit;
		$supply->unitprice = $price;
		$supply->reorderpoint = $reorderpoint;
		$supply->save();

		Session::flash('success-message','Supplies added to inventory');
		return Redirect::to('maintenance/supply');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Request::ajax())
		{
			$supply = Supply::find($id);
			$supply->delete();
			return json_encode('success');
		}

		try{
			$supply = Supply::find($id);
			$supply->delete();
			Session::flash('success-message','Office Removed');	
		} catch (Exception $e) {
			Session::flash('error-message','Problem Encountered While Processing Your Data');
		} 

		return Redirect::to('maintenance/supply');
	}


}
