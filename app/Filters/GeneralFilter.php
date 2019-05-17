<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class GeneralFilter extends Filter
{
    public function name($filter = "")
    {
        if($filter == "")  return $this->builder;

        $table_name = $this->builder->getQuery()->from;

        $table_columns = Schema::getColumnListing($table_name);

        if(in_array('name', $table_columns))
            return $this->builder->where('name', 'like', "%{$filter}%");
        else if($table_name == 'contacts') {
            return $this->builder->whereHas('user', function($query) use ($filter) {
                $query->where('first_name', 'like', "%{$filter}%")
                        ->orWhere('last_name', 'like', "%{$filter}%");
            });
        }

        return $this->builder;
    }
    

    public function email($filter = "")
    {
        if($filter == "")  return $this->builder;

        $table_name = $this->builder->getQuery()->from;

        $table_columns = Schema::getColumnListing($table_name);

        if(in_array('email', $table_columns))
            return $this->builder->where('email', 'like', "%{$filter}%");
        else if($table_name == 'contacts') {
            return $this->builder->whereHas('user', function($query) use ($filter) {
                $query->where('email', 'like', "%{$filter}%");
            });
        }
        else {
            return $this->builder;
        }        
    }

    public function created_at($filter = "")
    {
        if($filter == "")  return $this->builder;

        $dates = explode("||", $filter);

        if(is_array($dates) && count($dates) == 2)
        {
            return $this->builder->whereBetween('created_at',
                [Carbon::parse($dates[0])->format('Y-m-d'),
                Carbon::parse($dates[1])->format('Y-m-d')]
            );
        }

        $carbon = Carbon::parse($filter)->format('Y-m-d');

        return $this->builder->whereDate('created_at', '=', $carbon);
    }

    public function query($filter = "")
    {
        if($filter == "") return $this->builder;
        
        $table_name = $this->builder->getQuery()->from;

        $table_columns = Schema::getColumnListing($table_name);

        foreach ($table_columns as $column)
        {
            if (strpos($column, 'id') === false && $column != "created_at"  && $column != "updated_at") 
                $this->builder = $this->builder->orWhere($column,'like' ,"%{$filter}%");
        }

        return $this->builder;
    }

    public function address($filter = "") 
    {
        if($filter == "")  return $this->builder;

        $table_name = $this->builder->getQuery()->from;

        $table_columns = Schema::getColumnListing($table_name);

        if(in_array('address', $table_columns))
            return $this->builder->where('address', 'like', "%{$filter}%");

        return $this->builder;
    }

    public function post_code($filter = "") 
    {
        if($filter == "")  return $this->builder;

        $table_name = $this->builder->getQuery()->from;

        $table_columns = Schema::getColumnListing($table_name);

        if(in_array('address', $table_columns))
            return $this->builder->where('post_code', 'like', "%{$filter}%");

        return $this->builder;
    }

    public function website($filter = "") 
    {
        if($filter == "")  return $this->builder;

        $table_name = $this->builder->getQuery()->from;

        $table_columns = Schema::getColumnListing($table_name);

        if(in_array('address', $table_columns))
            return $this->builder->where('website', 'like', "%{$filter}%");

        return $this->builder;
    }

    public function phone_number($filter = "") 
    {
        if($filter == "")  return $this->builder;

        $table_name = $this->builder->getQuery()->from;

        $table_columns = Schema::getColumnListing($table_name);

        if(in_array('phone_number', $table_columns))
            return $this->builder->where('phone_number', 'like', "%{$filter}%");

        return $this->builder;
    }

    public function first_name($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('first_name', 'like', "%{$filter}%");
    }

    public function bmb($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('bmb', 'like', "%{$filter}%");
    }
    public function serial($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('serial', 'like', "%{$filter}%");
    }
    public function comment($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('comment', 'like', "%{$filter}%");
    }

    public function last_name($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('last_name', 'like', "%{$filter}%");
    }

    public function type($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('type', 'like', "%{$filter}%");
    }

    public function username($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('username', 'like', "%{$filter}%");
    }

    public function companies($filter = "") 
    {
        return $this->builder->whereHas('companies', function($query) use ($filter) {
            $query->where('name', 'like', "%{$filter}%");
        });
    }
    public function locations($filter = "") 
    {
        return $this->builder->whereHas('locations', function($query) use ($filter) {
            $query->where('address', 'like', "%{$filter}%");
        });
    }
    public function assets($filter = "") 
    {
        return $this->builder->whereHas('assets', function($query) use ($filter) {
            $query->where('name', 'like', "%{$filter}%");
        });
    }
    public function jobs($filter = "") 
    {
        return $this->builder->whereHas('jobs', function($query) use ($filter) {
            $query->where('type', 'like', "%{$filter}%");
        });
    }
    public function spares($filter = "") 
    {
        return $this->builder->whereHas('spares', function($query) use ($filter) {
            $query->where('name', 'like', "%{$filter}%");
        });
    }
    public function asset_type($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('asset_type', 'like', "%{$filter}%");
    }
    public function company_id($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('company_id', 'like', "%{$filter}%");
    }
    public function agent_id($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('agent_id', 'like', "%{$filter}%");
    }
    public function location($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('location', 'like', "%{$filter}%");
    }
    public function status($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('status', 'like', "%{$filter}%");
    }
    public function contact_id($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('contact_id', 'like', "%{$filter}%");
    }
    public function cost_labour($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('cost_labour', 'like', "%{$filter}%");
    }
    public function material_labour($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('material_labour', 'like', "%{$filter}%");
    }
    public function hours($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('hours', 'like', "%{$filter}%");
    }
    public function current_stock($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('current_stock', 'like', "%{$filter}%");
    }
    public function minimum_stock($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('minimum_stock', 'like', "%{$filter}%");
    }
    public function contact_type($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('contact_type', 'like', "%{$filter}%");
    }
    public function rent($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('rent', 'like', "%{$filter}%");
    }
    public function frequency($filter = "") 
    {
        if($filter == "")  return $this->builder;

        return $this->builder->where('frequency', 'like', "%{$filter}%");
    }
    
}