<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goals;


use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use DB;

class GoalsController extends Controller
{
    public function url_goals()
    {
        $x = env('APP_URL') . '/goals';
        return $x;
    }
    protected $original_column = array(
        1 => "id",
        2 => "goal_name",
        2 => "upper_limit",
        2 => "lower_limit",
        2 => "weight",
        2 => "type",
        2 => "created_at",
        2 => "updated_at",
    );
    public function type()
    {
        $value = array('zero' => 'Zero', 'percent' => 'Percent', 'amount_plus' => 'Amount Plus', 'amount_minus' => 'Amount Minus');
        return $value;
    }


    public function index()
    {
        return view('backend/goals/index');
    }

    public function getData(Request $request)
    {
        $limit = $request->length;
        $start = $request->start;
        $page  = $start + 1;
        $search = $request->search['value'];

        $records = Goals::select('*');

        //   if(array_key_exists($request->order[0]['column'], $this->original_column)){
        //      $records->orderByRaw($this->original_column[$request->order[0]['column']].' '.$request->order[0]['dir']);
        //   }
        //   else{
        //     $records->orderBy('created_at','DESC');
        //   }
        if ($search) {
            $records->where(function ($query) use ($search) {
                $query->orWhere('goal_name', 'LIKE', "%{$search}%");
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

            if ($request->user()->can('goals.ubah')) {
                $action .= '<a href="' . route('goals.ubah', $enc_id) . '" class="btn btn-warning btn-xs icon-btn md-btn-flat product-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>&nbsp;';
            }
            if ($request->user()->can('goals.hapus')) {
                $action .= '<a href="#" onclick="deleteData(this,\'' . $enc_id . '\')" class="btn btn-danger btn-xs icon-btn md-btn-flat product-tooltip" title="Hapus"><i class="ion ion-md-close"></i></a>&nbsp;';
            }

            $record->no             = $key + $page;
            // $record->url            = '<a href="'.$this->url_goals().'/'.$record->slug_url.'" target="_blank">'.$this->url_goals().'/'.$record->slug_url.'</a>';
            $record->action         = $action;
        }
        if ($request->user()->can('goals.index')) {
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
        $type = $this->type();
        $selectedtype = "percent";
        return view('backend/goals/form', compact('type', 'selectedtype'));
    }


    public function ubah($enc_id)
    {
        $dec_id = $this->safe_decode(Crypt::decryptString($enc_id));
        if ($dec_id) {
            $goals = Goals::find($dec_id);
            $type = $this->type();
            $selectedtype = $goals->type;


            return view('backend/goals/form', compact('enc_id', 'goals', 'type', 'selectedtype'));
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
            $data = Goals::find($dec_id);

            $data->goal_name       = $req->goal_name;
            $data->upper_limit       = $req->upper_limit;
            $data->lower_limit       = $req->lower_limit;
            $data->weight       = $req->weight;
            $data->type       = $req->type;
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
            $data = new Goals;

            $data->goal_name            = $req->goal_name;
            $data->upper_limit       = $req->upper_limit;
            $data->lower_limit       = $req->lower_limit;
            $data->weight       = $req->weight;
            $data->type       = $req->type;
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
        $goals = Goals::find($dec_id);
        $pegawai = EmployeeGoals::where('goal_id',$dec_id)->get();
        if($pegawai){
            return response()->json(['type' => "failed", 'message' => 'Data dipakai di Employee Goals, tidak dapat dihapus']);
        }else{
            if ($goals) {
                $goals->delete();
                return response()->json(['type' => "success", 'message' => 'Data berhasil dihapus.']);
            } else {
                return response()->json(['type' => "failed", 'message' => 'Gagal menghapus data']);
            }
        }
    }
}
