<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
