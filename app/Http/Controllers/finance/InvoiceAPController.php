<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

    class InvoiceAPController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AP.invoiceAP.invoiceAP');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            case 'posted':
                return $this->posted($request);
            case 'cancel':
                return $this->cancel($request);
            default:
                return 'error happen..';
        }
    }

    public function suppgroup($suppcode){
        $query = DB::table('material.supplier')
                ->select('supplier.SuppGroup')
                ->where('SuppCode','=',$suppcode)
                ->where('compcode','=', '9A')
                ->first();
        
        return $query->SuppGroup;
    }

    public function add(Request $request){

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        $auditno = $this->recno($request->apacthdr_source, $request->apacthdr_trantype);
      //  $suppgroup = $this->suppgroup($request->apacthdr_suppcode);

        DB::beginTransaction();

        $table = DB::table("finance.apacthdr");

        $array_insert = [
            'source' => 'AP',
            'auditno' => $auditno,
            'trantype' => $request->trantype,
            //'suppcode' => $request->suppcode,
          //  'suppgroup' => $suppgroup,
          /*  'payto' => $request->payto,
            'document' => $request->document,
            'category' => $request->category,
            'amount' => $request->amount,
            'outamount' => $request->amount,
            'remarks' => $request->remarks,
            'actdate' => $request->actdate,
            'recdate' => $request->recdate,
            'deptcode' => $request->deptcode,*/
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'OPEN'
        ];

        foreach ($field as $key => $value){
            $array_insert[$value] = $request[$request->field[$key]];
        }

        try {

            $idno = $table->insertGetId($array_insert);

            $responce = new stdClass();
            $responce->auditno = $auditno;
            $responce->idno = $idno;
          //  $responce->suppgroup = $suppgroup;
            echo json_encode($responce);

            // $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }


   
}
