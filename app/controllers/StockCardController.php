<?php

class StockCardController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getAllStockNumber()
	{
		if(Request::ajax())
		{
			return json_encode(Supply::lists('stocknumber'));
		}
	}

	public function getSupplyStockNumber()
	{
		if(Request::ajax())
		{
			$stocknumber = $this->sanitizeString(Input::get('term'));
			return json_encode(Supply::where('stocknumber','like','%'.$stocknumber.'%')->lists('stocknumber'));
		}
	}

	public function index($stocknumber)
	{
		if(Request::ajax())
		{
			return json_encode([
				'data' => SupplyTransaction::where('stocknumber','=',$stocknumber)
											->orderBy('date','asc')
											->get()
			]);
		}
		$supply = Supply::find($stocknumber);
		return View::make('stockcard.index')
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
		return View::make('stockcard.create')
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
		$office = $this->sanitizeString(Input::get('office'));
		$daystoconsume = $this->sanitizeString(Input::get('daystoconsume'));

		$validator = Validator::make([	
			'Stock Number' => $stocknumber,
			'Purchase Order' => $reference,
			'Date' => $date,
			'Receipt Quantity' => $receiptquantity,
			'Office' => $office,
			'Days To Consume' => $daystoconsume
		],SupplyTransaction::$receiptRules);

		if($validator->fails())
		{
			return Redirect::to("inventory/supply/$stocknumber/stockcard/create")
					->withInput()
					->withErrors($validator);
		}

		$supply = Supply::find($stocknumber);

		$balance = 0;

		try{
			$supplytransaction = SupplyTransaction::where('id','=',$supply->id)
												->orderBy('created_at','desc')
												->select('balancequantity')
												->first();

			$balance = $supplytransaction->balancequantity + $receiptquantity;	
		} catch ( Exception $e ) {

			$balance = $receiptquantity;	
		}

		$transaction = new SupplyTransaction;
		$transaction->stocknumber = $stocknumber;
		$transaction->reference = $reference;
		$transaction->date = Carbon\Carbon::parse($date);
		$transaction->receiptquantity = $receiptquantity;
		$transaction->office = $office;
		$transaction->daystoconsume = $daystoconsume;
		$transaction->save();

		Session::flash('success-message','Operation Successful');
		return Redirect::to("inventory/supply/$stocknumber/stockcard");
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
	public function edit($stocknumber,$id)
	{
		return Redirect::to("inventory/supply/$stocknumber/stockcard/$id/edit");
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($stocknumber,$id)
	{
		return Redirect::to("inventory/supply/$stocknumber/stockcard");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($supplyId,$id)
	{
		
		$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
		$reference = $this->sanitizeString(Input::get('reference'));
		$date = $this->sanitizeString(Input::get('date'));
		$issuequantity = $this->sanitizeString(Input::get('quantity'));
		$office = $this->sanitizeString(Input::get('office'));
		$daystoconsume = $this->sanitizeString(Input::get('daystoconsume'));

		$validator = Validator::make([	
			'Stock Number' => $stocknumber,
			'Requisition and Issue Slip' => $reference,
			'Date' => $date,
			'Issue Quantity' => $issuequantity,
			'Office' => $office,
			'Days To Consume' => $daystoconsume
		],SupplyTransaction::$issueRules);

		if($validator->fails())
		{
			return Redirect::to("inventory/supply/$stocknumber/stockcard/release")
					->withInput()
					->withErrors($validator);
		}

		$supply = Supply::find($stocknumber);

		$balance = 0;

		try{
			$supplytransaction = SupplyTransaction::where('stocknumber','=',$supply->stocknumber)
												->orderBy('created_at','desc')
												->get();
			$balance = $supplytransaction->sum('receiptquantity') + $supplytransaction->sum('issuequantity');	

			if($balance == 0)
			{		
				Session::flash("error-message","Supply has no more items to issue");
				return Redirect::to("inventory/supply/$stocknumber/stockcard/release")
						->withInput();
			} else if($balance < $issuequantity ) {
				Session::flash("error-message","Issue quantity must be lesser than remaining balance");
				return Redirect::to("inventory/supply/$stocknumber/stockcard/release")
						->withInput();
			}
		} catch ( Exception $e ) {
			Session::flash("error-message","Supply has 0 quantity");
			return Redirect::to("inventory/supply/$stocknumber/stockcard/release")
					->withInput();
		}

		$transaction = new SupplyTransaction;
		$transaction->stocknumber = $stocknumber;
		$transaction->reference = $reference;
		$transaction->date = Carbon\Carbon::parse($date);
		$transaction->issuequantity = $issuequantity;
		$transaction->office = $office;
		$transaction->daystoconsume = $daystoconsume;
		$transaction->save();

		Session::flash('success-message','Operation Successful');
		return Redirect::to("inventory/supply/$stocknumber/stockcard");
	}

	public function releaseForm($id)
	{
		$supply = Supply::find($id);
		$balance = SupplyTransaction::where('stocknumber','=',$supply->stocknumber)->get();
		$balance = $balance->sum('receiptquantity') - $balance->sum('issuequantity');
		return View::make('stockcard.release')
				->with('supply',$supply)
				->with('balance',$balance);
	}

	public function batchAcceptForm()
	{
		return View::make('stockcard.batch.accept');
	}

	public function batchAccept()
	{
		Session::flash('success-message','Supplies Accepted');
		return Redirect::to('inventory/supply');
	}

	public function batchReleaseForm()
	{
		return View::make('stockcard.batch.release');
	}

	public function batchRelease()
	{

		$stocknumber = Input::get("stocknumber");
		$reference = $this->sanitizeString(Input::get('reference'));
		$date = $this->sanitizeString(Input::get('date'));
		$receiptquantity = Input::get("quantity");
		$office = $this->sanitizeString(Input::get('office'));
		$daystoconsume = $this->sanitizeString(Input::get("daystoconsume"));

		foreach($stocknumber as $_stocknumber)
		{
			$validator = Validator::make([	
				'Stock Number' => $_stocknumber,
				'Reference' => $reference,
				'Date' => $date,
				'Receipt Quantity' => $receiptquantity["$_stocknumber"],
				'Office' => $office,
				'Days To Consume' => $daystoconsume
			],SupplyTransaction::$rules);

			if($validator->fails())
			{
				return Redirect::to("inventory/supply/batch/form/release")
						->with('total',count($stocknumber))
						->with('stocknumber',$stocknumber)
						->with('quantity',$receiptquantity)
						->with('daystoconsume',$daystoconsume)
						->withInput()
						->withErrors($validator);
			}
		}

		foreach($stocknumber as $_stocknumber)
		{
			$transaction = new SupplyTransaction;
			$transaction->stocknumber = $this->sanitizeString($_stocknumber);
			$transaction->reference = $reference;
			$transaction->date = Carbon\Carbon::parse($date);
			$transaction->receiptquantity = $receiptquantity["$_stocknumber"];
			$transaction->office = $office;
			$transaction->daystoconsume = $daystoconsume;
			$transaction->save();
		}

		Session::flash('success-message','Supplies Released');
		return Redirect::to('inventory/supply');
	}


}
