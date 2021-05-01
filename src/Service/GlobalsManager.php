<?php

namespace App\Service;

use App\Entity\SocialLink;
use App\Repository\SocialLinkRepository;

class GlobalsManager {

    private $socialRepo;
    
    public function __construct(SocialLinkRepository $socialRepo) {
        
        $this->socialRepo = $socialRepo;
    }
    
    public function getSocialLinks() {
        
        $socialLinks = $this->socialRepo->findAll();

        return $socialLinks;
    }
}