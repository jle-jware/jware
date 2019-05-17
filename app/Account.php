<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public function scopeFilter($query, $params) {
		$accountFilter = $query;
		if (isset($params['query'])) {
			foreach (['name'] as $column)
	        {
				$accountFilter->orWhere($column,'like' ,"%{$params['query']}%");
	        }
		}
		if (isset($params['name'])) {
			$accountFilter->where('name', 'like', "%{$params['name']}%");
		}
		if (isset($params['website'])) {
			$accountFilter->where('website', 'like', "%{$params['website']}%");
		}
		if (isset($params['address'])) {
			$accountFilter->where('address', 'like', "%{$params['address']}%");
		}
		if (isset($params['post_code'])) {
			$accountFilter->where('post_code', 'like', "%{$params['post_code']}%");
		}
		if (isset($params['type'])) {
			$accountFilter->where('type', 'like', "%{$params['type']}%");
		}
		if (isset($params['phone_number'])) {
			$accountFilter->where('phone_number', 'like', "%{$params['phone_number']}%");
		}
 		return $accountFilter;
	}
}
