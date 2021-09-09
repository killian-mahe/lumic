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
        return $this->render($request);
    }

    /**
     * Update the website version.
     *
     * @param Request $request
     * @return Redirector|Application|RedirectResponse
     */
    public function updateWebsite(Request $request): Redirector|Application|RedirectResponse
    {
        (new Process(['git', 'fetch']))->run();
        (new Process(['git', 'pull']))->run();
        (new Process(['git', 'reset', '--hard']))->run();

        (new Process(['composer', 'install']))->run();

        Artisan::call('migrate -n --force');

        Artisan::call('optimize -n');

        return redirect()->route('admin.panel');
    }

    /**
     * Render the administrator dashboard.
     *
     * @param Request $request
     * @param array $errors
     * @return Response
     */
    public function render(Request $request, array $errors = []): Response
    {
        $runtimeErrors = $errors;
        $commits_number = null;
        $current_branch = $this->getCurrentBranch();

        try {
            $commits_number = $this->getCommitsNumber($current_branch);
        } catch (\Exception $e) {
            $runtimeErrors[] = $e->getMessage();
        }

        return Inertia::render('Administration/Show', [
            "current_branch" => $current_branch,
            "commit_diff" => $commits_number,
            "errors" => $runtimeErrors
        ]);
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

        return $availableBranches;
    }
}
