<?php

namespace App\Services;

use App\Repositories\AnnonceRepository;

class AnnonceService
{
    protected $annonceRepository;

    public function __construct(AnnonceRepository $annonceRepository)
    {
        $this->annonceRepository = $annonceRepository;
    }

    public function creerAnnonce(array $data)
    {
        return $this->annonceRepository->create($data);
    }

    public function obtenirAnnonces()
    {
        return $this->annonceRepository->getAll();
    }

    public function obtenirAnnonce($id)
    {
        return $this->annonceRepository->getById($id);
    }

    public function mettreAJourAnnonce($annonce, array $data)
    {
        return $this->annonceRepository->update($annonce, $data);
    }

    public function supprimerAnnonce($annonce)
    {
        $this->annonceRepository->delete($annonce);
    }
}
