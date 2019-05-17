<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MachineOption extends Model
{
    public function assetModel(){
        return $this->belongsTo(AssetModel::class);
    }
    public function asset(){
        return $this->belongsTo(Asset::class);
    }
}
