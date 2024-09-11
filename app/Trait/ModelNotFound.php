<?php

namespace App\Trait;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ModelNotFound
{
    public function modelNotFound()
    {
        if (! $this->exists()) {
            throw new ModelNotFoundException();
        }
    }
}
