<?php

namespace Kineo;

use PDO;

final class UtilisateurRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Renvoie l'utilisateur ['id', 'login', 'nom'] si le login et le mot de
     * passe sont corrects, null sinon.
     */
    public function authentifier(string $login, string $mdp): ?array
    {
        $sql = "SELECT id, login, nom, motDePasse FROM Utilisateur WHERE login = :login";
        $req = $this->pdo->prepare($sql);
        $req->bindValue(':login',$login,PDO::PARAM_STR);
        $req->execute();
        $ligne = $req->fetch(PDO::FETCH_ASSOC);

        if ($ligne === false || !password_verify($mdp, $ligne['motDePasse'])) {
            return null;
        }
        unset($ligne['motDePasse']);
        return $ligne;
    }
}
