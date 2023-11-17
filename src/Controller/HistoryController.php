<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\History;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoryController extends AbstractController
{
    #[Route('/exchange/values', name: 'app_exchange_values', methods: ['post'])]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['first']) || !isset($data['second'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }
        $first = $data['first'];
        $second = $data['second'];

        $history = new History();
        $history->setFirstIn($first);
        $history->setSecondIn($second);

        $entityManager->persist($history);
        $entityManager->flush();

        $history->replace();
        $history->update();
        $entityManager->flush();

        return $this->json(['message' => 'Done'], 201);
    }

    #[Route('/exchange', name: 'app_list', methods: ['post', 'get'])]
    public function list(EntityManagerInterface $entityManager): JsonResponse
    {
        $histories = $entityManager->getRepository(History::class)->findAll();
        return $this->json(['data' => $histories]);
    }
}
