<?php

require_once __DIR__ . '/../../models/comments.php'; //should require the model file

class CommentsController extends Controller{ //should extend the base Controller
    public function createdComments(){
        global $pdo;

        $modelObject = new Comments($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content'] ?? ''); // 'content' is where coming from input field
                                                    // should trim and check empty content before saveing
            if($content !== ''){
                $modelObject -> create($pdo, $content);
                header('Location: /TravelMate/public/index.php?url=CommentsController/createdComments');
                exit;
            }
        }
        $comments = $modelObject->read($pdo);

        $this->view('createdComments',[
            'comments' => $comments
        ]);
    }
}