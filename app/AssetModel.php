<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetModel extends Model
{
    public function modelType(){
        return $this->belongsTo(ModelType::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
}
