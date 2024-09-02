<?php

namespace App\Http\Controllers;

use App\Services\InternetServiceProvider\InternetServiceProviderInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InternetServiceProviderController extends Controller
{
    public function getInvoiceAmount(Request $request, $entity): JsonResponse
    {
        try {

            $internetServiceProvider = $this->resolveProvider($entity);
            $month = $request->input('month', 1);
            $internetServiceProvider->setMonth($month);

            return response()->json([
                'status' => 'success',
                'data' => $internetServiceProvider->calculateTotalAmount(),
                'message' => 'Get your wifi bill successful'
            ], JsonResponse::HTTP_OK);
            
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function resolveProvider($entity): InternetServiceProviderInterface
    {
        if ($entity === 'mpt') {
            return app()->make('App\Services\InternetServiceProvider\Mpt'); // Corrected namespace
        } elseif ($entity === 'ooredoo') {
            return app()->make('App\Services\InternetServiceProvider\Ooredoo');
        }

        throw new \InvalidArgumentException("Invalid ISP provider: $entity");
    }
}
