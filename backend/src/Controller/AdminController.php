<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use App\Repository\CoachRepository;
use App\Repository\ReservationRepository;
use App\Repository\SalleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin')]
class AdminController extends AbstractController
{
    #[Route('/users', name: 'api_admin_users', methods: ['GET'])]
    public function users(UserRepository $repo): JsonResponse
    {
        return $this->json(array_map(fn ($u) => [
            'id' => $u->getId(),
            'email' => $u->getEmail(),
            'nom' => $u->getNom(),
            'prenom' => $u->getPrenom(),
            'roles' => $u->getRoles(),
            'verifie' => $u->isVerified(),
        ], $repo->findAll()));
    }

    #[Route('/users/{id}', name: 'api_admin_user_delete', methods: ['DELETE'])]
    public function deleteUser(int $id, UserRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $u = $repo->find($id);
        if (!$u) {
            return $this->json(['erreur' => 'Utilisateur introuvable'], Response::HTTP_NOT_FOUND);
        }

                $profil = $em->getRepository(\App\Entity\Coach::class)->findOneBy(['user' => $u]);
        if ($profil) {
            $em->remove($profil);
        }
        $em->remove($u);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/salles', name: 'api_admin_salles', methods: ['GET'])]
    public function salles(SalleRepository $repo): JsonResponse
    {
        return $this->json(array_map(fn ($s) => [
            'id' => $s->getId(),
            'nom' => $s->getNom(),
            'ville' => $s->getVille(),
        ], $repo->findAll()));
    }

    #[Route('/coachs', name: 'api_admin_coachs', methods: ['GET'])]
    public function coachs(CoachRepository $repo): JsonResponse
    {
        return $this->json(array_map(fn ($c) => [
            'id' => $c->getId(),
            'specialite' => $c->getSpecialite(),
            'nom' => $c->getUser()?->getNom(),
        ], $repo->findAll()));
    }

    #[Route('/reservations', name: 'api_admin_reservations', methods: ['GET'])]
    public function reservations(ReservationRepository $repo): JsonResponse
    {
        return $this->json(array_map(fn ($r) => [
            'id' => $r->getId(),
            'statut' => $r->getStatut(),
            'userId' => $r->getUser()?->getId(),
            'seanceId' => $r->getSeance()?->getId(),
        ], $repo->findAll()));
    }

    #[Route('/avis', name: 'api_admin_avis', methods: ['GET'])]
    public function avis(AvisRepository $repo): JsonResponse
    {
        return $this->json(array_map(fn ($a) => [
            'id' => $a->getId(),
            'note' => $a->getNote(),
            'commentaire' => $a->getCommentaire(),
            'statut' => $a->getStatutModeration(),
        ], $repo->findBy([], ['dateAvis' => 'DESC'])));
    }

    #[Route('/avis/{id}/moderation', name: 'api_admin_avis_moderation', methods: ['PUT'])]
    public function moderer(int $id, Request $request, AvisRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $a = $repo->find($id);
        if (!$a) {
            return $this->json(['erreur' => 'Avis introuvable'], Response::HTTP_NOT_FOUND);
        }
        $d = json_decode($request->getContent(), true) ?? [];
        if (!in_array($d['statut'] ?? '', ['valide', 'masque'], true)) {
            return $this->json(['erreur' => 'Statut attendu : valide ou masque'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $a->setStatutModeration($d['statut']);
        $em->flush();

        return $this->json(['message' => 'Avis mis à jour']);
    }

        #[Route('/users/{id}/verify', name: 'api_admin_user_verify', methods: ['PUT'])]
    public function verifyUser(int $id, UserRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $u = $repo->find($id);
        if (!$u) {
            return $this->json(['erreur' => 'Utilisateur introuvable'], Response::HTTP_NOT_FOUND);
        }
        $u->setIsVerified(true);
        $u->setTokenConfirmation(null);
        $em->flush();

        return $this->json(['message' => 'Compte activé']);
    }
}