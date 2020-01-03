<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class PostcodeController extends defaultController
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
        return view('setup.postcode.postcode');
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

            $postcode = DB::table('hisdb.postcode')
                            ->where('postcode','=',$request->pc_postcode);

            if($postcode->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('hisdb.postcode')
                ->insert([  
                    'compcode' => session('compcode'),
                    'postcode' => strtoupper($request->pc_postcode),
                    'place_name' => strtoupper($request->pc_place_name),
                    'recstatus' => strtoupper($request->pc_recstatus),
                    'countrycode' => strtoupper($request->cn_Code),
                    'statecode' => strtoupper($request->st_StateCode),
                    'district' => strtoupper($request->pc_district),
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

            DB::table('hisdb.postcode')
                ->where('idno','=',$request->pc_idno)
                ->update([  
                    'postcode' => strtoupper($request->pc_postcode),
                    'place_name' => strtoupper($request->pc_place_name),
                    'recstatus' => strtoupper($request->pc_recstatus),
                    'countrycode' => strtoupper($request->cn_Code),
                    'statecode' => strtoupper($request->st_StateCode),
                    'district' => strtoupper($request->pc_district),
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
        DB::table('hisdb.postcode')
            ->where('idno','=',$request->pc_idno)
            ->update([  
                'recstatus' => 'D',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}