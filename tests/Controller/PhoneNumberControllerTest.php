<?php

declare(strict_types = 1);

namespace App\Tests\Controller;

use App\Entity\PhoneNumber;
use App\Repository\PhoneNumberRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneNumberControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    private PhoneNumberRepository $repository;

    private string $path = '/phone/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(PhoneNumber::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('PhoneNumber index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): never
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'phone_number[content]' => 'Testing',
            'phone_number[countryCode]' => 'Testing',
            'phone_number[createdAt]' => 'Testing',
            'phone_number[isDefault]' => 'Testing',
            'phone_number[owner]' => 'Testing',
        ]);

        self::assertResponseRedirects('/phone/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): never
    {
        $this->markTestIncomplete();
        $fixture = new PhoneNumber();
        $fixture->setContent('My Title');
        $fixture->setCountryCode('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setIsDefault('My Title');
        $fixture->setOwner('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('PhoneNumber');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): never
    {
        $this->markTestIncomplete();
        $fixture = new PhoneNumber();
        $fixture->setContent('My Title');
        $fixture->setCountryCode('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setIsDefault('My Title');
        $fixture->setOwner('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'phone_number[content]' => 'Something New',
            'phone_number[countryCode]' => 'Something New',
            'phone_number[createdAt]' => 'Something New',
            'phone_number[isDefault]' => 'Something New',
            'phone_number[owner]' => 'Something New',
        ]);

        self::assertResponseRedirects('/phone/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getContent());
        self::assertSame('Something New', $fixture[0]->getCountryCode());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getIsDefault());
        self::assertSame('Something New', $fixture[0]->getOwner());
    }

    public function testRemove(): never
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new PhoneNumber();
        $fixture->setContent('My Title');
        $fixture->setCountryCode('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setIsDefault('My Title');
        $fixture->setOwner('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/phone/');
    }
}
