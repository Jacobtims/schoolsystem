<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Jenssegers\Agent\Agent;

trait SessionTrait {

    public function collectSessions(Request $request): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(DB::table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->orderBy('last_activity', 'desc')
            ->get())->map(function ($session) {
            return (object)$this->sessionList($session);
        });
    }

    private function sessionList($session): array
    {
        $agent = $this->createAgent($session);
        return  [
            'key' => $session->id,
            'agent' => (object)[
                'is_desktop' => $agent->isDesktop(),
                'platform' => $agent->platform(),
                'browser' => $agent->browser(),
            ],
            'ip_address' => $session->ip_address,
            'is_current_device' => $session->id === request()->session()->getId(),
            'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
        ];
    }

    private function createAgent($session)
    {
        return tap(new Agent, function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }

    public function deleteOtherSessionRecords(Request $request): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
            ->delete();
    }
}
