<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST NOTIFIKASI
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = Auth::id();

        $notifications = DB::table('notifications')
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    /*
    |--------------------------------------------------------------------------
    | MARK SINGLE NOTIF AS READ
    |--------------------------------------------------------------------------
    */
    public function markAsRead($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = Auth::id();

        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->update([
                'is_read' => 1,
                'updated_at' => now()
            ]);

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | MARK ALL AS READ
    |--------------------------------------------------------------------------
    */
    public function markAll()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        DB::table('notifications')
            ->where('user_id', Auth::id())
            ->update([
                'is_read' => 1,
                'updated_at' => now()
            ]);

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE NOTIFICATION
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return back();
    }
}
