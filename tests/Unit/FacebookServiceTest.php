<?php

namespace Tests\Unit;

use App\Services\Facebook;
use Tests\TestCase;

class FacebookServiceTest extends TestCase
{
    /**
     * @var Facebook
     */
    private $fb;
    
    public function __construct(Facebook $fb)
    {
        parent::__construct();
    
        $this->fb = $fb;
    }
}
