<?php

namespace App\Filters\User;

use App\Constants\Constants;
use App\Services\UserService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class UserAuth implements FilterInterface
{
    private null|object $user;
    private null|object $admin;

    public function __construct()
    {
        $this->user = session('user');
        $this->admin = session('admin');
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        if (!$this->authFilterAction()) {

            if ($this->user) {
                UserService::removeUserSession($this->user->id, onlyRemoveCookie: true);
                UserService::loginSessionData(remove: true);
            }


            if ($request->isAJAX()) {
                return resJson(['f_redirect' => route('login')], 400);
            }

            return response()->redirect(route('login'));
        }
    }

    private function authFilterAction(): bool
    {

        if ($this->admin and $this->user and isset($this->user->adminLogin))
            return true;


        if ($this->user and !$this->loginSessionCheck())
            return false;


        if (!$this->user and ($this->user = UserService::checkLoginRememberCookie(updateSessionAndCookie: true))) {
            $this->user = UserService::loginSessionData(user: $this->user);
        }

        if (!$this->user)
            return false;

        return true;
    }

    private function loginSessionCheck(): bool
    {
        $sessionRec = user_model()->sessionsTable()->where('user_id', $this->user->id)->get()->getRow();

        $userSessionId = session(Constants::USER_SESSION_ID_KEY);

        if (!$sessionRec or !$userSessionId or ($userSessionId !== $sessionRec->session_id))
            return false;

        return true;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
