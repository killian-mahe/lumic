<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkLog;
use App\Models\Team;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class LinkController extends Controller
{

    /**
     * Redirect to the select URL.
     *
     * @param Request $request
     * @param string $name
     * @return RedirectResponse|null
     */
    public function go(Request $request, string $name): ?RedirectResponse
    {
        $user = $request->user();
        $link = $user->links()->where('name', $name)->first();
        if ($link) {
            $this->logConnection($request, $link);
            return redirect()->away($link->value);
        }
        abort(404);
        return null;
    }

    /**
     * Redirect to the select team URL.
     *
     * @param Request $request
     * @param string $slug
     * @param string $name
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function goTeam(Request $request, string $slug, string $name): RedirectResponse
    {
        $team = Team::firstWhere('slug', $slug);
        if (!$team) abort(404);

        $link = $team->links()->where('name', $name)->first();
        if (!$link) abort(404);

        if ($link->isPublic()) {
            $this->logConnection($request, $link);
            return redirect()->away($link->value);
        }

        $user = $request->user();
        if (!$user) abort(403);

        if (!$user->ownsTeam($team) && !$user->belongsToTeam($team)) abort(403);

        Gate::forUser($user)->authorize('useLink', $team);

        $this->logConnection($request, $link);
        return redirect()->away($link->value);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function store(Request $request): Redirector|RedirectResponse|Application
    {

        $input = $request->validate([
            "name" => "required|alpha_num",
            "value" => "required|url",
            "team" => "required|exists:App\Models\Team,id",
            "visibility" => "required|boolean",
            "favicon" => "nullable|mimes:jpg,jpeg,png|max:1024"
        ]);

        $team = Team::find($input['team']);

        Gate::forUser($request->user())->authorize('createLink', $team);

        $link = $team->links()->create([
            "name" => $input['name'],
            "value" => $input['value'],
            "team" => $input['team'],
            "public" => $input['visibility']
        ]);

        if (isset($input['favicon'])) {
            $link->updateFavicon($input['favicon']);
        }

        return redirect('dashboard');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Link $link
     * @return Application|RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function update(Request $request, Link $link): Redirector|RedirectResponse|Application
    {
        $input = $request->validate([
            "name" => "required|alpha_num",
            "value" => "required|url",
            "visibility" => "required|boolean",
            "favicon" => "nullable|mimes:jpg,jpeg,png|max:1024",
            "delete_favicon" => "required|boolean"
        ]);

        Gate::forUser($request->user())->authorize('updateLink', $link->team);

        if (isset($input['favicon'])) {
            $link->updateFavicon($input['favicon']);
        }

        $link->name = $input['name'];
        $link->value = $input['value'];
        $link->public = $input['visibility'];
        $link->save();

        if ($input['delete_favicon']) $link->deleteFavicon();

        return redirect('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Link $link
     * @return Application|RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Link $link): Redirector|RedirectResponse|Application
    {
        Gate::forUser($request->user())->authorize('deleteLink', $link->team);

        $link->logs()->delete();

        $link->delete();

        return redirect('dashboard');
    }

    /**
     * Log the connection in the database.
     *
     * @param Request $request
     * @param Link $link
     */
    private function logConnection(Request $request, Link $link)
    {
        if ($request->ip() == '127.0.0.1') return;

        $response = Http::withHeaders([
            'User-Agent' => 'keycdn-tools:https://www.lumic.fr'
        ])->get('https://tools.keycdn.com/geo.json', [
            'host' => $request->ip()
        ]);

        $link->logs()->create([
            'ip_address' => $request->ip(),
            'geolocation' => json_encode($response->json()['data']['geo'])
        ]);
    }
}
