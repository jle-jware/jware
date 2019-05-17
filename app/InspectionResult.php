<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspectionResult extends Model
{
    public function job(){
        return $this->belongsTo(Job::class);
    }
    public function result(){
        return $this->belongsTo(Result::class);
    }
}
