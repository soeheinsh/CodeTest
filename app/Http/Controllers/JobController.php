<?php

namespace App\Http\Controllers;

use App\Services\EmployeeManagement\NonEmployeeInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JobController extends Controller
{
    private NonEmployeeInterface $applicant;

    public function __construct(NonEmployeeInterface $applicant)
    {
        $this->applicant = $applicant;
    }
    
    public function apply(Request $request)
    {
        $data = $this->applicant->applyJob();
        
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'Job application successful',
        ], Response::HTTP_OK);
    }
}
