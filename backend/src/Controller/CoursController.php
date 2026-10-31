<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/cours')]
class CoursController extends AbstractController
{
    private function toArray(Cours $c): array
    {
        return [
            'id' => $c->getId(),
            'nom' => $c->getNom(),
            'description' => $c->getDescription(),
            'niveau' => $c->getNiveau(),
        ];
    }

    #[Route('', name: 'api_cours_index', methods: ['GET'])]
    public function index(Request $request, CoursRepository $repo): JsonResponse
    {
        $liste = $repo->search(
            $request->query->get('q'),
            $request->query->get('niveau')
        );

        return $this->json(array_map(fn ($c) => $this->toArray($c), $liste));
    }

    #[Route('/{id}', name: 'api_cours_show', methods: ['GET'])]
    public function show(Cours $cours): JsonResponse
    {
        return $this->json($this->toArray($cours));
    }

    #[Route('', name: 'api_cours_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        if (!$this->isGranted('ROLE_COACH') && !$this->isGranted('ROLE_SALLE')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $d = json_decode($request->getContent(), true) ?? [];
        if (empty($d['nom']) || empty($d['niveau'])) {
            return $this->json(['erreur' => 'Champs requis : nom, niveau'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $c = new Cours();
        $c->setNom($d['nom']);
        $c->setNiveau($d['niveau']);
        $c->setDescription($d['description'] ?? null);
        $em->persist($c);
        $em->flush();

        return $this->json($this->toArray($c), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_cours_update', methods: ['PUT'])]
    public function update(Cours $cours, Request $request, EntityManagerInterface $em): JsonResponse
    {
        if (!$this->isGranted('ROLE_COACH') && !$this->isGranted('ROLE_SALLE') && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $d = json_decode($request->getContent(), true) ?? [];

        if (isset($d['nom'])) { $cours->setNom($d['nom']); }
        if (isset($d['niveau'])) { $cours->setNiveau($d['niveau']); }
        if (array_key_exists('description', $d)) { $cours->setDescription($d['description']); }
        $em->flush();

        return $this->json($this->toArray($cours));
    }

    #[Route('/{id}', name: 'api_cours_delete', methods: ['DELETE'])]
    public function delete(Cours $cours, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em->remove($cours);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
