<?php


namespace App\Http\Controllers\Concerns;


use App\Exceptions\AuthenticationFailed;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatesUser extends UseCaseConcerns
{
    use ValidatesRequests;

    /**
     * @var User|null
     */
    private $user;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Fluently go through the process of logging in a user
     *
     * @return mixed
     * @throws ValidationException
     * @throws AuthenticationFailed
     */
    public function loginUser()
    {
        return $this->validateRequest()
            ->authenticateUser()
            ->getToken();
    }

    /**
     * Validates or throws an error
     *
     * @return $this
     * @throws ValidationException
     */
    private function validateRequest()
    {
        $rules = [
            'email' => 'required|email|exists:users'
        ];

        $this->validate($this->request, $rules);

        return $this;
    }

    /**
     * Authenticate the user
     *
     * @return $this
     * @throws AuthenticationFailed
     */
    private function authenticateUser()
    {
        if (!Auth::attempt($this->request->only(['email', 'password'])))
            throw new AuthenticationFailed;

        $this->user = Auth::user();

        return $this;
    }

    private function getToken()
    {
        $token = $this->user->createToken($this->user->email . '-' . now());

        if ($this->request->remember_me) {
            $token->token->expires_at = Carbon::now()->addWeek();

            $token->token->save();
        }

        return [
            'token' => $token->accessToken,
            'expires_in' => Carbon::now()->diffInDays($token->token->expires_at)
        ];
    }
}
