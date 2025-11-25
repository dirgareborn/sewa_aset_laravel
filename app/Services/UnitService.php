<?php

namespace App\Services;

use App\Models\Unit;

class UnitService
{
    public static function buildTree($departmentId = null)
    {
        $query = Unit::with('children')->whereNull('parent_id')->orderBy('name');
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query->get();
    }

    public static function flattenForSelect($units, $prefix = '')
    {
        $result = [];
        foreach ($units as $u) {
            $result[] = (object) ['id' => $u->id, 'name' => $prefix.$u->name, 'department' => $u->department];
            if ($u->children->count()) {
                $childs = self::flattenForSelect($u->children, $prefix.'— ');
                $result = array_merge($result, $childs->all());
            }
        }

        return collect($result);
    }
}
