<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\User;

class NotificationController extends Controller
{
	public function get($id) {
        $user = User::findOrFail($id);
        $notification = $user->unreadNotifications;
        return $notification;
    }

    public function read(Request $request) {
        $user = User::findOrFail($request->id);
        $user->unreadNotifications()->find($request->id)->markAsRead();
        return 'success';
    }
}
