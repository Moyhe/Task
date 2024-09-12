<?php

namespace App\Trait;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ModelNotFound
{
    public function modelNotFound(Model $model)
    {
        if (! $model->exists()) {
            throw new ModelNotFoundException();
        }
    }
}
