<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpareJob extends Model
{
    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function job(){
        return $this->belongsTo(Job::class);
    }
    public function spare(){
        return $this->belongsTo(Spare::class);
    }
}
