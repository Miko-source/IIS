<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait AutoPaginate
{
    public static function bootAutoPaginate()
    {
        static::addGlobalScope('autoPaginate', function (Builder $builder) {
            
            // Pouze pokud si to uzivatel vyzada
            if (request()->has('paginate')) {
                $limit = request()->get('limit', 10);
                $builder->limit($limit);
            }
        });
    }
}
