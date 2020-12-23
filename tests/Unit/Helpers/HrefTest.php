<?php

namespace Tests\Unit\Helpers;

use App\Helpers\Href;
use PHPUnit\Framework\TestCase;

class HrefTest extends TestCase
{
    private array $rawPhoneNumbers;

    public function setUp(): void
    {
        parent::setUp();

        // Вариации +78005553535
        $this->rawPhoneNumbers = [
            '+78005553535',
            '+7 800 5553535',
            '+7 800 555 35 35',
            '+7 800 555-35-35',
            '+7 800 555 - 35 - 35',
            '+7(800)5553535',
            '+7 (800) 555-35-35',
            '+7 (800) 555 - 35 - 35',
        ];
    }

    public function testForPhone()
    {
        $this->assertEquals('tel:+78005553535', Href::forPhone('+7 (800) 555-35-35'));
    }

    public function testForEmail()
    {
        $this->assertEquals('mailto:email@example.com', Href::forEmail('email@example.com'));

        $this->expectException(\DomainException::class);
        Href::forEmail('emailexample.com');
    }

    public function testForViber()
    {
        foreach ($this->rawPhoneNumbers as $rawPhoneNumber) {
            $viberHref = Href::forViber($rawPhoneNumber);
            $this->assertEquals('href="viber://chat?number=%2B78005553535', $viberHref);
        }

        $this->expectException(\DomainException::class);
        Href::forWhatsapp('88005553535');
    }

    public function testForWhatsapp()
    {
        foreach ($this->rawPhoneNumbers as $rawPhoneNumber) {
            $whatsappHref = Href::forWhatsapp($rawPhoneNumber);
            $this->assertEquals('https://wa.me/78005553535', $whatsappHref);
        }

        $this->expectException(\DomainException::class);
        Href::forWhatsapp('88005553535');
    }

    public function testForTelegram()
    {
        $this->assertEquals('https://t.me/withoutAt', Href::forTelegram('withoutAt'));
        $this->assertEquals('https://t.me/withAt', Href::forTelegram('@withAt'));
    }
}
