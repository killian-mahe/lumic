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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            "name" => "required|alpha_num",
            "value" => "required|url",
            "team" => "required|exists:App\Models\Team,id"
        ]);

        $team = Team::find($validated['team']);

        Gate::forUser($request->user())->authorize('createLink', $team);

        $team->links()->create($validated);

        return redirect('dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function show(Link $link)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function edit(Link $link)
    {
        //
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
        $validated = $request->validate([
            "name" => "required|alpha_num",
            "value" => "required|url"
        ]);

        Gate::forUser($request->user())->authorize('updateLink', $link->team);

        $link->name = $validated['name'];
        $link->value = $validated['value'];
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
