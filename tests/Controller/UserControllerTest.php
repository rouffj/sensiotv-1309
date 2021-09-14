<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testRegisterForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'SensioTV');

        $client->clickLink('Register');
        $this->assertSelectorTextContains('h1', 'Create your account');
        $registerForm = $client->getCrawler()->selectButton('Create your SensioTV Account')->form();

        // On Failure
        $client->submit($registerForm, [
            'user[email]' => 'BadEmail',
        ]);
        $this->assertCount(7, $client->getCrawler()->filter('.badge-danger'));

        // On Success
        $client->submit($registerForm, [
            'user[firstName]' => 'Fabien',
            'user[lastName]' => 'POTENCIER',
            'user[email]' => 'fabien@fabien.io',
            'user[phone]' => '',
            'user[password][first]' => 'test',
            'user[password][second]' => 'test',
            'user[terms]' => true,
        ]);

        //print_r($client->getResponse()->getContent());die;
        $userRepository = $client->getContainer()->get('doctrine')->getRepository(User::class);
        $user = $userRepository->findOneByEmail('fabien@fabien.io');
        //dump($user);
        $this->assertNotNull($user);
        $this->assertEquals('Fabien', $user->getFirstName());
    }
}
