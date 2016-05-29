<?php

namespace Tricolore;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /**
     * Block comment
     *
     * @param bool 
     * @throws \Exception
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
