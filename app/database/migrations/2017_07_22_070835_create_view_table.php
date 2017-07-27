<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewTable extends Migration {

	/**
	 * Run the migrations.
	 *	
	 * @return void
	 */
	public function up()
	{
/*		DB::statement("CREATE VIEW rsmi AS
                        SELECT st.reference AS 'RIS',st.office AS 'Responsibility Center Code',st.stocknumber AS 'Stock No.',s.unit AS 'Unit',st.issuequantity AS 'Qty. Issued', CONCAT(MONTH(st.created_at),',',YEAR(st.created_at))
                        FROM supply AS s JOIN supplytransaction AS st ON s.stocknumber = st.stocknumber;");*/

/*		DB::statement("CREATE VIEW amoinventoryview AS
                        SELECT st.stocknumber,s.entityname,s.fundcluster,s.supplytype,s.unit,st.receiptquantity-st.issuequantity AS 'Balance Quantity',st.balancequantity,s.reorderpoint
	  					FROM supply AS s JOIN supplytransaction AS st ON s.stocknumber = st.stocknumber 
	 					GROUP BY stocknumber
	 					ORDER BY stocknumber DESC;");
*/
/*		DB::statement("CREATE VIEW accountinginventoryview AS
                        SELECT sl.stocknumber,s.entityname,s.fundcluster,s.supplytype,s.unit,sl.receiptquantity-sl.issuequantity AS 'Balance Quantity',TOP 1(sl.balancequantity),s.reorderpoint
	  					FROM supply AS s JOIN supplyledger AS sl ON s.stocknumber = sl.stocknumber 
	 					GROUP BY stocknumber
	 					ORDER BY stocknumber DESC;");*/
		/*DB::statement("CREATE PROCEDURE rsmi @month AS
			 SELECT st.reference AS 'RIS',st.office AS 'Responsibility Center Code',st.stocknumber AS 'Stock No.',s.unit AS 'Unit',st.issuequantity AS 'Qty. Issued', 
             FROM supply AS s JOIN supplytransaction AS st ON s.stocknumber = st.stocknumber
             WHERE MONTH(st.date) = @month;
			;");*/	/*CONCAT(MONTH(st.created_at),',',YEAR(st.created_at))*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
//		DB::statement("DROP VIEW rsmi ");
	}

}
