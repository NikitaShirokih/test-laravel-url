<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Security\Rules\HttpUrlRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreShortLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'original_url' => [
                'required',
                'string',
                'url',
                'max:2048',
                new HttpUrlRule,
            ],
        ];
    }
}
