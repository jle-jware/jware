<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
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
    public function priority(){
        return $this->belongsTo(Priority::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function asset(){
        return $this->belongsTo(Asset::class);
    }
    public function jobType(){
        return $this->belongsTo(JobType::class);
    }
    public function jobStat(){
        return $this->belongsTo(JobStat::class);
    }
    public function spareJob(){
        return $this->hasMany(SpareJob::class);
    }
    public function assetInspection(){
        return $this->hasMany(AssetInspection::class);
    }
    public function createdUser(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    } 
    public function updatedUser(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function contractor(){
        return $this->belongsTo(Company::class, 'contractor_id', 'id');
    }
 
}
