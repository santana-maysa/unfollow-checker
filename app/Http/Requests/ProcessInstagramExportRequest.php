<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessInstagramExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'followers' => [
                'required',
                'array',
                'min:1',
                'max:20',
            ],

            'followers.*' => [
                'required',
                'file',
                'extensions:json',
                'mimes:json,txt',
                'max:10240',
            ],

            'following' => [
                'required',
                'file',
                'extensions:json',
                'mimes:json,txt',
                'max:10240',
            ],

            'pending_follow_requests' => [
                'nullable',
                'file',
                'extensions:json',
                'mimes:json,txt',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'followers.required' => 'Envie pelo menos um arquivo de seguidores.',
            'followers.array' => 'Os arquivos de seguidores são inválidos.',
            'followers.min' => 'Envie pelo menos um arquivo de seguidores.',
            'followers.max' => 'Você pode enviar no máximo 20 arquivos de seguidores.',

            'followers.*.required' => 'Um dos arquivos de seguidores não foi enviado.',
            'followers.*.file' => 'Um arquivo de seguidores é inválido.',
            'followers.*.extensions' => 'Os arquivos de seguidores devem possuir extensão .json.',
            'followers.*.mimes' => 'O conteúdo dos arquivos de seguidores deve ser JSON.',
            'followers.*.max' => 'Cada arquivo de seguidores deve ter no máximo 10 MB.',

            'following.required' => 'Envie o arquivo de seguindo.',
            'following.file' => 'O arquivo de seguindo é inválido.',
            'following.extensions' => 'O arquivo de seguindo deve possuir extensão .json.',
            'following.mimes' => 'O conteúdo do arquivo de seguindo deve ser JSON.',
            'following.max' => 'O arquivo de seguindo deve ter no máximo 10 MB.',

            'pending_follow_requests.file' => 'O arquivo de solicitações pendentes é inválido.',
            'pending_follow_requests.extensions' => 'O arquivo de solicitações pendentes deve possuir extensão .json.',
            'pending_follow_requests.mimes' => 'O conteúdo do arquivo de solicitações pendentes deve ser JSON.',
            'pending_follow_requests.max' => 'O arquivo de solicitações pendentes deve ter no máximo 10 MB.',
        ];
    }
}