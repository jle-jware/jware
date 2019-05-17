<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spare extends Model
{
      public function company(){
        return $this->belongsTo(Company::class);
    }
    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function assetModel(){
        return $this->belongsTo(AssetModel::class);
    }
    public function spareType(){
        return $this->belongsTo(SpareType::class);
    } 
}
