<?php

namespace Kineo;

use PDO;

final class PatientRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /** Patients dont le nom contient $texte, triés par nom. */
    public function rechercherParNom(string $texte): array
    {
        $sql = "SELECT id, nom, prenom, ville FROM Patient WHERE nom LIKE :motif ORDER BY nom";
        $req -> bindValue(':motif', '%'.$texte.'%',PDO::PARAM_STR);
        $req -> execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tous les patients, triés selon la colonne $tri ('nom', 'prenom', 'ville'
     * ou 'dateNaissance') et le sens $sens ('asc' ou 'desc'). Une colonne ou
     * un sens inconnu donne le tri par défaut : nom, croissant.
     */
    public function lister(string $tri = 'nom', string $sens = 'asc'): array
    {
        const COLONNES_TRI = ['nom', 'prenom', 'ville', 'dateNaissance'];
        // on vérifie que $tri est dans la liste blanche
        $col = in_array($tri, COLONNES_TRI, true) ? $tri : 'nom';
        $sens = strtolower($sens)==="desc" ? 'DESC' : 'ASC'; 
        $sql = "SELECT id, nom, prenom, ville, dateNaissance FROM Patient ORDER BY $col $sens";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
