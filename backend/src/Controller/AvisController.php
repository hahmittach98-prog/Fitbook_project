<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use App\Repository\CoachRepository;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/avis')]
class AvisController extends AbstractController
{
    private function toArray(Avis $a): array
    {
        return [
            'id' => $a->getId(),
            'note' => $a->getNote(),
            'commentaire' => $a->getCommentaire(),
            'date' => $a->getDateAvis()?->format('Y-m-d H:i'),
            'auteur' => $a->getUser()?->getPrenom(),
            'coachId' => $a->getCoach()?->getId(),
            'salleId' => $a->getSalle()?->getId(),
        ];
    }

    #[Route('', name: 'api_avis_index', methods: ['GET'])]
    public function index(Request $request, AvisRepository $repo): JsonResponse
    {
        $criteres = ['statutModeration' => 'valide'];
        if ($request->query->get('coachId')) {
            $criteres['coach'] = (int) $request->query->get('coachId');
        }
        if ($request->query->get('salleId')) {
            $criteres['salle'] = (int) $request->query->get('salleId');
        }

        return $this->json(array_map(
            fn ($a) => $this->toArray($a),
            $repo->findBy($criteres, ['dateAvis' => 'DESC'])
        ));
    }

    #[Route('', name: 'api_avis_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        CoachRepository $coachs,
        SalleRepository $salles
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_SPORTIF');
        $d = json_decode($request->getContent(), true) ?? [];

        $note = (int) ($d['note'] ?? 0);
        if ($note < 1 || $note > 5) {
            return $this->json(['erreur' => 'La note doit être comprise entre 1 et 5'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if (empty($d['coachId']) === empty($d['salleId'])) {
            return $this->json(['erreur' => 'Indiquer un coachId OU un salleId'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $a = new Avis();
        $a->setUser($this->getUser());
        $a->setNote($note);
        $a->setCommentaire($d['commentaire'] ?? null);
        $a->setDateAvis(new \DateTimeImmutable());
        $a->setStatutModeration('valide');

        if (!empty($d['coachId'])) {
            $coach = $coachs->find($d['coachId']);
            if (!$coach) {
                return $this->json(['erreur' => 'Coach introuvable'], Response::HTTP_NOT_FOUND);
            }
            $a->setCoach($coach);
        } else {
            $salle = $salles->find($d['salleId']);
            if (!$salle) {
                return $this->json(['erreur' => 'Salle introuvable'], Response::HTTP_NOT_FOUND);
            }
            $a->setSalle($salle);
        }

        $em->persist($a);
        $em->flush();

        return $this->json($this->toArray($a), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_avis_delete', methods: ['DELETE'])]
    public function delete(Avis $avis, EntityManagerInterface $em): JsonResponse
    {
        if ($avis->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $em->remove($avis);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}