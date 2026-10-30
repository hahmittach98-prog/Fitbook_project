<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/profil')]
class ProfilController extends AbstractController
{
    #[Route('', name: 'api_profil', methods: ['GET'])]
    public function show(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'telephone' => $user->getTelephone(),
            'roles' => $user->getRoles(),
            'imageName' => $user->getImageName(),
            'creeLe' => $user->getCreatedAt()?->format('Y-m-d H:i'),
            'modifieLe' => $user->getUpdatedAt()?->format('Y-m-d H:i'),
        ]);
    }

    #[Route('', name: 'api_profil_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $em): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true) ?? [];

        if (isset($data['nom'])) {
            $user->setNom($data['nom']);
        }
        if (isset($data['prenom'])) {
            $user->setPrenom($data['prenom']);
        }
        if (array_key_exists('telephone', $data)) {
            $user->setTelephone($data['telephone']);
        }
        $em->flush();

        return $this->json(['message' => 'Profil mis à jour']);
    }

        #[Route('/image', name: 'api_profil_image', methods: ['POST'])]
    public function image(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $file = $request->files->get('image');
        if (!$file) {
            return $this->json(['erreur' => 'Fichier "image" requis'], Response::HTTP_BAD_REQUEST);
        }
        /** @var User $user */
        $user = $this->getUser();
        $user->setImageFile($file);
        $em->flush();

        return $this->json(['imageName' => $user->getImageName()], Response::HTTP_CREATED);
    }
}