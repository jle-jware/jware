<?php

namespace App\Filters;

use Illuminate\Http\Request as Requests;
use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    protected $request;
    protected $builder;

    public function __construct(Requests $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach($this->filters() as $name => $value) {
            if(method_exists($this, $name)) {
                call_user_func_array([$this, $name], array_filter([$value]));
            }
        }
        return $this->builder;
    }

    public function filters()
    {
        if($this->request)
            return $this->request->all();
        else
            return [];
    }
}