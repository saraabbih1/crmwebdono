<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $monthlySubscriptions = Subscription::query()
            ->whereDate('start_date', '>=', today()->subMonths(11)->startOfMonth())
            ->get(['start_date'])
            ->groupBy(fn (Subscription $subscription) => $subscription->start_date->format('Y-m'))
            ->map->count();

        $chartLabels = collect(range(11, 0))
            ->map(fn ($monthsAgo) => today()->subMonths($monthsAgo)->format('Y-m'))
            ->push(today()->format('Y-m'));

        return view('dashboard', [
            'totalClients' => Client::count(),
            'activeSubscriptions' => Subscription::where('status', 'active')->count(),
            'expiredSubscriptions' => Subscription::where('status', 'expired')
                ->orWhereDate('end_date', '<', today())
                ->count(),
            'pendingReminders' => Notification::where('status', 'pending')->count(),
            'recentNotifications' => Notification::with(['client', 'subscription'])
                ->latest()
                ->limit(8)
                ->get(),
            'upcomingSubscriptions' => Subscription::with('client')
                ->where('status', 'active')
                ->whereDate('end_date', '>=', today())
                ->orderBy('end_date')
                ->limit(6)
                ->get(),
            'latestActivities' => ActivityLog::with('user')->latest()->limit(8)->get(),
            'serviceDistribution' => Subscription::query()
                ->select('service_type', DB::raw('count(*) as total'))
                ->groupBy('service_type')
                ->pluck('total', 'service_type'),
            'chartLabels' => $chartLabels,
            'monthlySubscriptionCounts' => $chartLabels->map(fn ($label) => $monthlySubscriptions[$label] ?? 0),
        ]);
    }
}
