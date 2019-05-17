<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function account()
    {
        return $this->belongsTo(Account::class);
	}
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
	public function user()
	{
		return $this->belongsTo(User::class);
    }
    public function region()
	{
		return $this->belongsTo(Region::class);
	}  
}
