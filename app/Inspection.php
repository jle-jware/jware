<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function assetModel(){
        return $this->belongsTo(AssetModel::class);
    }
    public function inspectionCriteria(){
        return $this->belongsTo(InspectionCriteria::class);
    }
}
