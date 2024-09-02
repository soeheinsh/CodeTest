<?php

namespace App\Http\Controllers;

use App\Services\EmployeeManagement\EmployeeInterface;
use Symfony\Component\HttpFoundation\Response;

class StaffController extends Controller
{
    private EmployeeInterface $staff;

    public function __construct(EmployeeInterface $staff)
    {
        $this->staff = $staff;
    }
    
    public function payroll()
    {
        $data = $this->staff->salary();
    
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'Salary calculation successful',
        ],  Response::HTTP_OK);
    }
}
