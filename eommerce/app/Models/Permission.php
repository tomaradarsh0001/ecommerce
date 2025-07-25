<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}