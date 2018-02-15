<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;

class AppointmentController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "resourcecode";
    }

    public function show(Request $request)
    {   
        return view('hisdb.apptrsc.apptrsc');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function getEvent(Request $request){
        $select = DB::table('hisdb.apptbook')->where('loccode','=',$request->drrsc)->get();
        return $select;
    }

    public function addEvent(Request $request){
        
        DB::table('hisdb.apptbook')->insert([
            'title'       => $request->title,
            'loccode'     => $request->doctor,
            'mrn'         => $request->mrn,
            'pat_name'    => $request->patname,
            'start'  => $request->apptdatefr_day.' '.$request->apptdatefr_time,
            'end'  => $request->apptdateto_day.' '.$request->apptdateto_time,
            'telno'       => $request->telno,
            'apptstatus'  => $request->status,
            'telhp'       => $request->telhp,
            'case_code'   => $request->case,
            'remarks'     => $request->remarks,
        ]);
        
    }

    public function editEvent(Request $request){

        DB::table('hisdb.apptbook')
            ->where('idno','=',$request->idno)
            ->update([
            'title'       => $request->title,
            'loccode'     => $request->doctor,
            'mrn'         => $request->mrn,
            'pat_name'    => $request->patname,
            'start'  => $request->apptdatefr_day.' '.$request->apptdatefr_time,
            'end'  => $request->apptdateto_day.' '.$request->apptdateto_time,
            'telno'       => $request->telno,
            'apptstatus'  => $request->status,
            'telhp'       => $request->telhp,
            'case_code'   => $request->case,
            'remarks'     => $request->remarks,
        ]);

        // dd()
        // if(isset($request->delete) && isset($request->id)){
        //     DB::table('hisdb.apptbook')->where('idno','=',$request->id)->delete();
        // }

    }

}
