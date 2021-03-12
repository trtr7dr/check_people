<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class msLog extends Model {

    protected $table = "dbo.Log";
    protected $fillable = ['_id', 'DateTime', 'LogMessageSubType', 'Message', 'Details', 'EmployeeID'];
    protected $connection = 'mysql2';
    public static $ENTER_CODE = 66; 

    public static function decode_id($binguid) {
        $unpacked = unpack('Va/v2b/n2c/Nd', $binguid);
        return sprintf('%08X-%04X-%04X-%04X-%04X%08X', $unpacked['a'], $unpacked['b1'], $unpacked['b2'], $unpacked['c1'], $unpacked['c2'], $unpacked['d']);
    }
 
    public function get_log($date) {
        $res = $this::select('DateTime', 'EmployeeID')
                ->where('LogMessageSubType', self::$ENTER_CODE)
                ->where('DateTime', '>', $date)
                ->get();
        
        foreach ($res as $el){
            $el->EmployeeID = $this->decode_id($el->EmployeeID);
        }
        return $res;
    }
}
