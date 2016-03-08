<?php
namespace Minhbang\Category;

use Minhbang\Kit\Extensions\Request;

/**
 * Class ItemRequest
 *
 * @package Minhbang\Category
 */
class ItemRequest extends Request
{
    public $trans_prefix = 'category::common';
    public $rules = [
        'title' => 'required|max:255',
        'slug'  => 'required|max:255',
    ];

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
