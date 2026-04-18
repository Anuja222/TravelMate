<?php

class Comments {
    public function create($pdo, $content){
        $stmt = $pdo->prepare("INSERT INTO comments (content) values (?)");
        $stmt ->execute([$content]);
    }

    public function read($pdo){
        $stmt = $pdo->query("SELECT * FROM comments");
        return $stmt->fetchAll();
    }
}
