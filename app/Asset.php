<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    public function assetModel(){
        return $this->belongsTo(AssetModel::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function location(){
        return $this->belongsTo(Location::class);
    }
    public function assetStat(){
        return $this->belongsTo(AssetStat::class);
    }
    public function inspectionCriteria(){
        return $this->belongsTo(InspectionCriteria::class);
    }
    public function assetInspection(){
        return $this->hasOne(AssetInspection::class);
    }
    public function milestone(){
        return $this->belongsTo(Milestone::class);
    }
    public function machineOption(){
        return $this->belongsTo(MachineOption::class);
    }
    public function createdUser(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    } 
    public function updatedUser(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    } 
}
