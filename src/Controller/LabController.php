<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Cookie as Cookie;

#[Route('/lab', name: 'app_lab')]
final class LabController extends AbstractController
{
    #[Route('/xss', name: 'xss_lab', methods:["GET"])]
    public function xssLab(Request $req): Response
    {
       $search = $req->query->get('q', '');

       $response = new Response("
            <html>
            <head><title>Search</title></head>
            <body>
                <h1>Search</h1>
                <form method='GET'>
                    <input type='text' name='q' value='" . $search . "'>
                    <button type='submit'>Search</button>
                </form>
                <p>Vous avez recherché : " . $search . "</p>
            </body>
            </html>
       ");

       $response->headers->setCookie(new Cookie('flag', 'flag{xss_r3fl3ct3d_f0und}', 0, '/', null, false, false));

       return $response;
    }
}
