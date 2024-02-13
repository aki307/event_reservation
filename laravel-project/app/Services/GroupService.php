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

    public function getAllPosts()
    {
        $allGroups = $this->getAllGroups();
        $count = $allGroups->count();

        if($count > 0){
            return $allGroups->slice(0, $count - 1);
        }

        return collect();
    }
}
