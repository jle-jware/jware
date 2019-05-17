<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MachineSpec extends Model
{
    public function assetModel(){
        return $this->belongsTo(AssetModel::class);
    }
}
