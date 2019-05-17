<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobSheet extends Model
{
    public function job(){
        return $this->belongsTo(Job::class);
    }
    public function createdUser(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    } 
    public function updatedUser(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
