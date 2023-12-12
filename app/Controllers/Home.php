<?php

namespace App\Controllers;

use CodeIgniter\Session\Session;

class Home extends BaseController
{
    private $session;
    function __construct()
    {
        $this->session = Session();
    }

    public function index()
    {

        if (!$this->session->has('loggedIn')) {
            echo "
    <form action='/login' method='post'>
      <input type='hidden' name='<?= csrf_token() ?>' value='<?= csrf_hash() ?>' />
        <label for='username'>Username:</label>
        <input type='text' id='username' name='username' required>
        <br>

        <label for='password'>Password:</label>
        <input type='password' id='password' name='password' required>
        <br>

        <button type='submit'>Login</button>
    </form>
";
        }else {
            echo "<pre>";
            print_r($this->session->get('loggedIn'));
            echo "</pre>";
            echo "<a href='/logout'><button>Click Me! </button></a>";
        }

    }
}
