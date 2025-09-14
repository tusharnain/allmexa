<?php

namespace App\Filters\Admin;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminNoAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $admin = admin();

        if ($admin and $admin->loginId) {


            if ($request->isAJAX()) {
                return resJson(['f_redirect' => route('admin.home')], 400);
            }

            return response()->redirect(route('admin.home'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
