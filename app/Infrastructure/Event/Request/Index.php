<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Request;

use Illuminate\Foundation\Http\FormRequest;

final class Index extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'min:3', 'max:255', 'string',],
            'page' => ['sometimes', 'integer', 'min:1'],
            'limit' => ['sometimes', 'integer', 'min:1'],
            'sort' => ['sometimes', 'string', 'in:id,title,description,start,end'],
            'order' => ['sometimes', 'string', 'in:asc,desc'],
            'start' => ['sometimes', 'date:Y-m-d', 'required_with:to', 'before:end'],
            'end' => ['sometimes', 'date:Y-m-d', 'required_with:from', 'after:start'],
        ];
    }
}
