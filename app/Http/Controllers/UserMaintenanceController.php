<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

class UserMaintenanceController extends defaultController
{
    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->table = new user;
        $this->duplicateCode = "username";
    }

    public function duplicate($check){
        return $this->table->where('Code','=',$check)->count();
    }

    public function show(Request $request)
    {   
        return view('setup.user_maintenance.user_maintenance');
    }

    public function table(Request $request)
    {   
        $table = $this->table;

        /////////where/////////


        
        /////////searching/////////
        if(!empty($request->searchCol)){
            foreach ($request->searchCol as $key => $value) {
                $table = $table->orWhere($request->searchCol[$key],'like',$request->searchVal[$key]);
            }
         }

        //////////ordering/////////
        if(!empty($request->sidx)){
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
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

        if($this->duplicate($this->duplicateCode)){
            return response('duplicate', 500);
        }

        try {

            $this->table->insert([
                'username' => $request->username,
                'name' => $request->name,
                'groupid' => $request->groupid,
                'deptcode' => $request->deptcode,
                'priceview' => $request->priceview,
                'programmenu' => $request->programmenu,
                'password' => $request->password,
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now(),
                'recstatus' => 'A'
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            $table = $this->table->find($request->id);
            $table->update([
                'username' => $request->username,
                'name' => $request->name,
                'groupid' => $request->groupid,
                'deptcode' => $request->deptcode,
                'priceview' => $request->priceview,
                'programmenu' => $request->programmenu,
                'password' => $request->password,
                'compcode' => session('compcode'),
                'upduser' => session('username'),
                'upddate' => Carbon::now(),
                'recstatus' => 'A'
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            $table = $this->table->find($request->id);
            $table->update([
                'recstatus' => 'D',
                'deluser' => session('username'),
                'deldate' => Carbon::now(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

    }
}
