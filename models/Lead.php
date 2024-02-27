<?php

class lead{
    private $id_lead;
    private $id_website;
    private $id_user;
    private $name;
    private $message;
    private $phone;
    private $email;
    private $status;
    private $note;

    public function __construct(){
        $this->db = Database::connect();
    }

    // SETTERS

    public function setIdLead($id_lead){
        $this->id_lead = $id_lead;
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

    public function setMessage($message){
        $this->message = $message;
    }

    public function setPhone($phone){
        $this->phone = $phone;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function setLeadNoteContent($note){
        $this->note = $note;
    }

    public function setLeadStatus($status){
        $this->status = $status;
    }

    // GETTERS

    public function getIdLead(){
        return $this->db->real_escape_string($this->id_lead);
    }

    public function getIdWebsite(){
        return $this->db->real_escape_string($this->id_website);
    }

    public function getIdUser(){
        return $this->db->real_escape_string($this->id_user);
    }

    public function getName(){
        return $this->db->real_escape_string($this->name);
    }

    public function getMessage(){
        return $this->db->real_escape_string($this->message);
    }

    public function getPhone(){
        return $this->db->real_escape_string($this->phone);
    }

    private function getEmail(){
        return $this->db->real_escape_string($this->email);
    }

    public function getLeadNoteContent(){
        return $this->db->real_escape_string($this->note);
    }

    public function getLeadStatus(){
        return $this->db->real_escape_string($this->status);
    }

    // ClASS METHODS

    /*
    Read
    */
    public function getLeads(){
        $id_user = $this->getIdUser();
        $id_website = $this->getIdWebsite();
        
        $sql = "
            SELECT wu.id_user, wu.id_website, l.id_lead, l.name, l.phone, l.email, l.content, l.status, l.created_at, l.updated_at
            FROM leads l
            JOIN website_users wu 
            ON wu.id_website = l.id_website
            WHERE wu.id_user = ? AND wu.id_website = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $id_user, $id_website);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $data = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($data as &$lead) {
                $lead['content'] = str_replace("\\r\\n", "\r\n", $lead['content']);
            }
            return $data;
        }else{
            return false;
        }
    }

    public function leadNotes(){
        $data = array();
        $id_lead = $this->getIdLead();
        $sql = "
            SELECT ln.id_lead_note, ln.id_lead, u.name, ln.content, ln.created_at, ln.updated_at
            FROM leads_notes ln
            JOIN users u
            ON u.id_user = ln.id_user
            WHERE ln.id_lead = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id_lead);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            while($response = $result->fetch_assoc()){
                $data[] = $response;
            }
            foreach ($data as &$lead) {
                $content = $lead["content"];
                $content = str_replace("\\n", "\n", $content);
                $lead["content"] = $content;
            }
        }else{
            return 0;
        }
        return $data;    
    }

    public function leadNoteSave(){
        $id_lead = $this->getIdLead();
        $id_user = $this->getIdUser();
        $content = $this->getLeadNoteContent();
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        
        $sql = "INSERT INTO leads_notes (id_lead, id_user, content) VALUES(?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iis', $id_lead, $id_user, $content);
        
        if($stmt->execute()){
            echo 'success';
        }else{
            return 0;
        }
    }

    public function leadStatusUpdate(){
        $status = $this->getLeadStatus();
        $id_lead = $this->getIdLead();

        $sql = "UPDATE leads SET status = ? WHERE id_lead = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $status, $id_lead);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function addLeadManually(){

        $id_website = $this->getIdWebsite();
        $name = $this->getName();
        $phone = $this->getPhone();
        $email = $this->getEmail();
        $content = 'Prospecto creado manualmente';
        $status = $this->getLeadStatus();

        $sql = "
            INSERT INTO leads (id_website, name, phone, email, content, status) VALUES (?, ?, ?, ?, ?, ?);
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isssss', $id_website, $name, $phone, $email, $content, $status);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }
    /*
    Delete
    */
    public function delete(){
        $id_lead = $this->getIdLead();
        $sql = "
            DELETE FROM leads WHERE id_lead = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id_lead);
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

    public function changeName(){
        $id_lead = $this->getIdLead();
        $name = $this->getName();
        $sql = "
            UPDATE leads SET name = ? WHERE id_lead = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $name, $id_lead);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function changePhone(){
        $id_lead = $this->getIdLead();
        $phone = $this->getPhone();
        $sql = "
            UPDATE leads SET phone = ? WHERE id_lead = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $phone, $id_lead);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function changeEmail(){
        $id_lead = $this->getIdLead();
        $email = $this->getEmail();
        $sql = "
            UPDATE leads SET email = ? WHERE id_lead = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $email, $id_lead);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    /*
    Update
    */

    public function update(){
        
        $id = $this->getIdLead();
        $name = $this->getName();
        $phone = $this->getPhone();
        $email = $this->getEmail();
        $status = $this->getLeadStatus();


        $sql = "UPDATE leads SET name=?, phone=?, email=?, status=? WHERE id_lead=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssssi', $name, $phone, $email, $status, $id);
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }
}