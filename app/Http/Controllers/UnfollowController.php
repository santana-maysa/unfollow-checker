<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessInstagramExportRequest;
use App\Services\InstagramExportParser;
use App\Services\PendingFollowRequestsParser;
use App\Services\UnfollowComparisonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;
use InvalidArgumentException;

class UnfollowController extends Controller
{
    public function __construct(
        private InstagramExportParser $parser,
        private PendingFollowRequestsParser $pendingParser,
        private UnfollowComparisonService $comparisonService,
    ) {}

    public function index(): View
    {
        return view('unfollow.index');
    }

    public function analyze(
        ProcessInstagramExportRequest $request
    ): View|RedirectResponse {

        try {
            $followers = [];

            foreach ($request->file('followers') as $file) {
                $json = $this->readFile($file);

                $usernames = $this->parser->parse($json);

                foreach ($usernames as $username) {
                    $followers[] = $username;
                }
            }

            $followingJson = $this->readFile(
                $request->file('following')
            );

            $following = $this->parser->parse($followingJson);

            $pendingFollowRequests = [];

            if ($request->hasFile('pending_follow_requests')) {
                $pendingJson = $this->readFile(
                    $request->file('pending_follow_requests')
                );

                $pendingFollowRequests = $this->pendingParser->parse(
                    $pendingJson
                );
            }

            $result = $this->comparisonService->compare(
                followers: $followers,
                following: $following
            );

            return view('unfollow.results', [
                'result' => $result,
                'statistics' => $result->getStatistics(),
                'pendingFollowRequests' => $pendingFollowRequests,
                'successMessage' => 'Análise realizada com sucesso!',
            ]);

        } catch (InvalidArgumentException $exception) {
            return back()
                ->withErrors([
                    'files' => $exception->getMessage(),
                ]);
        }
    }

    private function readFile(UploadedFile $file): string
    {
        $path = $file->getRealPath();

        if ($path === false) {
            throw new InvalidArgumentException(
                'Não foi possível acessar um dos arquivos enviados.'
            );
        }

        $content = file_get_contents($path);

        if ($content === false) {
            throw new InvalidArgumentException(
                'Não foi possível ler um dos arquivos enviados.'
            );
        }

        return $content;
    }
}
