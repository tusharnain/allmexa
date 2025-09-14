<?php

namespace App\Filters\Admin;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        $admin = admin();

        if (!$admin) {


            if ($request->isAJAX()) {
                return resJson(['f_redirect' => route('admin.login')], 400);
            }

            return response()->redirect(route('admin.login'));
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
