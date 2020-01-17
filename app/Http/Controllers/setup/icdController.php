<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class icdController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('setup.icd.icd');
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

            $diagtab = DB::table('hisdb.diagtab')
                            ->where('icdcode','=',$request->icdcode);

            $type = DB::table('sysdb.sysparam')
                            ->where('source','=',"MR")
                            ->where('trantype','=',"ICD")
                            ->first();

            if($diagtab->exists()){
                throw new \Exception("record duplicate");
            }



            DB::table('hisdb.diagtab')
                ->insert([  
                    'compcode' => session('compcode'),
                    'icdcode' => strtoupper($request->icdcode),
                    'description' => strtoupper($request->Description),
                    "type" => $type->pvalue1,
                    'recstatus' => strtoupper($request->recstatus),
                    // 'lastcomputerid' => strtoupper($request->lastcomputerid),
                    // 'lastipaddress' => strtoupper($request->lastipaddress),
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

            DB::table('hisdb.diagtab')
                ->where('idno','=',$request->idno)
                ->update([  
                    'description' => strtoupper($request->Description),
                    'recstatus' => strtoupper($request->recstatus),
                    // 'lastcomputerid' => strtoupper($request->lastcomputerid),
                    // 'lastipaddress' => strtoupper($request->lastipaddress),
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
        DB::table('hisdb.diagtab')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'D',
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}
