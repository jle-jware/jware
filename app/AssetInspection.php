<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetInspection extends Model
{
    public function inspectionCriteria(){
        return $this->belongsTo(InspectionCriteria::class);
    }
}
