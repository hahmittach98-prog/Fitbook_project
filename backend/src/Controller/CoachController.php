<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Repository\CoachRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/coachs')]
class CoachController extends AbstractController
{
    private function toArray(Coach $c): array
    {
        return [
            'id' => $c->getId(),
            'userId' => $c->getUser()?->getId(),
            'nom' => $c->getUser()?->getNom(),
            'prenom' => $c->getUser()?->getPrenom(),
            'specialite' => $c->getSpecialite(),
            'description' => $c->getDescription(),
            'tarifHoraire' => $c->getTarifHoraire(),
            'photo' => $c->getPhoto(),
            'imageName' => $c->getImageName(),
            'modifieLe' => $c->getUpdatedAt()?->format('Y-m-d H:i'),
        ];
    }

    #[Route('', name: 'api_coach_index', methods: ['GET'])]
    public function index(Request $request, CoachRepository $repo): JsonResponse
    {
        $tarifMax = $request->query->get('tarifMax');
        $coachs = $repo->search(
            $request->query->get('q'),
            $request->query->get('specialite'),
            $tarifMax !== null ? (float) $tarifMax : null
        );

        return $this->json(array_map(fn ($c) => $this->toArray($c), $coachs));
    }

    #[Route('/{id}', name: 'api_coach_show', methods: ['GET'])]
    public function show(Coach $coach): JsonResponse
    {
        return $this->json($this->toArray($coach));
    }

    #[Route('', name: 'api_coach_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, CoachRepository $repo): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_COACH');

        if ($repo->findOneBy(['user' => $this->getUser()])) {
            return $this->json(['erreur' => 'Profil coach déjà existant'], Response::HTTP_CONFLICT);
        }
        $d = json_decode($request->getContent(), true) ?? [];
        if (empty($d['specialite'])) {
            return $this->json(['erreur' => 'Champ requis : specialite'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $c = new Coach();
        $c->setUser($this->getUser());
        $c->setSpecialite($d['specialite']);
        $c->setDescription($d['description'] ?? null);
        $c->setTarifHoraire(isset($d['tarifHoraire']) ? (float) $d['tarifHoraire'] : null);
        $c->setPhoto($d['photo'] ?? null);
        $em->persist($c);
        $em->flush();

        return $this->json($this->toArray($c), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_coach_update', methods: ['PUT'])]
    public function update(Coach $coach, Request $request, EntityManagerInterface $em): JsonResponse
    {
        if ($coach->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $d = json_decode($request->getContent(), true) ?? [];

        if (isset($d['specialite'])) { $coach->setSpecialite($d['specialite']); }
        if (array_key_exists('description', $d)) { $coach->setDescription($d['description']); }
        if (array_key_exists('tarifHoraire', $d)) {
            $coach->setTarifHoraire($d['tarifHoraire'] !== null ? (float) $d['tarifHoraire'] : null);
        }
        if (array_key_exists('photo', $d)) { $coach->setPhoto($d['photo']); }
        $em->flush();

        return $this->json($this->toArray($coach));
    }

    #[Route('/{id}', name: 'api_coach_delete', methods: ['DELETE'])]
    public function delete(Coach $coach, EntityManagerInterface $em): JsonResponse
    {
        if ($coach->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $em->remove($coach);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

        #[Route('/{id}/image', name: 'api_coach_image', methods: ['POST'])]
    public function image(Coach $coach, Request $request, EntityManagerInterface $em): JsonResponse
    {
        if ($coach->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $file = $request->files->get('image');
        if (!$file) {
            return $this->json(['erreur' => 'Fichier "image" requis'], Response::HTTP_BAD_REQUEST);
        }
        $coach->setImageFile($file);
        $em->flush();

        return $this->json(['imageName' => $coach->getImageName()], Response::HTTP_CREATED);
    }
}