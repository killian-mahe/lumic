<?php

namespace App\Http\Controllers;

use App\Models\Config;
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
        $commits_number = null;
        $current_branch = $this->getCurrentBranch();

        try {
            $commits_number = $this->getCommitsNumber($current_branch);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            $error = true;
        }

        return Inertia::render('Administration/Show', [
            "available_branches" => $this->getGitBranches(),
            "current_branch" => $current_branch,
            "commit_diff" => $commits_number,
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

        Artisan::call('migrate');

        Artisan::call('optimize');

        return redirect()->route('admin.panel');
    }

    /**
     * Change the current Git branch.
     *
     * @param Request $request
     */
    public function changeCurrentBranch(Request $request)
    {
        try {
            (new Process(['git', 'fetch']))->run();
            (new Process(['git', 'checkout', $request->input('branch')]))->run(function ($type, $buffer) use (&$availableBranches) {
                if ($type == Process::ERR) throw new \Exception($buffer);
            });
        } catch (\Exception $e) { }
    }

    /**
     * Get the number of commits pending on remote.
     *
     * @param string $local_branch_name
     * @return int|null
     */
    private function getCommitsNumber(string $local_branch_name): int|null
    {
        $origin_commits = null;
        $local_commits = null;

        (new Process(['git', 'fetch']))->run();
        (new Process(['git', 'rev-list', '--count', 'remotes/origin/'.$local_branch_name]))
            ->run(function ($type, $buffer) use (&$origin_commits) {
                if ($type === Process::ERR) throw new \Exception($buffer);
                $origin_commits = (int)preg_replace("#\n|\t|\r#", "", $buffer);
            });
        (new Process(['git', 'rev-list', '--count', $local_branch_name]))
            ->run(function ($type, $buffer) use (&$local_commits) {
                if ($type === Process::ERR) throw new \Exception($buffer);
                $local_commits = (int)preg_replace("#\n|\t|\r#", "", $buffer);
            });

        if ($origin_commits != null && $local_commits != null) return $origin_commits - $local_commits;
        return null;
    }

    /**
     * Get the current Git branch.
     *
     * @return string
     */
    private function getCurrentBranch(): string
    {
        $current_branch = null;
        (new Process(['git', 'branch', '--show-current']))->run(function ($type, $buffer) use (&$current_branch) {
            if ($type == Process::ERR) throw new \Exception($buffer);
            $current_branch = preg_replace("#\n|\t|\r#", "", $buffer);
        });
        Config::set('git_branch', $current_branch);
        return $current_branch;
    }

    /**
     * Get all the Git branches available on the server.
     *
     * @return array
     */
    private function getGitBranches(): array
    {
        $availableBranches = [];

        (new Process(['git', 'fetch']))->run();
        (new Process(['git', 'branch', '-a']))->run(function ($type, $buffer) use (&$availableBranches) {
            if ($type == Process::ERR) throw new \Exception($buffer);
            $availableBranches = preg_split('/\r\n|\r|\n/', str_replace('*', '', str_replace(' ', '', $buffer)));
            array_pop($availableBranches);
        });

        dd("hello");

        return $availableBranches;
    }
}
