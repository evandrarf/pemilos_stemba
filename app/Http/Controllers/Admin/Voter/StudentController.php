<?php

namespace App\Http\Controllers\Admin\Voter;

use App\Actions\Options\GetClassOptions;
use App\Exports\StudentVoterExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStudentVoterRequest;
use App\Http\Requests\ImportStudentVoterRequest;
use App\Http\Requests\UpdateStudentVoterRequest;
use App\Http\Resources\Voter\Student\StudentListResource;
use App\Http\Resources\Voter\Student\SubmitStudentVoterResource;
use App\Services\Voter\Student\StudentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    private $studentService;
    private $getClassOptions;

    public function __construct(StudentService $studentService, GetClassOptions $getClassOptions)
    {
        $this->studentService = $studentService;
        $this->getClassOptions = $getClassOptions;
    }

    public function index()
    {
        $statusOptions = [true => 'Done', false => 'Not yet'];

        return Inertia::render('admin/voters/student/index', [
            'title' => 'Pemilos | Student Voter',
            'additional' => [
                'status_list' => $statusOptions
            ]
        ]);
    }

    public function getClassList()
    {
        $classOptions = $this->getClassOptions->handle();

        return $this->respond($classOptions);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->studentService->getData($request);

            $result = new StudentListResource($data);

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createStudentVoter(CreateStudentVoterRequest $request)
    {
        try {
            $data = $this->studentService->createStudentVoter($request);

            $result = new SubmitStudentVoterResource($data, 'Success create student voter');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function updateStudentVoter(UpdateStudentVoterRequest $request, $id)
    {
        try {
            $data = $this->studentService->updateStudentVoter($request, $id);

            $result = new SubmitStudentVoterResource($data, 'Success update student voter');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteStudentVoter($id)
    {
        try {
            $data = $this->studentService->deleteStudentVoter($id);

            $result = new SubmitStudentVoterResource($data, 'Success delete student voter');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function importStudentVoter(ImportStudentVoterRequest $request)
    {
        try {
            $data = $this->studentService->importStudentVoter($request);

            $result = new SubmitStudentVoterResource($data, 'Success import student voter');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function downloadTemplateStudentVoter()
    {
        try {
            return response()->download(public_path('template/student-voter.xlsx'), 'student-voter.xlsx');
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function exportStudentVoter()
    {
        try {
            $data = $this->studentService->exportStudentVoter();

            return $data->download('student-voter.xlsx');
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteAllStudentVoter()
    {
        try {
            $data = $this->studentService->deleteAllStudentVoter();

            $result = new SubmitStudentVoterResource($data, 'Success delete all student voter');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
