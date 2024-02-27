<?php
require_once 'models/note.php';
require_once 'config/cors.php';

class NoteController{

    public function save(){
        if(isset($_POST)){
            $cors = new Cors();
            $cors->headers();
            $response = array();
            $note = new Note();
            $note->setIdUser($_SESSION['id_user']);
            $note->setIdWebsite($_POST['id_website']);
            $note->setTitle($_POST['note_title']);
            $note->setContent($_POST['note_content']); 
            $save = $note->save();
                
            if($save){
                $response = array();
                $response['status'] = 'succes';
                echo json_encode($response);
            }else{
                $response = array();
                $response['status'] = 'failed';
                echo json_encode($response);
            }
        }
    }

    public function getNotes(){
        $cors = new Cors();
        $cors->headers();
        $note = new Note();
        $note->setIdUser($_SESSION['id_user']);
        // $note->setIdUser('14');
        $note->setIdWebsite($_POST['id_website']);
        $data = $note->getNotes();
        
        if($data) {
            echo json_encode($data);
        } else {
            return false;
        }
    }

    public function update(){
        if(isset($_POST)){
            $note = new Note();
            $note->setId($_POST['id_note']);
            $note->setTitle($_POST['title']);
            $note->setContent($_POST['content']); 
            $update = $note->update();

            if($update){
                echo 'success';
            }else{
                echo 'failed';
            }
        }
    }

    public function delete(){
        if(isset($_POST)){
            $note = new Note();
            $note->setId($_POST['id_note']);
            $delete = $note->delete();

            if($delete){
                echo 'success';
            }else{
                echo 'failed';
            }
        }
    }


}