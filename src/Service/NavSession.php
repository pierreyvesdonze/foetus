<?php

// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class NavSession
{
    private $session;
    private $request;

    public function __construct(SessionInterface $session, Request $request)
    {
        $this->session = $session;
        $this->request = $request;
    }

    public function getPreviousPage($currentPage)
    {
        $this->session->set('route-name', $currentPage);

        // On récupère la précédente page visitée
        $previousPage = $this->session->get('route-name');

        return $previousPage;
    }
}