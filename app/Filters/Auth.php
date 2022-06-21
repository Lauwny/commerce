<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
      /* $session = $this->session->;

        if ($session->has('userdata')) {
            $userdata = $session->get('userdata');
            if (isset($userdata['logged_in'])) {//si utilisateur logged
                if ($request->uri->getPath() == 'login') {
                    return redirect()->to('user');
                }
            } else {//si utilisateur pas logged
                if ($request->getUri()->getPath() != 'login') {
                    return redirect()->to('login');
                }
            }
        }else{
            print_r("no data");

        }*/
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
