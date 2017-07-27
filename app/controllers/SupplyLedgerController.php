<?php

class SupplyLedgerController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($stocknumber)
	{
		if(Request::ajax())
		{
			return json_encode([
				'data' => SupplyLedger::where('stocknumber','=',$stocknumber)
										->groupBy('date')
										->select(
												'date',
												DB::raw('sum(receiptquantity) as receiptquantity'),
												DB::raw('avg(receiptunitprice) as receiptunitprice'),
												DB::raw('sum(issuequantity) as issuequantity'),
												DB::raw('avg(issueunitprice) as issueunitprice')
												)
										->get()
			]);
		}
		$supply = Supply::find($stocknumber);
		return View::make('supplyledger.index')
				->with('supply',$supply);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
		$supply = Supply::find($id);
		return View::make('supplyledger.create')
				->with('supply',$supply);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
		$reference = $this->sanitizeString(Input::get('reference'));
		$date = $this->sanitizeString(Input::get('date'));
		$receiptquantity = $this->sanitizeString(Input::get('quantity'));
		$daystoconsume = $this->sanitizeString(Input::get('daystoconsume'));

		$supply = Supply::find($stocknumber);
		$receiptunitprice = $supply->unitprice;

		$validator = Validator::make([	
			'Date' => $date,
			'Stock Number' => $stocknumber,
			'Purchase Order' => $reference,
			'Receipt Quantity' => $receiptquantity,
			'Receipt Unit Price' => $receiptunitprice,
			'Days To Consume' => $daystoconsume
		],SupplyLedger::$receiptRules);

		if($validator->fails())
		{
			return Redirect::to("inventory/supply/$stocknumber/supplyledger/create")
					->withInput()
					->withErrors($validator);
		}

		$transaction = new SupplyLedger;
		$transaction->stocknumber = $stocknumber;
		$transaction->reference = $reference;
		$transaction->date = Carbon\Carbon::parse($date);
		$transaction->receiptquantity = $receiptquantity;
		$transaction->receiptunitprice = $receiptunitprice;
		$transaction->daystoconsume = $daystoconsume;
		$transaction->save();

		Session::flash('success-message','Operation Successful');
		return Redirect::to("inventory/supply/$stocknumber/supplyledger");
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
			$transaction = SupplyTransaction::with('supply')->where('stocknumber','=',$this->sanitizeString($id))->get();
			return json_encode([ 'data' => $transaction ]);
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
		return Redirect::to('inventory/supply/ledger');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		return Redirect::to('inventory/supply');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
		$reference = $this->sanitizeString(Input::get('reference'));
		$date = $this->sanitizeString(Input::get('date'));
		$issuequantity = $this->sanitizeString(Input::get('quantity'));
		$daystoconsume = $this->sanitizeString(Input::get('daystoconsume'));

		$supply = Supply::find($stocknumber);
		$issueunitprice = $supply->unitprice;


		$validator = Validator::make([	
			'Date' => $date,
			'Stock Number' => $stocknumber,
			'Requisition and Issue Slip' => $reference,
			'Issue Quantity' => $issuequantity,
			'Issue Unitprice' => $issueunitprice,
			'Days To Consume' => $daystoconsume
		],SupplyLedger::$issueRules);

		if($validator->fails())
		{
			return Redirect::to("inventory/supply/$stocknumber/supplyledger/release")
					->withInput()
					->withErrors($validator);
		}

		$transaction = new SupplyLedger;
		$transaction->stocknumber = $stocknumber;
		$transaction->reference = $reference;
		$transaction->date = Carbon\Carbon::parse($date);
		$transaction->issuequantity = $issuequantity;
		$transaction->issueunitprice = $issueunitprice;
		$transaction->daystoconsume = $daystoconsume;
		$transaction->save();

		Session::flash('success-message','Operation Successful');
		return Redirect::to("inventory/supply/$stocknumber/supplyledger");
	}

	public function releaseForm($id)
	{
		$supply = Supply::find($id);
		$balance = SupplyLedger::where('stocknumber','=',$supply->stocknumber)->get();
		$balance = $balance->sum('receiptquantity') - $balance->sum('issuequantity');
		return View::make('supplyledger.release')
				->with('supply',$supply)
				->with('balance',$balance);
	}

}
