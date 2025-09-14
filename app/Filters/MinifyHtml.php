<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MinifyHtml implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        //
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $contentType = $response->getHeader('Content-Type')->getValue();

        if ($contentType and (strpos($contentType, 'text/html') !== false)) {
            $output = $response->getBody();
            $output = \App\Libraries\MyLib::minifyHtmlCssJs($output);

            $response->setBody($output);
        }

    }


}
