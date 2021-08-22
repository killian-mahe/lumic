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
        $error = false;
        $error_message = "";
        $origin_commits = null;
        $local_commits = null;

        try {
            (new Process(['git', 'fetch']))->run();
            (new Process(['git', 'rev-list', '--count', 'origin/main'], "C:\\xampp\\htdocs\\janus"))
                ->run(function ($type, $buffer) use (&$origin_commits) {
                    if ($type === Process::ERR) throw new \Exception($buffer);
                    $origin_commits = (int)preg_replace("#\n|\t|\r#", "", $buffer);
                });
            (new Process(['git', 'rev-list', '--count', 'main']))
                ->run(function ($type, $buffer) use (&$local_commits) {
                    if ($type === Process::ERR) throw new \Exception($buffer);
                    $local_commits = (int)preg_replace("#\n|\t|\r#", "", $buffer);
                });
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            $error = true;
        }

        return Inertia::render('Administration/Show', [
            "commit_diff" => $origin_commits - $local_commits,
            "error" => $error,
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

        Artisan::call('migrate', ['--force']);

        Artisan::call('optimize');

        return redirect()->route('admin.panel');
    }
}
