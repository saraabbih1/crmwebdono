<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;

class NotificationApiController extends Controller
{
    public function index()
    {
        abort_unless(request()->user()?->isAdmin(), 403);

        return NotificationResource::collection(
            Notification::with(['client', 'subscription'])->latest()->paginate(15)
        );
    }
}
