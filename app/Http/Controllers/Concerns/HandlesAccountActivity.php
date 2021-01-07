<?php


namespace App\Http\Controllers\Concerns;


use App\Models\AccountActivity;
use App\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HandlesAccountActivity extends UseCaseConcerns
{
    use ValidatesRequests;

    /**
     * @var User|null
     */
    private $user;

    /**
     * @var AccountActivity
     */
    private $accountActivity;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @throws ValidationException
     */
    public function saveAccountActivity()
    {
        $this->validateActivity()->getUser()->saveActivityToUser();
    }

    /**
     * Validates request
     *
     * @return $this
     * @throws ValidationException
     */
    protected function validateActivity()
    {
        $rules = [
            'user_id' => 'required',
            'activity_date' => 'required',
            'amount' => 'required',
            'description' => 'required'
        ];

        $this->validate($this->request, $rules);

        return $this;
    }

    protected function getUser()
    {
        $this->user = User::query()->findOrFail($this->request->input('user_id'));

        return $this;
    }

    protected function saveActivityToUser()
    {
        $this->user->createActivity(
            $this->request->input('amount'),
            $this->request->input('description'),
            $this->request->input('activity_date')
        );

        return $this;
    }

    /**
     * @param AccountActivity $accountActivity
     * @throws ValidationException
     */
    public function updateActivity(AccountActivity $accountActivity)
    {
        $this->validateActivity();

        $this->accountActivity = $accountActivity;

        $this->user = User::query()->findOrFail($this->request->user_id);

        $this->user->updateActivity($this->request);
    }
}
