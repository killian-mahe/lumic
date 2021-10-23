<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{
    /**
     * Show the analytics page.
     *
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $team = $request?->user()->currentTeam;

        if (!$team) abort(404);

        $stats = [];

        foreach ($team->links as $link)
        {
            $logs = $link->logs;

            $stats[] = [
                "link_id" => $link->id,
                "link_name" => $link->name,
                "series" => $logs->map(function ($item, $key) {
                    return [
                        "timestamp" => json_encode($item->created_at),
                        "value" => json_decode($item->geolocation)
                    ];
                })
            ];
        }

        return Inertia::render('Analytics/Show', [
            'statistics' => $stats
        ]);
    }
}
