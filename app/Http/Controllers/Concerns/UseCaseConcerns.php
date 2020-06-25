<?php


namespace App\Http\Controllers\Concerns;


use Illuminate\Http\Request;

class UseCaseConcerns
{

    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
