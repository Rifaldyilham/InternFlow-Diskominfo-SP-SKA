<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePesertaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'nim' => 'required|string|max:20',
            'no_telp' => 'required|string|max:15',
            'universitas' => 'required|string|max:100',
            'jurusan' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'alasan' => 'required|string|min:150|max:2000',
            'bidang_pilihan' => 'required|exists:bidang,id_bidang',
            'surat_file' => 'required|file|mimes:pdf|max:5120', // max 5MB
            'cv_file' => 'required|file|mimes:pdf|max:5120', // max 5MB
        ];
    }

    public function messages()
    {
        return [
            'surat_file.mimes' => 'Surat pengantar harus berupa file PDF',
            'surat_file.max' => 'Surat pengantar maksimal 5MB',
            'cv_file.mimes' => 'CV harus berupa file PDF',
            'cv_file.max' => 'CV maksimal 5MB',
            'alasan.min' => 'Alasan minimal 150 karakter',
        ];
    }
}