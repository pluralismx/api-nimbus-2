<?php

class Note{
    
    private $id;
    private $id_user;
    private $id_website;
    private $title;
    private $content;

    public function __construct(){
        $this->db = Database::connect();
    }

    public function setIdUser($id_user){
        $this->id_user = $id_user;
    }

    public function setIdWebsite($id_website){
        $this->id_website = $id_website;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function setContent($content){
        $this->content = $content;
    }

    // GETTERS

    private function getIdUser(){
        return $this->db->real_escape_string($this->id_user);
    }

    private function getIdWebsite(){
        return $this->db->real_escape_string($this->id_website);
    }

    private function getId(){
        return $this->db->real_escape_string($this->id);
    }

    private function getTitle(){
        return $this->db->real_escape_string($this->title);
    }

    private function getContent(){
        return $this->db->real_escape_string($this->content);
    }

    public function save(){
        $sql = "INSERT INTO notes (id_user, id_website, title, content) VALUES(?, ?, ?, ?)";
        $user_id = $this->getIdUser();
        $website_id = $this->getIdWebsite();
        $title = $this->getTitle();
        $content = $this->getContent();
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iiss', $user_id, $website_id, $title, $content);
        
        $result = false;
        if($stmt->execute()){
            $result = true;
        }
        return $result;
    }

    public function getNotes() {
        $sql = "SELECT * FROM notes WHERE id_user = ? AND id_website = ?  ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $user_id = $this->getIdUser();
        $website_id = $this->getIdWebsite();
        $stmt->bind_param('si', $user_id, $website_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
    
            foreach ($data as &$note){
                $note["content"] = str_replace("\\n", "\n", $note["content"]);
                $note["content"] = str_replace("\\r", "\r", $note["content"]);
            }
            return $data;
        }else{
            return false;
        }
    }

    public function update(){
        $sql = "UPDATE notes SET title = ?, content = ? WHERE id_note = ?";
        $stmt = $this->db->prepare($sql);
        $title = $this->getTitle();
        $content = $this->getContent();
        $id = $this->getId();
        $stmt->bind_param('ssi', $title, $content, $id);
        $result = false;

        if($stmt->execute()){
            $result = true;
        }
        return $result;
    }

    public function delete(){
        $sql = "DELETE FROM notes WHERE id_note = ?";
        $stmt = $this->db->prepare($sql);
        $id = $this->getId();
        $stmt->bind_param('i', $id);
        $result = false;

        if($stmt->execute()){
            $result = true;
        }
        return $result;
    }




}