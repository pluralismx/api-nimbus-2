<?php

class website{

    private $id_website;
    private $id_user;
    private $name;
    private $url;

    public function __construct(){
        $this->db = Database::connect();
    }

    public function setIdWebsite($id_website){
        $this->id_website = $id_website;
    }
    public function setIdUser($id_user){
        $this->id_user = $id_user;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setUrl($url){
        $this->url = $url;
    }

    // GETTERS

    public function getIdWebsite(){
        return $this->db->real_escape_string($this->id_website);
    }

    public function getIdUser(){
        return $this->db->real_escape_string($this->id_user);
    }

    public function getName(){
        return $this->db->real_escape_string($this->name);
    }

    public function getUrl(){
        return $this->db->real_escape_string($this->url);
    }

    // CLASS METHODS

    public function getSites(){

        $id_user = $this->getIdUser();
        $sql = "
            SELECT wu.id_user, wu.id_website, w.name, w.url, w.email, w.email_password
            FROM website_users wu
            JOIN websites w
            ON wu.id_website = w.id_website
            WHERE wu.id_user = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            while($response = $result->fetch_assoc()){
                $sites[] = $response;
            }
            return $sites;
        }else{
            return 'failed';
        }
    }

    // public function getWebsiteInfo(){
    //     $id_website = $this->getIdWebsite();
    //     $query = "SELECT * FROM websites WHERE id_website = ?";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bind_param('i', $id_website);
    //     if($stmt->execute()){
    //         $result = $stmt->get_result();
    //         $data = $result->fetch_assoc();
    //         return $data;
    //     }else{
    //         echo 'error';
    //     }
    // }
}


