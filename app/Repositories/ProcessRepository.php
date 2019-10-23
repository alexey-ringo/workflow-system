<?php

namespace App\Repositories;

use App\Models\Process as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ProcessRepository
 * 
 * @packages App\Repositories
 * 
 */
class ProcessRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }
}