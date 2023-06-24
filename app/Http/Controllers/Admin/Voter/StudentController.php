<?php

namespace App\Http\Controllers\Admin\Voter;

use App\Actions\Options\GetClassOptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStudentVoterRequest;
use App\Http\Requests\UpdateStudentVoterRequest;
use App\Http\Resources\Voter\Student\StudentListResource;
use App\Http\Resources\Voter\Student\SubmitStudentVoterResource;
use App\Services\Voter\Student\StudentService;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        $classOptions = $this->getClassOptions->handle();
        $statusOptions = ['Done', 'Not yet'];

        return Inertia::render('admin/voters/student/index', [
            'title' => 'Pemilos | Student Voter',
            'additional' => [
                'class_list' => $classOptions,
                'status_list' => $statusOptions
            ]
        ]);
    }

    public function getData()
    {
        try {
            $data = $this->studentService->getData();

            $result = new StudentListResource($data);

            return $this->respond($result);
            // $data = $this->getClassOptions->handle();

            // return $this->respond($data);
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
}
