<?php

namespace App\Services;

use App\Models\Group;

class GroupService
{
    /**
     * グループの一覧を取得します。
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllGroups()
    {
        return Group::get();
    }

    
}
