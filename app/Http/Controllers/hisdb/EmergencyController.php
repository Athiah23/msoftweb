<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class EmergencyController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "MRN";
    }

    public function table(Request $request)
    {

        $array_select = $this->fixPost($request->field,"_");
        $array_select = array_merge($array_select, ['apptdatefr']);

        $table = DB::table('hisdb.apptbook as a')
            ->select($array_select)
            ->join('hisdb.doctor as d', function($join) use ($request){
                $join = $join->on('d.compcode','=','a.compcode');
                $join = $join->on('d.doctorcode','=','a.loccode');
            });

        /////////searching/////////
        if(!empty($request->searchCol)){
            $searchCol_array = $this->fixPost3($request->searchCol);

            foreach ($searchCol_array as $key => $value) {
                $table = $table->orWhere($searchCol_array[$key],'like',$request->searchVal[$key]);
            }
        }

        $apptdatefr = Carbon::createFromFormat('Y-m-d H', $request->apptdatefr." 00", 'Asia/Kuala_Lumpur');
        $apptdatefr2 = $apptdatefr->copy()->addDays(1);

        $table = $table->where('a.type','=','ED')
            ->whereBetween('a.apptdatefr', [$apptdatefr, $apptdatefr2]);

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
        $rows = $paginate->items();

        foreach ($rows as $key => $value) {
            $apptdatefr = Carbon::createFromFormat('Y-m-d H:i:s', $value->apptdatefr, 'Asia/Kuala_Lumpur');

            $rows[$key]->reg_date = $apptdatefr->toDateString();
            $rows[$key]->reg_time = $apptdatefr->toTimeString();
        }

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $rows;
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }

    public function show(Request $request)
    {   
        $color = DB::table('sysdb.users')
            ->where('username','=',session('username'))
            ->where('compcode','=',session('compcode'))
            ->first();

       return view('hisdb.emergency.emergency',compact('color'));
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_default':

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->defaultEdit($request);
                    case 'del':
                        return $this->defaultDel($request);
                    case 'savecolor':
                        return $this->savecolor($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_patient':

                if($request->oper == 'add'){
                    return false; 
                }else if($request->oper == 'edit'){
                    return $this->save_patient_edit($request);
                }else{
                    return false; 
                }

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {
            ///--- 1. check kalu MRN ada ke tak

            $mrn = ltrim($request->mrn, '0');
            $payer = ltrim($request->payer, '0'); 

            if($mrn != ""){
                //1.kalu ada cari
                $patmast_obj = DB::table('hisdb.pat_mast')
                    ->where('mrn','=',$mrn)
                    ->where('compcode','=',session('compcode'));
            }else{
                //2.kalu xde buat baru lepas tambah sysparam
                $sysparam_mrn = $this->defaultSysparam("HIS","MRN");

                DB::table('hisdb.pat_mast')
                    ->insert([
                        'mrn' => $sysparam_mrn,
                        'Oldic' => $request->Oldic,
                        'Name' => $request->patname,
                        'DOB' => $request->DOB,
                        'ID_Type'  => $request->ID_Type,
                        'Sex'  => $request->sex,
                        'RaceCode'  => $request->race,
                        'Confidential'  => 'yes',
                        'MRFolder'  => 'yes',    
                        'Newic'   => $request->Newic,
                        'Active'  => 'yes',     
                        'CompCode'  => session('compcode'),
                        'PatStatus'  => 'yes',  
                        'Lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'LastUser'  => session('username'),   
                        'AddDate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'AddUser'  => session('username'),
                        'first_visit_date' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'last_visit_date' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

                $patmast_obj = DB::table('hisdb.pat_mast')
                    ->where('mrn','=',$sysparam_mrn)
                    ->where('compcode','=',session('compcode'));
            }
            
            $patmast_data = $patmast_obj->first();

            ///--- 2. buat episode baru
                //1. RegDept OE-ED
            $RegDept = DB::table('sysdb.sysparam')
                ->select('pvalue1')
                ->where('source','=','OE')
                ->where('trantype','=','ED')
                ->first();
                
                //2. tukar pat status dgn episno
            $patmast_obj->update(['PatStatus' => 'yes', 'EpisNo' => $patmast_data->Episno + 1]);

                //3. tambah episode
            DB::table('hisdb.episode')
                ->insert([
                    'MRN' => $patmast_data->MRN,
                    'EpisNo' => $patmast_data->Episno + 1,    
                    'EpisTyCode' => "OP",
                    'Reg_Date'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(), 
                    'Reg_Time' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString(),  
                    'AdmSrcCode' => "WIN",
                    'Case_Code'  => "MED",
                    'PyrMode' => "CASH",   
                    'Pay_type' => $request->financeclass,
                    'BillType' => $request->billtype,
                    'AdmDoctor' => $request->doctor, 
                    'CompCode' => session('compcode'),  
                    'RegDept' => $RegDept->pvalue1,   
                    'EpisActive' => 'yes',
                    'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'Lastuser' => session('username'),  
                    'AddDate' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),   
                    'AddUser' => session('username'),   
                    'EDDept' => 'yes',
                    'episstatus' => ''
                ]);

            ///--- 3. buat debtormaster
            if($request->financeclass == 'PT'){
                $debtortype_data = DB::table('debtor.debtortype')
                    ->where('compcode','=',session('compcode'))
                    ->where('DebtorTyCode','=','PR')
                    ->first();

                $debtormast_obj = DB::table('debtor.debtormast')
                    ->where('compcode','=',session('compcode'))
                    ->where('debtorcode','=',$patmast_data->MRN);

                $debtormast_data = $debtormast_obj->first();

                if(!count($debtormast_data)){
                    //kalu xjumpa debtormast, buat baru
                    DB::table('debtor.debtormast')
                        ->insert([
                            'CompCode' => session('compcode'),
                            'DebtorCode' => $patmast_data->MRN,
                            'Name' => $patmast_data->Name,
                            'Address1' => $patmast_data->Address1,
                            'Address2' => $patmast_data->Address2,
                            'Address3' => $patmast_data->Address3,
                            'DebtorType' => "PR",
                            'DepCCode'  => $debtortype_data->depccode,
                            'DepGlAcc' => $debtortype_data->depglacc,
                            'BillType' => "IP",
                            'BillTypeOP' => "OP",
                            'ActDebCCode' => $debtortype_data->actdebccode,
                            'ActDebGlAcc' => $debtortype_data->actdebglacc,
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'RecStatus' => "A"
                        ]);
                }
            }

            ///--- 4. epispayer
            $epispayer_data = DB::table('hisdb.epispayer')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$patmast_data->MRN)
                ->where('Episno','=',$patmast_data->Episno + 1)
                ->first();

            if(!count($epispayer_data)){
                //kalu xjumpa epispayer, buat baru
                DB::table('hisdb.epispayer')
                ->insert([
                    'CompCode' => session('compcode'),
                    'MRN' => $patmast_data->MRN,
                    'Episno' => $patmast_data->Episno + 1,
                    'EpisTyCode' => "OP",
                    'LineNo' => '1',
                    'BillType' => $request->billtype,
                    'PayerCode' => $payer,
                    'Pay_Type' => $request->financeclass,
                    'AddDate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'AddUser' => session('username'),
                    'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'LastUser' => session('username')
                ]);
            }

            ///--- 5. buat apptbook
            DB::table('hisdb.apptbook')
                ->insert([
                    'LastUpdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'apptdatefr' => Carbon::now("Asia/Kuala_Lumpur"),
                    'AddUser' => session('username'),    
                    'CompCode' => session('compcode'),
                    'Location' => 'ED',
                    'LocCode' => $request->doctor,
                    'ApptNo' => '0',
                    'episno' => $patmast_data->Episno + 1,
                    'MRN' => $patmast_data->MRN,
                    'pat_name' => $patmast_data->Name,
                    'icnum' => $patmast_data->Newic,
                    'apptstatus' => 'Attend',
                    'telno' => $patmast_data->telh,
                    'telhp' => $patmast_data->telhp,
                    'type' => 'ED'
                    // 'ApptTime' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString()
                ]);

            ///---6. buat docalloc
            $docalloc_data=DB::table('hisdb.docalloc')
                ->where('compcode','=',session('compcode'))
                ->where('Mrn','=',$patmast_data->MRN)
                ->where('Episno','=',$patmast_data->Episno + 1)
                ->first();


            if(!count($docalloc_data)){
                //kalu xde docalloc buat baru
                DB::table('hisdb.docalloc')
                    ->insert([
                        'mrn' => $patmast_data->MRN,
                        'compcode' => session('compcode'),
                        'episno' => $patmast_data->Episno + 1,
                        'AStatus' => "ADMITTING",
                        'Adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'AddUser' => session('username'),
                        'Epistycode' => 'OP',
                        'DoctorCode' => $request->doctor,
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'LastUser' => session('username'),
                        'ASDate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'ASTime' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString()
                    ]);

            }
            ///---7. generate queue
                //cari queue utk source que dgn trantype epistycode
            $queue_obj = DB::table('sysdb.sysparam')
                ->where('source','=','QUE')
                ->where('trantype','=','OP');
                
            $queue_data = $queue_obj->first();

                //kalu xjumpe buat baru
            if(!count($queue_data)){
                DB::table('sysdb.sysparam')
                    ->insert([
                        'compcode' => '9A',
                        'source' => 'QUE',
                        'trantype' => 'OP',
                        'description' => 'OP Queue No.',
                        'pvalue2' => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                    ]);

                $queue_obj = DB::table('sysdb.sysparam')
                    ->where('source','=','QUE')
                    ->where('trantype','=','OP');

                $queue_data = $queue_obj->first();
            }

                //ni start kosong balik bila hari baru
            if($queue_data->pvalue2 != Carbon::now("Asia/Kuala_Lumpur")->toDateString()){
                $queue_obj
                    ->update([
                        'pvalue1' => 0,
                        'pvalue2' => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                    ]);
            }

                //tambah satu dkt queue sysparam
            $current_pvalue1 = intval($queue_data->pvalue1);
            $queue_obj
                ->update([
                    'pvalue1' => $current_pvalue1+1
                ]);

                //buat queue utk 'ALL'
            $queueAll_obj=DB::table('hisdb.queue')
                ->where('mrn','=',$patmast_data->MRN)
                ->where('episno','=',$patmast_data->Episno + 1)
                ->where('deptcode','=','ALL');

            $queueAll_data=$queueAll_obj->first();

            if(!count($queueAll_data)){
                DB::table('hisdb.queue')
                    ->insert([
                        'AdmDoctor' => $request->doctor,
                        'AttnDoctor' => $request->doctor,
                        'BedType' => '',
                        'Case_Code' => "MED",
                        'CompCode' => session('compcode'),
                        'Episno' => $patmast_data->Episno + 1,
                        'EpisTyCode' => "OP",
                        'LastTime' => Carbon::now("Asia/Kuala_Lumpur")->toTimeString(),
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'Lastuser' => session('username'),
                        'MRN' => $patmast_data->MRN,
                        'Reg_Date' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'Reg_Time' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString(),
                        'Bed' => '',
                        'Room' => '',
                        'QueueNo' => $current_pvalue1+1,
                        'Deptcode' => 'ALL',
                        'DOB' => $patmast_data->DOB,
                        'NAME' => $patmast_data->Name,
                        'Newic' => $patmast_data->Newic,
                        'Oldic' => $patmast_data->Oldic,
                        'Sex' => $patmast_data->Sex,
                        'Religion' => $patmast_data->Religion,
                        'RaceCode' => $patmast_data->RaceCode,
                        'EpisStatus' => '',
                        'chggroup' => $request->billtype
                    ]);
            }

                //buat queue utk 'specialist or doctor'
            $queueSPEC_obj=DB::table('hisdb.queue')
                ->where('mrn','=',$patmast_data->MRN)
                ->where('episno','=',$patmast_data->Episno + 1)
                ->where('deptcode','=','SPEC');

            $queueSPEC_data=$queueSPEC_obj->first();

            if(!count($queueSPEC_data)){
                DB::table('hisdb.queue')
                    ->insert([
                        'AdmDoctor' => $request->doctor,
                        'AttnDoctor' => $request->doctor,
                        'BedType' => '',
                        'Case_Code' => "MED",
                        'CompCode' => session('compcode'),
                        'Episno' => $patmast_data->Episno + 1,
                        'EpisTyCode' => "OP",
                        'LastTime' => Carbon::now("Asia/Kuala_Lumpur")->toTimeString(),
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'Lastuser' => session('username'),
                        'MRN' => $patmast_data->MRN,
                        'Reg_Date' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'Reg_Time' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString(),
                        'Bed' => '',
                        'Room' => '',
                        'QueueNo' => $current_pvalue1+1,
                        'Deptcode' => 'SPEC',
                        'DOB' => $patmast_data->DOB,
                        'NAME' => $patmast_data->Name,
                        'Newic' => $patmast_data->Newic,
                        'Oldic' => $patmast_data->Oldic,
                        'Sex' => $patmast_data->Sex,
                        'Religion' => $patmast_data->Religion,
                        'RaceCode' => $patmast_data->RaceCode,
                        'EpisStatus' => '',
                        'chggroup' => $request->billtype
                    ]);
            }

            $queries = DB::getQueryLog();

            $responce = new stdClass();
            $responce->sql = $queries;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function savecolor(Request $request){
        DB::table('sysdb.users')
        ->where('username','=',session('username'))
        ->where('compcode','=',session('compcode'))
        ->update([
            $request->columncolor => $request->color 
        ]);
    }

    public function save_patient_edit(Request $request){
        DB::beginTransaction();

        $table = DB::table('hisdb.pat_mast');

        $array_update = [
            'compcode' => session('compcode'),
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'A'
        ];

        foreach ($request->field as $key => $value) {
            $array_update[$value] = $request[$request->field[$key]];
        }

        array_pull($array_update, 'first_visit_date');
        array_pull($array_update, 'last_visit_date');

        try {

            //////////where//////////
            //1. edit pat_mast
            $table = $table->where('idno','=',$request->idno);
            $table->update($array_update);

            //2. edit apptbook mrn, telh, telhp
            $old_apptbook = DB::table('hisdb.apptbook')
                ->where('idno','=',$request->apptbook_idno)
                ->first();

            $newtitle = $request->MRN.' - '.$request->Name.' - '.$request->telhp.' - ED';

            DB::table('hisdb.apptbook')
                ->where('idno','=',$request->apptbook_idno)
                ->update([
                    'icnum' => $request->Newic,
                    'pat_name' => $request->Name,
                    'title' => $newtitle,
                    'telno' => $request->telh,
                    'telhp' => $request->telhp
                ]);

            $queries = DB::getQueryLog();

            $responce = new stdClass();
            $responce->sql = $queries;
            echo json_encode($responce);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }
}