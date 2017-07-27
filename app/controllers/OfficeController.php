<?php

class OfficeController extends \BaseController {

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
				'data' => Office::all()
			]);
		}
		return View::make('maintenance.office.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('maintenance.office.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return Redirect::to('maintenance/office');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$office = Office::find($id);
		return View::make("maintenance.office.edit")
				->with('office',$office);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$deptcode = $this->sanitizeString(Input::get('deptcode'));
		$deptname = $this->sanitizeString(Input::get('deptname'));

		$office = Office::find($id);
		$office->deptcode = $deptcode;
		$office->deptname = $deptname;
		$office->save();
		Session::flash('success-message','Office Information Updated');
		return Redirect::to('maintenance/office');
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
			$office = Office::find($id);
			$office->delete();
			return json_encode('success');
		}

		try{
			$office = Office::find($id);
			$office->delete();
			Session::flash('success-message','Office Removed');	
		} catch (Exception $e) {
			Session::flash('error-message','Problem Encountered While Processing Your Data');
		} 
		return Redirect::to('maintenance/office');
	}

	public function getAllCodes()
	{
		if(Request::ajax())
		{
			return json_encode(Office::lists('deptcode'));
		}
	}

	public function getOfficeCode()
	{
		if(Request::ajax())
		{
			$code = $this->sanitizeString(Input::get('term'));
			return json_encode(Office::where('deptcode','like','%'.$code.'%')->lists('deptcode'));
		}
	}


}
