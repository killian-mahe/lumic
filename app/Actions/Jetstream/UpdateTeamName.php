<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;

class UpdateTeamName implements UpdatesTeamNames
{
    /**
     * Validate and update the given team's name.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  array  $input
     * @return void
     */
    public function update($user, $team, array $input)
    {
        Gate::forUser($user)->authorize('update', $team);

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:20']
        ])->after(
            $this->ensureSlugIsValid($team, $input['slug'])
        )->validateWithBag('updateTeamName');

        $team->forceFill([
            'name' => $input['name'],
            'slug' => $input['slug']
        ])->save();
    }

    /**
     * Ensure that the given slug is valid.
     *
     * @param $team
     * @param $slug
     */
    protected function ensureSlugIsValid($team, $slug)
    {
        return function ($validator) use ($slug, $team) {

            if ($slug && $team->slug != $slug)
            {
                $validator->errors()->addIf(
                    Team::where('slug', $slug)->exists(),
                    'slug',
                    __('The slug has already been taken.')
                );
            }
        };
    }
}
