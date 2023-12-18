<?php


namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class JsonInterceptor implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Code to run before the controller method is executed.
        // You can modify the request or perform other actions.

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Code to run after the controller method is executed.
        // You can modify the response or perform other actions.

        // Check if the response is intended to be JSON.
        if ($response->getHeaderLine('Content-Type') === 'application/json') {
            // Intercept and modify JSON response.
            $jsonBody = json_decode($response->getBody(), true);

            // Add or modify properties in the JSON response.
            $jsonBody['intercepted'] = true;

            // Set the modified JSON response back to the response object.
            $response->setJSON($jsonBody);
        }

        return $response;
    }
}
