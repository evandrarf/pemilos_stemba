<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Recapitulation\RecapitulationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RecapitulationController extends Controller
{
    private $recapitulationService;

    public function __construct(RecapitulationService $recapitulationService)
    {
        $this->recapitulationService = $recapitulationService;
    }

    public function index()
    {
        return Inertia::render('admin/recapitulation/index', ['title' => 'Recapitulation | Pemilos']);
    }

    public function getSummary()
    {
        try {

            $data = $this->recapitulationService->getSummary();

            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function getVoterSummary(Request $request)
    {
        try {

            $data = $this->recapitulationService->getVoterSummary($request);

            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
