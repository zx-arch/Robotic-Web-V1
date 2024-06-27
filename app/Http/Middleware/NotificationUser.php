<?php

// app/Http/Middleware/NotificationMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Attendances;
use Illuminate\Support\Collection;

class NotificationUser
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role == 'user') {

            $notifications = Notification::where('notification.user_id', Auth::user()->id)
                ->orderBy('notification.read', 'asc')
                ->orderBy('notification.created_at', 'desc')
                ->get();
            //dd($notifications);
            // Hitung jumlah notifikasi yang belum dibaca
            // /** @var Collection $notificationCounts */
            // $notificationCounts = $notifications->groupBy('link_online')->map->count();

            // foreach ($notificationCounts as $linkOnline => $count) {
            //     if ($count > 1) {
            //         // Fetch duplicates based on link_online
            //         $duplicates = $notifications->where('link_online', $linkOnline)->slice(1);
            //         if ($duplicates->where('event_code')) {
            //             // Perform actions on duplicates if needed
            //             foreach ($duplicates as $duplicate) {
            //                 $duplicate->forceDelete(); // Or any other operation
            //             }
            //         }

            //     }
            // }

            session([
                'info_notif' => [
                    'total_notif' => $notifications->count(),
                    'notifications' => $notifications,
                ]
            ]);

            View::share('notifications', $notifications);
        }

        return $next($request);
    }
}