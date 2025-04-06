<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use App\Http\Requests\StoreFormSubmissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FormSubmissionController extends Controller
{
    public function index(): JsonResponse
    {
        $submissions = FormSubmission::latest()->get();
        
        return response()->json([
            'data' => $submissions
        ]);
    }
    public function store(StoreFormSubmissionRequest $request): JsonResponse
    {
        $data = $request->all();
         
        if ($request->hasFile('comprobante_pago')) {
            $path = $request->file('comprobante_pago')->store('comprobantes', 'public');
            $data['comprobante_pago'] = $path;
        }

        $submission = FormSubmission::create($data);

        return response()->json([
            'message' => 'Formulario enviado exitosamente',
            'data' => $submission
        ], 201);
    }

}
