<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RaceController extends defaultController
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
        return view('setup.race.race');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':

            	if($this->default_duplicate( ///check duplicate
            		$request->table_name,
            		$request->table_id,
            		$request->Code
            	)){
            		return response('duplicate', 500);
            	};

                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }
}