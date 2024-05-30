<?php

namespace App\Http\Controllers\Api;

use App\Services\ApiFootballService;
use App\Jobs\MakeApiRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubmitController extends Controller
{
    protected $apiFootballService;

    public function __construct(ApiFootballService $apiFootballService)
    {
        $this->apiFootballService = $apiFootballService;
    }

    public function __invoke(Request $request)
    {
        $params = $request->except('endpoint');
        $endpoint = $request->endpoint;

        $response = MakeApiRequest::dispatch($this->apiFootballService, $endpoint, $params);

        return response()->json(['message' => $response]);
    }
}
