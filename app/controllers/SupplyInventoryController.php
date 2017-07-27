<?php

class SupplyInventoryController extends \BaseController {

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
		return View::make('inventory.supply.index');
	}

	public function getSupplyWithRemainingBalance($stocknumber)
	{
		if(Request::ajax())
		{
			return json_encode([
				'data' => Supply::groupBy('supplytype')
								->leftJoin('supplytransaction','supply.stocknumber','=','supplytransaction.stocknumber')
								->where('supply.stocknumber','=',$stocknumber)
								->select(
									'supply.supplytype',
									DB::raw('sum(receiptquantity) as totalreceiptquantity'),
									DB::raw('sum(issuequantity) as totalissuequantity')
									)
								->get()
			]);	
		}
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('inventory.supply.create');
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
		$description = $this->sanitizeString(Input::get('description'));
		$unit = $this->sanitizeString(Input::get('unit'));
		$reorderpoint = $this->sanitizeString(Input::get("reorderpoint"));
		$supplytype = $this->sanitizeString(Input::get('supplytype'));

		$validator = Validator::make([
			'Stock Number' => $stocknumber,
			'Entity Name' => $entityname,
			'Fund Cluster' => '0',
			'Supply Type' => $supplytype,
			'Unit' => $unit,
			'Unit Price' => 0,
			'Reorder Point' => $reorderpoint
		],Supply::$rules);

		if($validator->fails())
		{
			return Redirect::to('inventory/supply/add')
					->withInput()
					->withErrors($validator);
		}

		$supply = new Supply;
		$supply->stocknumber = $stocknumber;
		$supply->entityname = $entityname;
		$supply->supplytype = $supplytype;
		$supply->unit = $unit;
		$supply->reorderpoint = $reorderpoint;
		$supply->save();

		Session::flash('success-message','Supplies added to inventory.supply');
		return Redirect::to('inventory.supply');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		return Redirect::to('inventory.supply');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		return Redirect::to('inventory.supply');
	}

	public function rsmi()
	{
		if(Request::ajax())
		{
			if($month == 'undefined')
			$month = Carbon\Carbon::now();
			else{
				$month = Carbon\Carbon::parse($month);
			}

			return json_encode([
				'data' => SupplyTransaction::with('supply')
										->whereBetween('date',array($month->startOfMonth(),$month->endOfMonth()))
										->where('receiptquantity','=',null)
										->get()
			]);
		}
		return View::make('rsmi.index');
	}

	public function rsmiPerMonth($month)
	{
		if(Request::ajax())
		{
			if($month == 'undefined')
			$month = Carbon\Carbon::now();
			else{
				$month = Carbon\Carbon::parse($month);
			}

			return json_encode([
				'data' => SupplyTransaction::with('supply')
										->whereBetween('date',array($month->startOfMonth()->toDateString(),$month->endOfMonth()->toDateString()))
										->where('receiptquantity','=',null)
										->get()
			]);
		}
		return View::make('rsmi.index');
	}

	public function rsmiByStockNumber($month)
	{
		if(Request::ajax())
		{
			if($month == 'undefined')
			$month = Carbon\Carbon::now();
			else{
				$month = Carbon\Carbon::parse($month);
			}
			
			return json_encode([
				'data' => Supply::join('supplytransaction','supply.stocknumber','=','supplytransaction.stocknumber')
										->whereBetween('date',array($month->startOfMonth()->toDateString(),$month->endOfMonth()->toDateString()))
										->where('supplytransaction.receiptquantity','=',null)
										->groupBy('supply.stocknumber')
										->select(
											'supply.stocknumber',
											DB::raw('sum(supplytransaction.issuequantity) as issuequantity'),
											'supplytype',
											'unitprice'
											)
										->get()
			]);
		}
	}

	public function getAllMonths()
	{
		if(Request::ajax())
		{
			return json_encode([
				'data' => SupplyTransaction::where('receiptquantity','=',null)
											->groupBy(DB::raw(" MONTH(date)"))
											->orderBy('date','desc')
											->lists('date')
			]);
		}
	}


}
