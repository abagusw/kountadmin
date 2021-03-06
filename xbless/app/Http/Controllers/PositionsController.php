<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Positions;
use App\Models\Hierarchy;


use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use DB;

class PositionsController extends Controller
{
    public function url_positions()
    {
        $x = env('APP_URL') . '/positions';
        return $x;
    }
    protected $original_column = array(
        1 => "title",
        2 => "status",
    );
    public function status()
    {
        $value = array('1' => 'Aktif', '0' => 'Tidak Aktif');
        return $value;
    }


    public function index()
    {
        return view('backend/positions/index');
    }

    public function getData(Request $request)
    {
        $limit = $request->length;
        $start = $request->start;
        $page  = $start + 1;
        $search = $request->search['value'];

        $records = Positions::select('*')->where('id', '!=', 1);

        //   if(array_key_exists($request->order[0]['column'], $this->original_column)){
        //      $records->orderByRaw($this->original_column[$request->order[0]['column']].' '.$request->order[0]['dir']);
        //   }
        //   else{
        //     $records->orderBy('created_at','DESC');
        //   }
        if ($search) {
            $records->where(function ($query) use ($search) {
                $query->orWhere('position_name', 'LIKE', "%{$search}%");
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

            if ($request->user()->can('positions.ubah')) {
                $action .= '<a href="' . route('positions.ubah', $enc_id) . '" class="btn btn-warning btn-xs icon-btn md-btn-flat product-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>&nbsp;';
            }
            if ($request->user()->can('positions.hapus')) {
                $action .= '<a href="#" onclick="deleteData(this,\'' . $enc_id . '\')" class="btn btn-danger btn-xs icon-btn md-btn-flat product-tooltip" title="Hapus"><i class="ion ion-md-close"></i></a>&nbsp;';
            }

            $record->no             = $key + $page;
            // $record->url            = '<a href="'.$this->url_positions().'/'.$record->slug_url.'" target="_blank">'.$this->url_positions().'/'.$record->slug_url.'</a>';
            $record->action         = $action;
        }
        if ($request->user()->can('positions.index')) {
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
        $status = $this->status();
        $selectedstatus = "1";
        return view('backend/positions/form', compact('status', 'selectedstatus'));
    }


    public function ubah($enc_id)
    {
        $dec_id = $this->safe_decode(Crypt::decryptString($enc_id));
        if ($dec_id) {
            $positions = Positions::find($dec_id);
            $status = $this->status();
            $selectedstatus = $positions->status;


            return view('backend/positions/form', compact('enc_id', 'positions', 'status', 'selectedstatus'));
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
            $data = Positions::find($dec_id);

            $data->position_name       = $req->position_name;
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
            $data = new Positions;

            $data->position_name            = $req->position_name;
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
        $positions = Positions::find($dec_id);
        $hierarchy = Hierarchy::where('position_id',$dec_id)->get();
        if($hierarchy){
            return response()->json(['status' => "failed", 'message' => 'Data dipakai di hierarchy, tidak dapat dihapus']);
        }else{
            if ($positions) {
                $positions->delete();
                return response()->json(['status' => "success", 'message' => 'Data berhasil dihapus.']);
            } else {
                return response()->json(['status' => "failed", 'message' => 'Gagal menghapus data']);
            }
        }
    }
}
