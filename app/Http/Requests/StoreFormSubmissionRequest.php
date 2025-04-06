<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        if ($this->isJson()) {
            $this->merge($this->json()->all());
        }
    }

    public function rules(): array
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mensaje' => 'required|string|min:10',
            'tipo_comprobante' => 'required|in:Factura,CFF,Ticket',
            'metodo_pago' => 'required|in:Efectivo,Transferencia,Tarjeta',
            'comprobante_pago' => 'nullable',
        ];

        // Solo aplicar validaciones de archivo si el método es Transferencia
        if ($this->input('metodo_pago') === 'Transferencia') {
            $rules['comprobante_pago'] = 'required|file|mimes:jpg,png,pdf|max:2048';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es requerido',
            'email.required' => 'El correo electrónico es requerido',
            'email.email' => 'El correo electrónico debe ser válido',
            'mensaje.required' => 'El mensaje es requerido',
            'mensaje.min' => 'El mensaje debe tener al menos 10 caracteres',
            'tipo_comprobante.required' => 'El tipo de comprobante es requerido',
            'tipo_comprobante.in' => 'El tipo de comprobante debe ser Factura, CFF o Ticket',
            'metodo_pago.required' => 'El método de pago es requerido',
            'metodo_pago.in' => 'El método de pago debe ser Efectivo, Transferencia o Tarjeta',
            'comprobante_pago.required' => 'El comprobante de pago es requerido cuando el método es Transferencia',
            'comprobante_pago.file' => 'El comprobante debe ser un archivo',
            'comprobante_pago.mimes' => 'El comprobante debe ser un archivo JPG, PNG o PDF',
            'comprobante_pago.max' => 'El comprobante no debe ser mayor a 2MB',
        ];
    }
}
