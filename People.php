<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class People extends Model {
    protected $table = "people";
    protected $fillable = ['name', 'img', 'EmployeeID'];
    
    
    public function get_name_by_cartcode($id){
        
        return $this->select('name')->where('EmployeeID', $id)->pluck('name')->first();
    }
    
}
