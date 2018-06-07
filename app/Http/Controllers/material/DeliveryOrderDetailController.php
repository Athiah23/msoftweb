<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class DeliveryOrderDetailController extends defaultController
{   
    var $gltranAmount;
    var $srcdocno;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function form(Request $request)
    {   
        // return $this->request_no('GRN','2FL');
        $delordhd = DB::table('material.delordhd')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$request->recno)
            ->first();
        $this->srcdocno = $delordhd->srcdocno;

        switch($request->oper){
            case 'add':

                if($delordhd->srcdocno != 0){
                    return $this->add_from_PO($request);
                }else{
                    return $this->add($request);
                }

            case 'edit':

                if($delordhd->srcdocno != 0){
                    return $this->edit_from_PO($request);
                }else{
                    return $this->edit($request);
                }

            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }

    public function get_draccno($itemcode){
        $query = DB::table('material.category')
                ->select('category.stockacct')
                ->join('material.product', 'category.catcode', '=', 'product.productcat')
                ->where('product.itemcode','=',$itemcode)
                ->first();
        
        return $query->stockacct;
    }

    public function get_drccode($deldept){
        $query = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deldept)
                ->first();
        
        return $query->costcode;
    }

    public function get_craccno(){
        $query = DB::table('sysdb.sysparam')
                ->select('pvalue2')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','ACC')
                ->first();
        
        return $query->pvalue2;
    }

    public function get_crccode(){
        $query = DB::table('sysdb.sysparam')
                ->select('pvalue1')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','ACC')
                ->first();
        
        return $query->pvalue1;
    }

    public function chgDate($date){
        if(!empty($date)){
            $newstr=explode("/", $date);
            return $newstr[2].'-'.$newstr[1].'-'.$newstr[0];
        }else{
            return 'NULL';
        }
    }

    public function add(Request $request){

        $draccno = $this->get_draccno($request->itemcode);
        $drccode = $this->get_drccode($request->deldept);
        $craccno = $this->get_craccno();
        $crccode = $this->get_crccode();

        $recno = $request->recno;
        $suppcode = $request->suppcode;
        $trandate = $request->trandate;
        $deldept = $request->deldept;
        $deliverydate = $request->deliverydate;

        DB::beginTransaction();

        try {
            ////1. calculate lineno_ by recno
            $sqlln = DB::table('material.delorddt')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno)
                        ->count('lineno_');

            $li=intval($sqlln)+1;

            ///2. insert detail
            DB::table('material.delorddt')
                ->insert([
                    'compcode' => session('compcode'),
                    'recno' => $recno,
                    'lineno_' => $li,
                    'pricecode' => $request->pricecode,
                    'itemcode' => $request->itemcode,
                    'uomcode' => $request->uomcode,
                    'pouom' => $request->pouom,
                    'suppcode' => $request->suppcode,
                    'trandate' => $request->trandate,
                    'deldept' => $request->deldept,
                    'deliverydate' => $request->deliverydate,
                    'qtyorder' => $request->qtyorder,
                    'qtydelivered' => $request->qtydelivered,
                    'qtytag' => $request->qtytag,
                    'unitprice' => $request->unitprice,
                    'taxcode' => $request->taxcode,
                    'perdisc' => $request->perdisc,
                    'amtdisc' => $request->amtdisc,
                    'amtslstax' => $request->tot_gst,
                    'netunitprice' => $request->netunitprice,
                    /*'qtydelivered' => $request->qtydelivered,*/
                    'amount' => $request->amount,
                    'totamount' => $request->totamount,
                    'draccno' => $draccno,
                    'drccode' => $drccode,
                    'craccno' => $craccno,
                    'crccode' => $crccode, 
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate' => $this->chgDate($request->expdate), 
                    'batchno' => $request->batchno, 
                    'recstatus' => 'OPEN', 
                    'remarks' => $request->remarks
                ]);

            ///3. calculate total amount from detail
            $totalAmount = DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amtslstax');

            ///4. then update to header
            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);

            echo $totalAmount;

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {


            ///1. update detail
            DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'pricecode' => $request->pricecode, 
                    'itemcode'=> $request->itemcode, 
                    'uomcode'=> $request->uomcode, 
                    'pouom'=> $request->pouom, 
                    'qtyorder'=> $request->qtyorder, 
                    'qtydelivered'=> $request->qtydelivered, 
                    'unitprice'=> $request->unitprice,
                    'taxcode'=> $request->taxcode, 
                    'perdisc'=> $request->perdisc, 
                    'amtdisc'=> $request->amtdisc, 
                    'amtslstax'=> $request->tot_gst, 
                    'netunitprice'=> $request->netunitprice, 
                    'amount'=> $request->amount, 
                    'totamount'=> $request->totamount, 
                    'upduser'=> session('username'), 
                    'upddate'=> Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate'=> $this->chgDate($request->expdate),  
                    'batchno'=> $request->batchno, 
                    'remarks'=> $request->remarks
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);
            
            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([ 
                    'deluser' => session('username'), 
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'DELETE'
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);

            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

        
    }

    public function add_from_PO(Request $request){

        $draccno = $this->get_draccno($request->itemcode);
        $drccode = $this->get_drccode($request->deldept);
        $craccno = $this->get_craccno();
        $crccode = $this->get_crccode();

        $recno = $request->recno;
        $suppcode = $request->suppcode;
        $trandate = $request->trandate;
        $deldept = $request->deldept;
        $deliverydate = $request->deliverydate;

        DB::beginTransaction();

        try {
            ////1. calculate lineno_ by recno
            $sqlln = DB::table('material.delorddt')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno)
                        ->count('lineno_');

            $li=intval($sqlln)+1;

            ///2. insert detail
            DB::table('material.delorddt')
                ->insert([
                    'compcode' => session('compcode'),
                    'recno' => $recno,
                    'lineno_' => $li,
                    'pricecode' => $request->pricecode,
                    'itemcode' => $request->itemcode,
                    'uomcode' => $request->uomcode,
                    'pouom' => $request->pouom,
                    'suppcode' => $request->suppcode,
                    'trandate' => $request->trandate,
                    'deldept' => $request->deldept,
                    'deliverydate' => $request->deliverydate,
                    'qtyorder' => $request->qtyorder,
                    'qtydelivered' => $request->qtydelivered,
                    'qtytag' => $request->qtytag,
                    'unitprice' => $request->unitprice,
                    'taxcode' => $request->taxcode,
                    'perdisc' => $request->perdisc,
                    'amtdisc' => $request->amtdisc,
                    'amtslstax' => $request->tot_gst,
                    'netunitprice' => $request->netunitprice,
                    /*'qtydelivered' => $request->qtydelivered,*/
                    'amount' => $request->amount,
                    'totamount' => $request->totamount,
                    'draccno' => $draccno,
                    'drccode' => $drccode,
                    'craccno' => $craccno,
                    'crccode' => $crccode, 
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate' => $this->chgDate($request->expdate), 
                    'batchno' => $request->batchno, 
                    'recstatus' => 'OPEN', 
                    'srcdocno' => $this->srcdocno,
                    'remarks' => $request->remarks
                ]);

            ///3. calculate total amount from detail
            $totalAmount = DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amtslstax');

            ///4. then update to header
            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);

            ///5. cari recno dkt podt
            $purordhd = DB::table('material.purordhd')
                ->where('compcode','=',session('compcode'))
                ->where('purordno','=',$this->srcdocno)
                ->first();
            $po_recno = $purordhd->recno;

            ///6. amik old qtyorder dkt podt
            $podt_obj = DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$po_recno)
                ->where('lineno_','=',$request->lineno_);

            $podt_obj_lama = $podt_obj->first();

            ///7. amik suma do yg open
            $delorddt_obj = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('srcdocno','=',$this->srcdocno)
                ->where('lineno_','=',$request->lineno_);

            $total_qtydeliverd_do = 0;

            if($delorddt_obj->exist()){
                $delorddt_data = $delorddt_obj->get();
                foreach ($delorddt_data as $value) {
                    $total_qtydeliverd_do = $total_qtydeliverd_do + $value->qtydelivered;
                }
            }

            $jumlah_qtydelivered = $podt_obj_lama->qtydelivered + $request->qtydelivered + $total_qtydeliverd_do;

            if($jumlah_qtydelivered > $podt_obj_lama->qtyorder){
                DB::rollback();

                return response('Error: Quantity delivered exceed quantity order', 500)
                  ->header('Content-Type', 'text/plain');
            }

            echo $totalAmount;

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function edit_from_PO(Request $request){

        DB::beginTransaction();

        try {


            ///1. update detail
            DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'pricecode' => $request->pricecode, 
                    'itemcode'=> $request->itemcode, 
                    'uomcode'=> $request->uomcode, 
                    'pouom'=> $request->pouom, 
                    'qtyorder'=> $request->qtyorder, 
                    'qtydelivered'=> $request->qtydelivered, 
                    'unitprice'=> $request->unitprice,
                    'taxcode'=> $request->taxcode, 
                    'perdisc'=> $request->perdisc, 
                    'amtdisc'=> $request->amtdisc, 
                    'amtslstax'=> $request->tot_gst, 
                    'netunitprice'=> $request->netunitprice, 
                    'amount'=> $request->amount, 
                    'totamount'=> $request->totamount, 
                    'upduser'=> session('username'), 
                    'upddate'=> Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate'=> $this->chgDate($request->expdate),  
                    'batchno'=> $request->batchno, 
                    'remarks'=> $request->remarks
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);

            ///4. cari recno dkt podt
            $purordhd = DB::table('material.purordhd')
                ->where('compcode','=',session('compcode'))
                ->where('purordno','=',$this->srcdocno)
                ->first();
            $po_recno = $purordhd->recno;

            ///5. amik old qtyorder dkt qtyrequest
            $podt_obj = DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$po_recno)
                ->where('lineno_','=',$request->lineno_);

            $podt_obj_lama = $podt_obj->first();

            ///6. check dan bagi error kalu exceed quantity order

                //step 1. cari header yang ada srcdocno ni
            $delordhd_obj = DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('srcdocno','=',$this->srcdocno);

            if($delordhd_obj->exists()){
                $total_qtydeliverd_do = 0;

                $delorhd_all = $delordhd_obj->get();

                //step 2. dapatkan dia punya qtydelivered melalui lineno yg sama, pastu jumlahkan, jumlah ni qtydelivered yang blom post lagi
                foreach ($delorhd_all as $value_hd) {
                    $delorddt_obj = DB::table('material.delorddt')
                        ->where('recno','=',$value_hd->recno)
                        ->where('compcode','=',session('compcode'))
                        ->where('lineno_','=',$request->lineno_);

                    if($delorddt_obj->exists()){
                        $delorddt_data = $delorddt_obj->first();
                        $total_qtydeliverd_do = $total_qtydeliverd_do + $delorddt_data->qtydelivered;
                    }
                }
            }

                //step 3. jumlah_qtydelivered = qtydelivered yang dah post + qtydelivered yang blom post
            $jumlah_qtydelivered = $podt_obj_lama->qtydelivered + $total_qtydeliverd_do;

                //step 4. kalu melebihi qtyorder, rollback
            if($jumlah_qtydelivered > $podt_obj_lama->qtyorder){
                DB::rollback();

                return response('Error: Quantity delivered exceed quantity order', 500)
                  ->header('Content-Type', 'text/plain');
            }
            
            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

}

