<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_pengguna' => ['required','string', 'max:255'],
            'no_telpon' => ['required','numeric','digits:12'],
            'alamat' => ['required','string','max:225'],
            'id_kelas' => ['required','string','max:225'],
            'email' => ['required','string','max:225'],
            'info' => ['required','string','max:225'],
            'password' => ['string','max:225'],
            'tgl_daftar' => ['string','max:225'],
            'status_akun' => ['string','max:225'],
            'referal' => ['string','max:225'],
            'bukti_transfer' => ['required','mimes:jpg,jpeg,png','max:4096'],
        ];
    }
}
