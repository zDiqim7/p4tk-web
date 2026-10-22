<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'catalog_type' => ['required', 'in:menu,product'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('products', 'slug')->ignore($this->route('product')),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'is_available' => ['required', 'boolean'],
            'is_featured' => ['required', 'boolean'],
            'catalog_attributes' => ['sometimes', 'array'],
            'catalog_attributes.type' => ['nullable', 'string', 'max:100'],
            'catalog_attributes.roast_level' => ['nullable', 'string', 'max:100'],
            'catalog_attributes.taste_type' => ['nullable', 'string', 'max:100'],
            'catalog_attributes.coffee_tastes_like' => ['nullable', 'string', 'max:255'],
            'catalog_attributes.region' => ['nullable', 'string', 'max:100'],
            'catalog_attributes.origin' => ['nullable', 'string', 'max:100'],
            'catalog_attributes.process' => ['nullable', 'string', 'max:100'],
        ];
    }
}
