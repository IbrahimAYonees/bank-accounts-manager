<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $casts = [
        'canceled' => 'boolean'
    ];

    public function cancelTransfer()
    {
        if($this->canceled || now()->diffInHours($this->created_at) >= 36){
            return false;
        }

        $this->canceled = true;
        return $this->save();
    }
}
