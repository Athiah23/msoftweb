<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class CaseTypeController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        return view('setup.casetype.casetype');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();
        try {

            $casetype = DB::table('hisdb.casetype')
                            ->where('case_code','=',$request->case_code);

            if($casetype->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('hisdb.casetype')
                ->insert([  
                    'compcode' => session('compcode'),
                    'case_code' => strtoupper($request->case_code),
                    'description' => strtoupper($request->description),
                    'grpcasetype' => strtoupper($request->grpcasetype),
                    'recstatus' => strtoupper($request->recstatus),
                    'units' => strtoupper($request->units),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            DB::table('hisdb.casetype')
                ->where('idno','=',$request->idno)
                ->update([  
                    'case_code' => strtoupper($request->case_code),
                    'description' => strtoupper($request->description),
                    'grpcasetype' => strtoupper($request->grpcasetype),
                    'recstatus' => strtoupper($request->recstatus),
                    'units' => strtoupper($request->units),
                    'idno' => strtoupper($request->idno),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function del(Request $request){
        DB::table('hisdb.casetype')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'D',
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}