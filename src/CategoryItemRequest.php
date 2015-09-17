<?php
namespace Minhbang\LaravelCategory;

use Minhbang\LaravelKit\Extensions\Request;

/**
 * Class CategoryItemRequest
 *
 * @package Minhbang\LaravelCategory
 */
class CategoryItemRequest extends Request
{
    public $trans_prefix = 'category::common';
    public $rules = [
        'title' => 'required|max:255',
        'slug'  => 'required|max:255',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

}
