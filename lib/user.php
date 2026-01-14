<?php

function addUser(PDO $pdo, string $first_name, string $last_name, string $email, string $password, $role = "user"): bool {
    $query = $pdo->prepare("
        INSERT INTO users (first_name, last_name, email, password, role)
        VALUES (:first_name, :last_name, :email, :password, :role)
    ");

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $query->bindValue(':first_name', $first_name);
    $query->bindValue(':last_name', $last_name);
    $query->bindValue(':email', $email);
    $query->bindValue(':password', $hash);
    $query->bindValue(':role', $role);

    return $query->execute();
    /*
        @todo faire la requête d'insertion d'utilisateur et retourner $query->execute();
        Attention faire une requête préparer et à binder les paramètres
    */
}

function verifyUserLoginPassword(PDO $pdo, string $email, string $password) {
    $query = $pdo->prepare("
        SELECT * FROM users
        WHERE email = :email
        LIMIT 1
    ");

    $query->bindValue(':email', $email);
    $query->execute();
    /*
        @todo faire une requête qui récupère l'utilisateur par email et stocker le résultat dans user
        Attention faire une requête préparer et à binder les paramètres
    */
    $user = $query->fetch(PDO::FETCH_ASSOC);
     if ($user && password_verify($password, $user['password'])) {
        return $user;
    }

    return false;

 

    /*
        @todo Si on a un utilisateur et que le mot de passe correspond (voir fonction  native password_verify)
              alors on retourne $user
              sinon on retourne false
    */


}
