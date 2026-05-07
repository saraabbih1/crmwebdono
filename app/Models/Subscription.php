<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public function client()
{
    return $this->belongsTo(Client::class);
}

public function notifications()
{
    return $this->hasMany(Notification::class);
}
}
