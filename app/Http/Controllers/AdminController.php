<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\Process\Process;

class AdminController extends Controller
{
    /**
     * Display Administration panel information.
     *
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $error_message = "";
        $output = null;

        try {
            (new Process(['git', 'fetch']))->run();
            (new Process(['git', 'rev-list', '--count', 'main', '^origin/main']))
                ->run(function ($type, $buffer) use (&$output) {
                    dd($buffer);
                    if ($type === Process::ERR) throw new \Exception($buffer);
                    $output = (int)preg_replace("#\n|\t|\r#", "", $buffer);
                });
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
        }

        return Inertia::render('Administration/Show', [
            "commit_diff" => $output,
            "error_msg" => $error_message
        ]);
    }

    /**
     * Update the website version.
     *
     * @param Request $request
     * @return Redirector|Application|RedirectResponse
     */
    public function updateWebsite(Request $request): Redirector|Application|RedirectResponse
    {
        (new Process(['git', 'pull']))->run();

        Artisan::call('migrate');

        return redirect()->route('admin.panel');
    }
}
