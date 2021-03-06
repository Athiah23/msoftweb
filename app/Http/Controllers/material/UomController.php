<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class UomController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "uomcode";
    }

    public function show(Request $request)
    {   
        return view('material.UOM.UOM');
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

            $uom = DB::table('material.uom')
                            ->where('uomcode','=',$request->uomcode);

            if($uom->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('material.uom')
                ->insert([  
                    'compcode' => session('compcode'),
                    'uomcode' => strtoupper($request->uomcode),
                    'description' => strtoupper($request->description),
                    'convfactor' => strtoupper($request->convfactor),
                    'recstatus' => strtoupper($request->recstatus),
                    //'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
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

            DB::table('material.uom')
                ->where('idno','=',$request->idno)
                ->update([  
                    'uomcode' => strtoupper($request->uomcode),
                    'description' => strtoupper($request->description),
                    'convfactor' => strtoupper($request->convfactor),
                    'recstatus' => strtoupper($request->recstatus),
                    'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
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
        DB::table('material.uom')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'D',
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}