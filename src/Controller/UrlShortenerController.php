<?php

namespace App\Controller;

use App\Entity\UrlShortener as EntityUrlShortener;
use App\Form\UrlShortenerType;
use App\Services\UrlShortener;
use DateTimeImmutable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlShortenerController extends AbstractController
{
    #[Route('/', name: 'url_shortener_index')]
    public function index(): Response
    {
        return $this->redirectToRoute('url_shortener_convert_to_short');
    }

    #[Route('/url/shortener/{id}', name: 'url_shortener_show')]
    public function show(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $validTime = $_ENV['SHORT_URL_VALID_TIME'];
       
        $urlShortener = $entityManager->getRepository(EntityUrlShortener::class)->find($id);

        if (!$urlShortener) {
            throw $this->createNotFoundException(
                'No Short URL found for id '.$id
            );
        }

        return $this->render('url_shortener/show.html.twig', [
            'shortUrl' => $this->generateUrl('url_shortener_convert_to_long', [
                'token' => $urlShortener->getShortUrl(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'longUrl' => $urlShortener->getLongUrl(),
        ]);
    }


    #[Route('/url/list/all', name: 'url_shortener_list_all')]
    public function listAllUrls(EntityManagerInterface $entityManager, Request $request): Response
    {
        $validTime = $_ENV['SHORT_URL_VALID_TIME'];
       
        $urlShortenerAll = $entityManager->getRepository(EntityUrlShortener::class)->findAll();

        if (!$urlShortenerAll) {
            throw $this->createNotFoundException(
                'No Short URL found'
            );
        }

        return $this->render('url_shortener/list_all.html.twig', [
            'urlShortenerAll' => $urlShortenerAll,
            'validTime' => $validTime,
        ]);
    }



    #[Route('/url/shortener/convert/toshort', name: 'url_shortener_convert_to_short')]
    public function convertUrlToShort(EntityManagerInterface $entityManager, Request $request, UrlShortener $urlShortenerService): Response
    {
        try {
            $urlShortener = new EntityUrlShortener();

            $form = $this->createForm(UrlShortenerType::class, $urlShortener); 
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())  {
                $urlShortener = $form->getData();
                $urlShortener->setShortUrl($urlShortenerService->generateToken());
                $urlShortener->setCreatedAt(new DateTimeImmutable());
                $entityManager->persist($urlShortener);
                $entityManager->flush();

                $this->addFlash('success', 'Success! Short URL generated for your original URL!');
                return $this->redirectToRoute('url_shortener_show', ['id' => $urlShortener->getId()]);
            }

            return $this->render('url_shortener/create.html.twig', [
                'form' => $form
            ]);

        } catch (UniqueConstraintViolationException $e) {
            $this->addFlash('error', 'Error! '. $e->getMessage());
            return $this->redirectToRoute('url_shortener_convert_to_short');
        }
    }

    #[Route('/url/shortener/delete/{id}', name: 'url_shortener_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {     
        try {
            $urlShortener = $entityManager->getRepository(EntityUrlShortener::class)->find($id);

            if (!$urlShortener) {
                throw $this->createNotFoundException(
                    'No Short URL found in our Data Base!'
                );
            }

            
            $entityManager->remove($urlShortener);
            $entityManager->flush();

            $this->addFlash('success', 'Short URL record deleted! Now you can use the same long URL to generate a new short URL!');
       
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error! '. $e->getMessage());
        }
        
        return $this->redirectToRoute('url_shortener_list_all');
    }



    #[Route('/{token}', name: 'url_shortener_convert_to_long', requirements: ['token' => '[a-zA-Z0-9]{9}'])]
    public function convertUrlToLong(EntityManagerInterface $entityManager, Request $request, string $token): Response
    {
        $urlShortener = $entityManager->getRepository(EntityUrlShortener::class)->findOneBy(['shortUrl' => $token]);
       
        if (!$urlShortener) {
            throw $this->createNotFoundException(
                'No Long URL found for short url '.$token
            );
        }

        $validTime = $_ENV['SHORT_URL_VALID_TIME'];

        if($urlShortener->getCreatedAt()->getTimeStamp() <= (time() - $validTime)) {
            return $this->render('url_shortener/expired.html.twig', [
                'shortUrl' => $request->getUri(),
            ]);
        }

        return $this->redirect($urlShortener->getLongUrl());
    }    


    #[Route('/{slug}', name: 'url_shortener_fallback', requirements: ['token' => '[a-zA-Z0-9]'])]
    public function fallbackRoute(EntityManagerInterface $entityManager, Request $request, string $slug): Response
    {    
        return $this->render('url_shortener/invalid.html.twig');
    }    
}
