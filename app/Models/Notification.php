<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
public function subscription()
{
    return $this->belongsTo(Subscription::class);
}

public function client()
{
    return $this->belongsTo(Client::class);
}}
