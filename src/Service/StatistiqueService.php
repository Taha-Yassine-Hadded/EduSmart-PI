<?php

namespace App\Service;

use App\Repository\CandidatureRepository;
use App\Repository\OffreRepository;

class StatistiqueService
{
    private $candidatureRepository;
    private $offreRepository;

    public function __construct(CandidatureRepository $candidatureRepository, OffreRepository $offreRepository)
    {
        $this->candidatureRepository = $candidatureRepository;
        $this->offreRepository = $offreRepository;
    }

    public function getStatistiquesCandidaturesParOffre()
    {
        $queryBuilder = $this->candidatureRepository->createQueryBuilder('c');
        $queryBuilder->select('o.id as offre_id, o.titre as offre_titre, COUNT(c.id) as nb_candidatures')
                     ->leftJoin('c.offre', 'o')
                     ->groupBy('o.id');

        $result = $queryBuilder->getQuery()->getResult();

        $statistiques = [];

        foreach ($result as $row) {
            $offreId = $row['offre_id'];
            $offreTitre = $row['offre_titre'];
            $nbCandidatures = $row['nb_candidatures'];

            $offre = $this->offreRepository->find($offreId);
            $nbrOffre = $offre->getNbr();

            $pourcentage = $nbrOffre > 0 ? ($nbCandidatures / $nbrOffre) * 100 : 0;

            $statistiques[] = [
                'offre' => $offreTitre,
                'nbCandidatures' => $nbCandidatures,
                'pourcentage' => $pourcentage,
            ];
        }

        return $statistiques;
    }
}



