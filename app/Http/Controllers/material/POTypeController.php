<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class POTypeController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "potype";
    }

    public function show(Request $request)
    {   
        return view('material.Purchase Order Type.poType');
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

            $potype = DB::table('material.potype')
                            ->where('potype','=',$request->potype);

            if($potype->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('material.potype')
                ->insert([  
                    'compcode' => session('compcode'),
                    'potype' => strtoupper($request->potype),
                    'typedec' => strtoupper($request->typedec),
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

            DB::table('material.potype')
                ->where('idno','=',$request->idno)
                ->update([  
                    'potype' => strtoupper($request->potype),
                    'typedec' => strtoupper($request->typedec),
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
        DB::table('material.potype')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'D',
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}