<?php

namespace App\Collection;

use Illuminate\Database\Eloquent\Collection;

class BaseCollection extends Collection
{
    /**
     * @author xiaoze <zeasly@live.com>
     * @return array
     */
    public function toApi()
    {
        return array_map(function ($value) {
            return $value->toApi();
        }, $this->items);
    }
}
