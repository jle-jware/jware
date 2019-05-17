<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetUtilization extends Model
{
    public function utilizationType(){
        return $this->belongsTo(UtilizationType::class);
    }
}
