<?php

namespace App\Services;

use App\Repositories\CandidatureRepository;
use App\Notifications\CandidatureNotification;
use App\Models\User;

class CandidatureService
{
    protected $candidatureRepository;

    public function __construct(CandidatureRepository $candidatureRepository)
    {
        $this->candidatureRepository = $candidatureRepository;
    }

    public function postuler(array $data)
{
    $candidature = $this->candidatureRepository->create($data);

    // Notifier le recruteur
    $recruteur = $candidature->annonce->user;
    $recruteur->notify(new CandidatureNotification(
        "Nouvelle candidature pour votre annonce.", 
        "En attente"
    ));

    return $candidature;
}

    public function obtenirCandidatures()
    {
        return $this->candidatureRepository->getAll();
    }

    public function obtenirCandidature($id)
    {
        return $this->candidatureRepository->getById($id);
    }

    public function obtenirCandidaturesParAnnonce($annonceId)
    {
        return $this->candidatureRepository->getByAnnonce($annonceId);
    }

    public function mettreAJourCandidature($candidature, array $data)
    {
        $this->candidatureRepository->update($candidature, $data);
    
        // Notifier le candidat du changement de statut
        $candidature->candidat->notify(new CandidatureNotification(
            "Votre candidature a été mise à jour : " . $data['statut'], 
            $data['statut']
        ));
    
        return $candidature;
    }

    public function supprimerCandidature($candidature)
    {
        $this->candidatureRepository->delete($candidature);
    }
}
