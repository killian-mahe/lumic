<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Team;
use Illuminate\Http\Request;
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
    public function goTeam(Request $request, string $slug = null, string $name)
    {
        $user = $request->user();
        $team = $user->allTeams()->firstWhere('slug', $slug);

        if (!$team) abort(404);

        Gate::forUser($user)->authorize('useLink', $team);

        $link = $team->links()->where('name', $name)->first();
        if ($link) {
            return redirect()->away($link->value);
        }
        abort(404);
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
            "visibility" => "required|boolean"
        ]);

        $team = Team::find($input['team']);

        Gate::forUser($request->user())->authorize('createLink', $team);

        $team->links()->create($input);

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
            "visibility" => "required|boolean"
        ]);

        Gate::forUser($request->user())->authorize('updateLink', $link->team);

        $link->name = $input['name'];
        $link->value = $input['value'];
        $link->visibility = $input['visibility'];
        $link->save();

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
