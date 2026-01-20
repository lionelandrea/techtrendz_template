<?php

function getArticleById(PDO $pdo, int $id):array|bool
{
    $query = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function getArticles(PDO $pdo, ?int $limit = null, ?int $page = null):array|bool {
    $sql = "SELECT * FROM articles ORDER BY id DESC";

    if ($limit !== null && $page !== null) {
        $offset = ($page - 1) * $limit;
        $sql .= " LIMIT :limit OFFSET :offset";
        $query = $pdo->prepare($sql);
        $query->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    } else {
        $query = $pdo->prepare($sql);
    }

    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);

    /*
        @todo faire la requête de récupération des articles
        La requête sera différente selon les paramètres passés, commencer déjà juste avec la base en ignrorant les autre params
    */
    $sql = "SELECT * FROM articles ORDER BY id DESC";

    if ($limit !== null && $page !== null) {
        $offset = ($page - 1) * $limit;
        $sql .= " LIMIT :limit OFFSET :offset";
        $query = $pdo->prepare($sql);
        $query->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    } else {
        $query = $pdo->prepare($sql);
    }


    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function getTotalArticles(PDO $pdo):int|bool {
    $query = $pdo->prepare("SELECT COUNT(*) AS total FROM articles");
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result ? (int)$result['total'] : false;
    /*
        @todo récupérer le nombre total d'article (avec COUNT)
    */
    $query = $pdo->prepare("SELECT COUNT(*) AS total FROM articles");
    $query->execute();

    //$result = $query->fetch(PDO::FETCH_ASSOC);
    //return $result['total'];


     $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result && isset($result['total'])) {
        return (int)$result['total'];
    }
    return false;
}

function saveArticle(PDO $pdo, string $title, string $content, ?string $image, int $category_id, ?int $id = null):bool {

    if ($id === null) {
         $query = $pdo->prepare("
            INSERT INTO articles (title, content, image, category_id, created_at)
            VALUES (:title, :content, :image, :category_id, NOW())
        ");
        /*
            @todo si id est null, alors on fait une requête d'insection
        */
        //$query = ...
        $query = $pdo->prepare("
            INSERT INTO articles (title, content, image, category_id)
            VALUES (:title, :content, :image, :category_id)
        ");
    } else {
        $query = $pdo->prepare("
            UPDATE articles 
            SET title = :title, content = :content, image = :image, category_id = :category_id, updated_at = NOW()
            WHERE id = :id
        ");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        /*
            @todo sinon, on fait un update
        */
        
        //$query = ...

         $query = $pdo->prepare("
            UPDATE articles
            SET title = :title, content = :content, image = :image, category_id = :category_id
            WHERE id = :id
        ");
        
        //$query->bindValue(':id', $id, $pdo::PARAM_INT);

         $query->bindValue(':id', $id, PDO::PARAM_INT);
        
    }
    $query->bindValue(':title', $title);
    $query->bindValue(':content', $content);
    $query->bindValue(':image', $image);
    $query->bindValue(':category_id', $category_id, PDO::PARAM_INT);

    return $query->execute();  


    // @todo on bind toutes les valeurs communes
   


    //return $query->execute();  
}

function deleteArticle(PDO $pdo, int $id):bool {
    $query = $pdo->prepare("DELETE FROM articles WHERE id = :id");
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();

    return $query->rowCount() > 0;
    
    /*
        @todo Faire la requête de suppression
    */

    
    $query->execute();
    if ($query->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
    
    
}