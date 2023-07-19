<?php

namespace App\Http\Controllers\Admin\Voter;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeacherVoterRequest;
use App\Http\Requests\ImportTeacherVoterRequest;
use App\Http\Requests\UpdateTeacherVoterRequest;
use App\Http\Resources\Voter\Teacher\ExportTeacherVoterListResource;
use App\Http\Resources\Voter\Teacher\SubmitTeacherListResource;
use App\Http\Resources\Voter\Teacher\TeacherListResource;
use App\Models\Voter;
use App\Services\Voter\Teacher\TeacherService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherController extends Controller
{
    private $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function index()
    {
        $statusOptions = ['done' => 'Done', 'not-yet' => 'Not yet'];

        return Inertia::render('admin/voters/teacher/index', [
            'title' => 'Pemilos | Teacher Voter',
            'additional' => [
                'status_list' => $statusOptions
            ]
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->teacherService->getData($request);

            $result = new TeacherListResource($data);

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createTeacherVoter(CreateTeacherVoterRequest $request)
    {
        try {
            $data = $this->teacherService->createTeacherVoter($request);

            $result = new SubmitTeacherListResource($data, 'Success create teacher voter');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function updateTeacherVoter(UpdateTeacherVoterRequest $request, $id)
    {
        try {
            $data = $this->teacherService->updateTeacherVoter($request, $id);

            $result = new SubmitTeacherListResource($data, 'Success update teacher voter');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteTeacherVoter($id)
    {
        try {
            $data = $this->teacherService->deleteTeacherVoter($id);

            $result = new SubmitTeacherListResource($data, 'Success delete teacher voter');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function importTeacherVoter(ImportTeacherVoterRequest $request)
    {
        try {
            $data = $this->teacherService->importTeacherVoter($request);

            $result = new SubmitTeacherListResource($data, 'Success import teacher voter');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function downloadTemplateTeacherVoter()
    {
        try {
            return response()->download(public_path('template/teacher-voter.xlsx'), 'teacher-voter.xlsx');
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function exportTeacherVoter(Request $request)
    {
        try {
            $data = $this->teacherService->exportTeacherVoter($request);

            return $data->download('teacher-voter.xlsx');
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteAllTeacherVoter()
    {
        try {
            $data = $this->teacherService->deleteAllTeacherVoter();

            $result = new SubmitTeacherListResource($data, 'Success delete all teacher voter');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
