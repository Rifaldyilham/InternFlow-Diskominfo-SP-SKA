<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePesertaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Require authenticated user to register as peserta
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'nim' => 'required|string|max:20',
            'asal_univ' => 'nullable|string|max:100',
            'program_studi' => 'nullable|string|max:100',
            'no_telp' => 'nullable|string|max:15',
            'surat_penempatan' => 'nullable|file|mimes:pdf|max:2048',
            'cv' => 'nullable|file|mimes:pdf|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'surat_penempatan.mimes' => 'Surat penempatan harus berupa PDF.',
            'cv.mimes' => 'CV harus berupa PDF.',
        ];
    }
}
