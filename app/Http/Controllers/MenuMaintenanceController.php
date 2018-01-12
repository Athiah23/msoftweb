<?php

namespace App\Http\Controllers;

use App\programtab;
use Illuminate\Http\Request;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

class MenuMaintenanceController extends Controller
{   

    var $table;

    public function __construct()
    {
        $this->middleware('auth');
        $this->table = new programtab;
    }

    public function duplicate($check){
        return $this->table->where('Code','=',$check)->count();
    }

    public function show(Request $request)
    {   
        return view('setup.menu_maintenance.menu_maintenance');
    }

    public function table(Request $request)
    {   
        $pieces = explode(", ", $request->sidx .' '. $request->sord);
        $table = $this->table;

        /////////where/////////
        $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);

        /////////searching/////////
        if(!empty($request->searchCol)){
            foreach ($request->searchCol as $key => $value) {
                $table = $table->orWhere($request->searchCol[$key],'like',$request->searchVal[$key]);
            }
         }

        //////////ordering/////////
        if(count($pieces)==1){
            $table = $table->orderBy($request->sidx, $request->sord);
        }else{
            for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                $pieces_inside = explode(" ", $pieces[$i]);
                $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
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

        if($this->duplicate($request->programid)){
            return response('duplicate', 500);
        }

        try {
        	$at_where = $request->whereat;

			if($at_where=='after'){

				//step1 increase lineno at programtab by 1 -> yang ni line no after jer
				DB::table('sysdb.programtab')
					->where('compcode', '=', session('compcode'))
					->where('programmenu', '=', $request->programmenu)
					->where('lineno', '>', $request->idAfter)
					->increment('lineno',1);

				//step2 increase lineno at groupacc by 1 also -> yang ni line no after jer
				DB::table('sysdb.groupacc')
					->where('compcode', '=', session('compcode'))
					->where('programmenu', '=', $request->programmenu)
					->where('lineno', '>', $request->idAfter)
					->increment('lineno',1);

				//insert to programtab by its approprite lineno
				DB::table('sysdb.programtab')
					->insert([
						'compcode' => session('compcode'),
						'programmenu' => $request->programmenu,
						'lineno' => $request->idAfter + 1,
						'programname' => $request->programname,
						'programid' => $request->programid,
						'programtype' => $request->programtype,
						'url' => $request->url,
						'remarks' => $request->remarks,
						'condition1' => $request->condition1,
						'condition2' => $request->condition2,
						'adduser' => $session('username'),
						'adddate' => Carbon::now()
					]);

			}else if($at_where=='first'){

				//step1 increase lineno at programtab by 1 -> yang ni suma sekali
				DB::table('sysdb.programtab')
					->where('compcode', '=', session('compcode'))
					->where('programmenu', '=', $request->programmenu)
					->increment('lineno',1);

				//step2 increase lineno at groupacc by 1 also -> yang ni suma sekali
				DB::table('sysdb.groupacc')
					->where('compcode', '=', session('compcode'))
					->where('programmenu', '=', $request->programmenu)
					->increment('lineno',1);

				//insert to programtab by its approprite lineno
				DB::table('sysdb.programtab')
					->insert([
						'compcode' => session('compcode'),
						'programmenu' => $request->programmenu,
						'lineno' => 1,
						'programname' => $request->programname,
						'programid' => $request->programid,
						'programtype' => $request->programtype,
						'url' => $request->url,
						'remarks' => $request->remarks,
						'condition1' => $request->condition1,
						'condition2' => $request->condition2,
						'adduser' => $session('username'),
						'adddate' => Carbon::now()
					]);

			}else if($at_where=='last'){

				//get the last lineno
				$maxlineno = DB::table('sysdb.programtab')->max('lineno') + 1;

				//insert into programtab
				DB::table('sysdb.programtab')
					->insert([
						'compcode' => session('compcode'),
						'programmenu' => $request->programmenu,
						'lineno' => $maxlineno,
						'programname' => $request->programname,
						'programid' => $request->programid,
						'programtype' => $request->programtype,
						'url' => $request->url,
						'remarks' => $request->remarks,
						'condition1' => $request->condition1,
						'condition2' => $request->condition2,
						'adduser' => $session('username'),
						'adddate' => Carbon::now()
					]);
			}


			$get1 = DB::table('sysdb.programtab')
						->where('compcode','=', session('compcode'))
						->where('programid','=', $request->programmenu)
						->get();

			foreach ($get1 as $key => $value) {
				
				$get_dalam = DB::table('sysdb.groupacc')
						->where('compcode','=', session('compcode'))
						->where('programmenu','=', $value->programmenu)
						->where('lineno','=', $value->lineno)
						->where('yesall','=', 1)
						->get();

				foreach ($get_dalam as $key_dalam => $value_dalam) {
						DB::raw("INSERT INTO sysdb.groupacc (compcode,groupid,programmenu,lineno,canrun,yesall) SELECT '".session('compcode')."','".$get1->groupid."','".$request->programmenu."',lineno,1,0 FROM sysdb.programtab where compcode='".session('compcode')."' and programid=".$request->programid."' and programmenu='".$request->programmenu."'");
				}
			}

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

			DB::table('sysdb.programtab')
				->where('compcode', '=',  session('compcode'))
				->where('programid', '=',  $request->programid)
				->update([
					'programname' => $request->programname,
					'condition1' => $request->condition1,
					'condition2' => $request->condition2,
					'remarks' => $request->remarks,
					'url' => $request->url
				]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

			$child=DB::table('name')->where('programmenu', '=', $request->programid)->count();
			if($child!="0"){

			//update delete id jd lineno=0 ; nak elak child jd orphen
				DB::raw('UPDATE sysdb.programtab,(SELECT max(lineno)+1 as maxline FROM sysdb.programtab WHERE programmenu="" and compcode="'.$this->company.'")subq SET programmenu="" , lineno=subq.maxline WHERE lineno="'.$_POST['lineno'].'" and programmenu="'.$_POST['programmenu'].'" and programid="'.$_POST['programid'].'" and compcode="'.$this->company.'"');
				DB::raw('DELETE FROM sysdb.groupacc WHERE lineno='.$_POST['lineno'].' and programmenu="'.$_POST['programmenu'].'"  and compcode="'.$this->company.'"');
				DB::raw('UPDATE sysdb.programtab SET lineno = lineno - 1 WHERE compcode="'.$this->company.'" and programmenu="'.$_POST['programmenu'].'" and lineno > '.$_POST['lineno'].' order by lineno');
				DB::raw('UPDATE sysdb.groupacc SET lineno = lineno - 1 WHERE compcode="'.$this->company.'" and programmenu="'.$_POST['programmenu'].'" and lineno > '.$_POST['lineno'].' order by lineno');

			}else{

				DB::raw('DELETE FROM sysdb.programtab WHERE lineno="'.$_POST['lineno'].'" and programmenu="'.$_POST['programmenu'].'" and programid="'.$_POST['programid'].'" and compcode="'.$this->company.'"');
				DB::raw('DELETE FROM sysdb.groupacc WHERE lineno="'.$_POST['lineno'].'" and programmenu="'.$_POST['programmenu'].'"  and compcode="'.$this->company.'"');
				DB::raw('UPDATE sysdb.programtab SET lineno = lineno - 1 WHERE compcode="'.$this->company.'" and programmenu="'.$_POST['programmenu'].'" and lineno > '.$_POST['lineno'].' order by lineno');
				DB::raw('UPDATE sysdb.groupacc SET lineno = lineno - 1 WHERE compcode="'.$this->company.'" and programmenu="'.$_POST['programmenu'].'" and lineno > '.$_POST['lineno'].' order by lineno');
				
				$sql1=;

				$sql2=;

				$sql3=;

				$sql4=;

				$this->directSave($sql1);
				$this->directSave($sql2);
				$this->directSave($sql3);
				$this->directSave($sql4);

			}

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

    }
}
