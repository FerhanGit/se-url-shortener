<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrlShortenerTest extends WebTestCase
{
    public function testMainPageRedirectsToUrlCreationPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

      //  $this->assertResponseIsSuccessful();
        $this->assertResponseRedirects('/url/shortener/convert/toshort', 302, $message = '');
        //$this->assertSelectorTextContains('body', 'Redirecting to <a href="/url/shortener/convert/toshort">/url/shortener/convert/toshort</a>');
    }

    public function testUrlCreationPageIsLoaded(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/url/shortener/convert/toshort');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5', 'URL shortener service');
      //  $this->assertSelectorTextContains('input', 'Create Short URL');
        $this->assertSelectorTextContains('button', 'Create Short URL');
    }


    public function testGenerateShortUrlFormSubmit(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/url/shortener/convert/toshort');

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('url_shortener_save');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();
        // set values on a form object
        $form['url_shortener[longUrl]'] = 'https://symfony.com/doc/current/session.html';
        
        // submit the Form object
        $client->submit($form);

       // $this->assertFormValue('#url_shortener_form', 'longUrl', 'https://symfony.com/doc/current/session.html');

    }
}
