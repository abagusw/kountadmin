<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeGoals;
use App\Models\Employees;
use App\Models\Goals;


use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use DB;

class EmployeeGoalsController extends Controller
{
    public function url_goals()
    {
        $x = env('APP_URL') . '/employee_goals';
        return $x;
    }
    protected $original_column = array(
        1 => "id",
    );
    public function type()
    {
        $value = array('zero' => 'Zero', 'percent' => 'Percent', 'amount_plus' => 'Amount Plus', 'amount_minus' => 'Amount Minus');
        return $value;
    }


    public function index()
    {
        return view('backend/employee_goals/index');
    }

    public function employees()
    {
        $employees = Employees::get();
        return $employees;
    }
    public function goals()
    {
        $goals = Goals::get();
        return $goals;
    }
    public function getData(Request $request)
    {
        $limit = $request->length;
        $start = $request->start;
        $page  = $start + 1;
        $search = $request->search['value'];

        $records = EmployeeGoals::select('*');

        //   if(array_key_exists($request->order[0]['column'], $this->original_column)){
        //      $records->orderByRaw($this->original_column[$request->order[0]['column']].' '.$request->order[0]['dir']);
        //   }
        //   else{
        //     $records->orderBy('created_at','DESC');
        //   }
        if ($search) {
            $records->where(function ($query) use ($search) {
                $query->orWhere('employee_id', 'LIKE', "%{$search}%");
            });
        }
        $totalData = $records->get()->count();

        $totalFiltered = $records->get()->count();

        $records->limit($limit);
        $records->offset($start);
        $data = $records->get();
        foreach ($data as $key => $record) {
            $enc_id = $this->safe_encode(Crypt::encryptString($record->id));
            $action = "";

            $action .= "";

            if ($request->user()->can('employee_goals.ubah')) {
                $action .= '<a href="' . route('employee_goals.ubah', $enc_id) . '" class="btn btn-warning btn-xs icon-btn md-btn-flat product-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>&nbsp;';
            }
            if ($request->user()->can('employee_goals.hapus')) {
                $action .= '<a href="#" onclick="deleteData(this,\'' . $enc_id . '\')" class="btn btn-danger btn-xs icon-btn md-btn-flat product-tooltip" title="Hapus"><i class="ion ion-md-close"></i></a>&nbsp;';
            }

            $record->no             = $key + $page;
            // $record->url            = '<a href="'.$this->url_goals().'/'.$record->slug_url.'" target="_blank">'.$this->url_goals().'/'.$record->slug_url.'</a>';
            $record->action         = $action;
            $record->employee         = Employees::select('fullname')->where('id',$record->employee_id)->first()->fullname;
            $record->goal         = Goals::select('goal_name')->where('id',$record->goal_id)->first()->goal_name;
        }
        if ($request->user()->can('employee_goals.index')) {
            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
            );
        } else {
            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => 0,
                "recordsFiltered" => 0,
                "data"            => []
            );
        }
        return json_encode($json_data);
    }

    function safe_encode($string)
    {
        $data = str_replace(array('/'), array('_'), $string);
        return $data;
    }
    function safe_decode($string, $mode = null)
    {
        $data = str_replace(array('_'), array('/'), $string);
        return $data;
    }
    public function tambah()
    {
        $employees = $this->employees();
        $goals = $this->goals();

        return view('backend/employee_goals/form', compact('employees', 'goals'));
    }


    public function ubah($enc_id)
    {
        $dec_id = $this->safe_decode(Crypt::decryptString($enc_id));
        if ($dec_id) {
            $employee_goals = EmployeeGoals::find($dec_id);
            $employees = $this->employees();
            $goals = $this->goals();


            return view('backend/employee_goals/form', compact('enc_id', 'employee_goals', 'employees', 'goals'));
        } else {
            return view('errors/noaccess');
        }
    }


    public function simpan(Request $req)
    {
        $enc_id     = $req->enc_id;

        if ($enc_id != null) {
            $dec_id = $this->safe_decode(Crypt::decryptString($enc_id));
        } else {
            $dec_id = null;
        }

        if ($enc_id) {
            $data = EmployeeGoals::find($dec_id);

            $data->employee_id       = $req->employee_id;
            $data->goal_id       = $req->goal_id;
            $data->current_progress       = $req->current_progress;
            $data->percentage       = $req->percentage;
            $data->save();

            if ($data) {
                $json_data = array(
                    "success"         => TRUE,
                    "message"         => 'Data berhasil diperbarui.'
                );
            } else {
                $json_data = array(
                    "success"         => FALSE,
                    "message"         => 'Data gagal diperbarui.'
                );
            }
        } else {
            $data = new EmployeeGoals;

            $data->employee_id            = $req->employee_id;
            $data->goal_id       = $req->goal_id;
            $data->current_progress       = $req->current_progress;
            $data->percentage       = $req->percentage;
            $data->save();
            if ($data) {
                $json_data = array(
                    "success"         => TRUE,
                    "message"         => 'Data berhasil ditambahkan.'
                );
            } else {
                $json_data = array(
                    "success"         => FALSE,
                    "message"         => 'Data gagal ditambahkan.'
                );
            }
        }
        return json_encode($json_data);
    }

    public function hapus(Request $req, $enc_id)
    {
        $dec_id = $this->safe_decode(Crypt::decryptString($enc_id));
        $employee_goals = EmployeeGoals::find($dec_id);
        if ($employee_goals) {
            $employee_goals->delete();
            return response()->json(['type' => "success", 'message' => 'Data berhasil dihapus.']);
        } else {
            return response()->json(['type' => "failed", 'message' => 'Gagal menghapus data']);
        }
    }
}
