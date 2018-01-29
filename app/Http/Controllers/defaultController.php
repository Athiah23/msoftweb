<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

abstract class defaultController extends Controller{

    public function default_duplicate($table,$duplicateCode,$duplicateValue){
        return DB::table($table)->where($duplicateCode,'=',$duplicateValue)->count();
    }

    public function defaultSetter(Request $request){
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

    public function fixPost(array $column,$rep){ // ni nak replace underscore jadi dot pastu letak AS original(yang ada underscore)
        $temp=[];
        foreach($column as $value){
            $pos = strpos($value, "_");
            if ($pos !== false) {
                $newstring = substr_replace($value, ".", $pos, strlen("."));
                $newstring = $newstring.' AS '.$value;
                array_push($temp,$newstring);
            }
        }   
        return $temp;
    }

    public function fixPost2(array $column){ // ni nak buang lagsung underscore
        $temp=[];
        foreach($column as $value){
            array_push($temp, substr(strstr($value,'_'),1));
        }   
        return $temp;
    }

    public function fixPost3(array $column){ // ni nak replace underscore jadi dot
        $temp=[];
        foreach($column as $value){
            $pos = strpos($value, "_");
            if ($pos !== false) {
                array_push($temp, substr_replace($value, ".", $pos, strlen(".")));
            }
        }   
        return $temp;
    }

    public function defaultGetter(Request $request){

        //////////make table/////////////
        if(is_array($request->table_name)){
            $table =  DB::table($request->table_name[0]);
        }else{
            $table =  DB::table($request->table_name);
        }

        ///////////select field////////
        if(!empty($request->field)){
            if(!empty($request->fixPost)){
                $table = $table->select($this->fixPost($request->field,"_"));
            }else{
                $table = $table->select($request->field);
            }
        }

        //////////join//////////
        if(!empty($request->join_onCol)){
            foreach ($request->join_onCol as $key => $value) {

                if(empty($request->join_filterCol)){ //ni nak check kalu ada AND lepas JOIN ON

                    $table = $table->join($request->table_name[$key+1], $request->join_onCol[$key], '=', $request->join_onVal[$key]);
                }else{

                    $table = $table->join($request->table_name[$key+1], function($join) use ($request,$key){
                        $join = $join->on($request->join_onCol[$key], '=', $request->join_onVal[$key]);
                        
                        foreach ($request->join_filterCol as $key2 => $value2) {
                            foreach ($value2 as $key3 => $value3) {
                                $pieces = explode(' ', $value3);
                                if($pieces[1] == 'on'){
                                    $join = $join->on($pieces[0],$pieces[2],$request->join_filterVal[$key2][$key3]);
                                }else{
                                    $join = $join->where($pieces[0],$pieces[2],$request->join_filterVal[$key2][$key3]);
                                }
                            }
                        }
                    });
                }
            }
        }

        /////////searching/////////
        if(!empty($request->searchCol)){
            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol);
            }else{
                $searchCol_array = $request->searchCol;
            }

            foreach ($searchCol_array as $key => $value) {
                $table = $table->orWhere($searchCol_array[$key],'like',$request->searchVal[$key]);
            }
        }

        /////////searching 2///////// ni search utk ordialog
        if(!empty($request->searchCol2)){
            $table = $table->Where(function($query) use ($request){
                foreach ($request->searchCol2 as $key => $value) {
                    $query = $query->orWhere($request->searchCol2[$key],'like',$request->searchVal2[$key]);
                }
            });
        }

        //////////where//////////
        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $pieces = explode(".", $request->filterVal[$key], 2);
                if($pieces[0] == 'session'){
                    $table = $table->where($request->filterCol[$key],'=',session('compcode'));
                }else if($pieces[0] == '<>'){
                    $table = $table->where($request->filterCol[$key],'<>',$pieces[1]);
                }else{
                    $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
                }
            }
        }

        //////////ordering/////////
        if(!empty($request->sortby)){
            foreach ($request->sortby as $key => $value) {
                $pieces = explode(" ", $request->sortby[$key]);
                $table = $table->orderBy($pieces[0], $pieces[1]);
            }
        }else if(!empty($request->sidx)){
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

        return $table;

    }

    public function get_table_default(Request $request){
        $table = $this->defaultGetter($request);

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

    public function get_value_default(Request $request){
        $table = $this->defaultGetter($request);

        $responce = new stdClass();
        $responce->rows = $table->get();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }

    public function defaultAdd(Request $request){

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        if($this->default_duplicate( ///check duplicate
            $request->table_name,
            $idno,
            $request[$request->table_id]
        )){
            return response('duplicate', 500);
        };

        DB::beginTransaction();

        $table = DB::table($request->table_name);

        $array_insert = [
        	'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now(),
            'recstatus' => 'A'
        ];

        foreach ($field as $key => $value) {
            $array_insert[$value] = $request[$request->field[$key]];
        }

        try {

            $table->insert($array_insert);

            $responce = new stdClass();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function defaultEdit(Request $request){

        DB::beginTransaction();

        $table = DB::table($request->table_name);

        $array_update = [
        	'compcode' => session('compcode'),
            'upduser' => session('username'),
            'upddate' => Carbon::now(),
            'recstatus' => 'A'
        ];

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = $request[$request->idnoUse];
        }else{
            $field = $request->field;
            $idno = $request->idno;
        }

        foreach ($field as $key => $value) {
        	$array_update[$value] = $request[$request->field[$key]];
        }

        try {


            //////////where//////////
            $table = $table->where('idno','=',$idno);


            if(!empty($request->filterCol)){ ///this are not suppose to be used, we already have unique idno
                foreach ($request->filterCol as $key => $value) {
                    $pieces = explode(".", $request->filterVal[$key], 2);
                    if($pieces[0] == 'session'){
                        $table = $table->where($request->filterCol[$key],'=',session('compcode'));
                    }else{
                        $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
                    }
                }
            }

            $table->update($array_update);

            $responce = new stdClass();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function defaultDel(Request $request){

        DB::beginTransaction();

        $table = DB::table($request->table_name);

        try {

            $table = $table->where('idno','=',$request->idno);
            $table->update([
                'deluser' => session('username'),
                'deldate' => Carbon::now(),
                'recstatus' => 'D',
            ]);

            $responce = new stdClass();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response('Error'.$e, 500);
        }

    }
}
