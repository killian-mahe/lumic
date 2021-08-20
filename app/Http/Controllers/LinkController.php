<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class LinkController extends Controller
{

    /**
     * Redirect to the select URL.
     *
     * @param Request $request
     * @param string $slug
     * @param string $name
     */
    public function go(Request $request, string $name)
    {
        $user = $request->user();
        $link = $user->links()->where('name', $name)->first();
        if ($link) {
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
     */
    public function goTeam(Request $request, string $slug, string $name)
    {
        $team = Team::firstWhere('slug', $slug);
        if (!$team) abort(404);

        $link = $team->links()->where('name', $name)->first();
        if (!$link) abort(404);

        if ($link->isPublic()) return redirect()->away($link->value);

        $user = $request->user();
        if (!$user) abort(403);

        if (!$user->ownsTeam($team) && !$user->belongsToTeam($team)) abort(403);

        Gate::forUser($user)->authorize('useLink', $team);

        return redirect()->away($link->value);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
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
     * @param  \Illuminate\Http\Request  $request
     * @param  Link  $link
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Link $link)
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
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, Link $link)
    {
        Gate::forUser($request->user())->authorize('deleteLink', $link->team);

        $link->delete();

        return redirect('dashboard');
    }
}
