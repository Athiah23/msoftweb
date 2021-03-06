<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class AreaController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "bloodcode";
    }

    public function show(Request $request)
    {   
        return view('setup.area.area');
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

            $citizen = DB::table('hisdb.areacode')
                            ->where('areacode','=',$request->areacode);

            if($citizen->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('hisdb.areacode')
                ->insert([  
                    'compcode' => session('compcode'),
                    'areacode' => strtoupper($request->areacode),
                    'Description' => strtoupper($request->Description),
                    'recstatus' => strtoupper($request->recstatus),
                    'areagroup' => strtoupper($request->areagroup),
                    'citizen' => strtoupper($request->citizen),
                    //'createdby' => strtoupper($request->createdby),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
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

            DB::table('hisdb.areacode')
                ->where('idno','=',$request->idno)
                ->update([  
                    'areacode' => strtoupper($request->areacode),
                    'Description' => strtoupper($request->Description),
                    'recstatus' => strtoupper($request->recstatus),
                    'areagroup' => strtoupper($request->areagroup),
                    'citizen' => strtoupper($request->citizen),
                    //'createdby' => strtoupper($request->createdby),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function del(Request $request){
        DB::table('hisdb.areacode')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'D',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}