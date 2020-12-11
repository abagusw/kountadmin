<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hierarchy;
use App\Models\Positions;


use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use DB;

class HierarchyController extends Controller
{
    public function url_hierarchy()
    {
        $x = env('APP_URL') . '/hierarchy';
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

    public function positions()
    {
        $positions = Positions::where('id', '!=', 1)->get();
        return $positions;
    }


    public function index()
    {
        return view('backend/hierarchy/index');
    }

    public function getData(Request $request)
    {
        $limit = $request->length;
        $start = $request->start;
        $page  = $start + 1;
        $search = $request->search['value'];

        $records = Hierarchy::select('*');

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

            if ($request->user()->can('hierarchy.ubah')) {
                $action .= '<a href="' . route('hierarchy.ubah', $enc_id) . '" class="btn btn-warning btn-xs icon-btn md-btn-flat product-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>&nbsp;';
            }
            if ($request->user()->can('hierarchy.hapus')) {
                $action .= '<a href="#" onclick="deleteData(this,\'' . $enc_id . '\')" class="btn btn-danger btn-xs icon-btn md-btn-flat product-tooltip" title="Hapus"><i class="ion ion-md-close"></i></a>&nbsp;';
            }

            $record->no             = $key + $page;
            // $record->url            = '<a href="'.$this->url_hierarchy().'/'.$record->slug_url.'" target="_blank">'.$this->url_hierarchy().'/'.$record->slug_url.'</a>';
            $record->action         = $action;
            $record->position         = Positions::select('position_name')->where('id',$record->position_id)->first()->position_name;
            $record->parent         = Positions::select('position_name')->where('id',$record->parent_position_id)->first()->position_name;
        }
        if ($request->user()->can('hierarchy.index')) {
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
        $positions = $this->positions();
        return view('backend/hierarchy/form', compact('status', 'selectedstatus', 'positions'));
    }


    public function ubah($enc_id)
    {
        $dec_id = $this->safe_decode(Crypt::decryptString($enc_id));
        if ($dec_id) {
            $hierarchy = Hierarchy::find($dec_id);
            $status = $this->status();
            $selectedstatus = $hierarchy->status;
            $positions = $this->positions();


            return view('backend/hierarchy/form', compact('enc_id', 'hierarchy', 'status', 'selectedstatus', 'positions'));
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
            $data = Hierarchy::find($dec_id);

            $data->position_id       = $req->position_id;
            $data->parent_position_id       = $req->parent_position_id;
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
            $data = new Hierarchy;

            $data->position_id            = $req->position_id;
            $data->parent_position_id       = $req->parent_position_id;
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
        $hierarchy = Hierarchy::find($dec_id);
        if ($hierarchy) {
            $hierarchy->delete();
            return response()->json(['status' => "success", 'message' => 'Data berhasil dihapus.']);
        } else {
            return response()->json(['status' => "failed", 'message' => 'Gagal menghapus data']);
        }
    }
}
