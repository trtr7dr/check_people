<?php

namespace App\Http\Controllers;

use App\Models\Vizit;
use App\Models\People;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\msLog;

class PeopleController extends Controller {

    public function index() {
        $data = People::all();

        $today = Vizit::whereDate('created_at', Carbon::today())->get();
        $data->dop = [];
        foreach ($data as $el) {
            $data->dop[$el->id]['done'] = 0;
            foreach ($today as $done) {

                if ($done->name === $el->name) {
                    $data->dop[$el->id]['done'] = 1;
                    $el->done = 1;
                }
            }
        }
        return view('plus', ['data' => $data]);
    }

    public function ajax(Request $request) {
        $data = $request->all();
        if ($data['mode'] === 'add') {
            $this->add_people($data['name']);
        } else if ($data['mode'] === 'drop') {
            $this->drop_people($data['name']);
        }
        return 0;
    }

    public function add_people($name) {
        $model = new Vizit();
        $model->name = $name;
        $model->created_at = Carbon::now();
        $model->save();
    }

    public function drop_people($name) {
        $model = Vizit::orderBy('updated_at', 'desc')->where('name', $name)->first();
        $model->delete();
    }

    public function cart(){
        $d = date('Y-m-d 0:0:0');
        $foradd = [];
        
        $model = new msLog;
        
        $cart_enter = $model->get_log($d);
        foreach ($cart_enter as $el){
           $el->res = Vizit::where('created_at', '>', $d)
                   ->where('name', People::select('name')->where('EmployeeID', $el->EmployeeID)->pluck('name')->first())
                   ->pluck('id')
                   ->first();
           
           if(is_null($el->res)){
               $el->id_people = People::where('EmployeeID', $el->EmployeeID)->pluck('id')->first();
               array_push($foradd, $el->id_people);
           }
        }
        return array_unique($foradd);
    }
    
}
