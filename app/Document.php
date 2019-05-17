<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function spare(){
        return $this->belongsTo(Spare::class);
    }
    public function assetModel(){
        return $this->belongsTo(AssetModel::class);
    }
    public function documentType(){
        return $this->belongsTo(DocumentType::class);
    } 
}
