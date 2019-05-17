<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    public function asset(){
        return $this->belongsTo(Asset::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function jobType(){
        return $this->belongsTo(JobType::class);
    }
    public function milestoneType(){
        return $this->belongsTo(MilestoneType::class);
    }
    public function job(){
        return $this->belongsTo(Job::class);
    }     
}
