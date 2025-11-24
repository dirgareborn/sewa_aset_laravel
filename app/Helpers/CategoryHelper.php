<?php

namespace App\Helpers;

class CategoryHelper
{
    public static function renderTree($categories, $level = 0)
    {
        $html = '';
        foreach ($categories as $cat) {
            $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
            $html .= '<tr>';
            $html .= '<td>'.$indent.' '.$cat->category_name.'</td>';
            $html .= '<td>'.$cat->organization->name ?? '-'.'</td>';
            $html .= '<td>'.$cat->status ? 'Active' : 'Inactive'.'</td>';
            $html .= '<td>
                        <a href="'.route('admin.categories.edit', $cat->id).'" class="btn btn-warning btn-sm">Edit</a>
                        <form action="'.route('admin.categories.destroy', $cat->id).'" method="POST" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button class="btn btn-danger btn-sm" onclick="return confirm(\'Hapus unit bisnis ini?\')">Hapus</button>
                        </form>
                    </td>';
            $html .= '</tr>';

            if ($cat->children->count()) {
                $html .= self::renderTree($cat->children, $level + 1);
            }
        }

        return $html;
    }
}
