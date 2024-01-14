<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Motdepasse;
use App\Entity\Trader;

class TraderTest extends TestCase
{
    public function testExiste(): void
    {
        $trader = new Trader();

        $motDePasse = new Motdepasse();
        $motDePasse->setNom("test1");
        $trader->addLesMotsDePasse($motDePasse);

        $motDePasse2 = new Motdepasse();
        $motDePasse2->setNom("test2");
        $trader->addLesMotsDePasse($motDePasse2);

        $test1 = $trader->verifierMdp("test1");
        $this->assertEquals(true, $test1, "Le retour doit être true");

        $test2 = $trader->verifierMdp("test2");
        $this->assertEquals(false, $test2, "Le retour doit être false");
    }
}
