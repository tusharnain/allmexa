<?php

namespace App\Filters\User;

use App\Services\UserService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class UserNoAuth implements FilterInterface
{
    private null|object $user;
    public function __construct()
    {
        $this->user = session('user'); // can't use user() here, coz thats static data
    }
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!$this->noAuthFilterAction()) {

            if ($request->isAJAX()) {
                return resJson(['f_redirect' => route('user.home')], 400);
            }

            return response()->redirect(route('user.home'));
        }
    }

    private function noAuthFilterAction(): bool
    {
        if (!$this->user and ($this->user = UserService::checkLoginRememberCookie(updateSessionAndCookie: true))) {

            $this->user = UserService::loginSessionData(user: $this->user); // setting actual user data session
        }

        if ($this->user)
            return false;

        return true;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
