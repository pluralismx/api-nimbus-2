<?php
require_once 'models/lead.php';
require_once 'models/note.php';
require_once 'config/cors.php';

class leadController{

    public function getLeads(){
        $cors = new Cors();
        $cors->headers();
        if(isset($_POST)){
            $lead = new Lead();
            $lead->setIdUser($_SESSION['id_user']);
            $lead->setIdWebsite($_POST['id_website']);
            $data = $lead->getLeads();
        
            if($data){
                echo json_encode($data);
            } else {
                return false;
            }
    
        }
    }

    public function leadNotes(){
        if(isset($_SESSION['id_user'])){
            $lead = new Lead();
            $lead->setIdLead($_POST['id_lead']);
            $data = $lead->leadNotes();
            $lead_notes = json_encode($data);
        }
        echo $lead_notes;
    }

    public function leadNoteSave(){
        $lead = new Lead();
        $lead->setIdUser($_SESSION['id_user']);
        $lead->setIdLead($_POST['id_lead']);
        $lead->setLeadNoteContent($_POST['content']);
        $lead->leadNoteSave();
    }

    public function leadStatusUpdate(){
        $lead =  new Lead();
        $lead->setIdLead($_POST['id_lead']);
        $lead->setLeadStatus($_POST['status']);
        $response = $lead->leadStatusUpdate();
        if($response){
            echo 'success';
        }else{
            echo 'failed';
        }
    }

    public function addleadManually(){
        $lead = new Lead();
        $lead->setIdWebsite($_POST['id_website']);
        $lead->setName($_POST['name']);
        $lead->setPhone($_POST['phone']);
        $lead->setEmail($_POST['email']);
        $lead->setLeadStatus($_POST['status']);
        $response = $lead->addLeadManually();
        if($response == true){
            echo 'success';
        }else{
            echo 'failed';
        }
    }

    public function deleteLead(){
        if(isset($_POST['id_lead']) && $_SESSION['role'] === 'MASTER'){
            $id = $_POST['id_lead'];
            $lead = new Lead();
            $lead->setIdLead($id);
            $response = $lead->delete();
            if($response){
                echo 'succes';
            }else {
                echo 'failed';
            }
        }else if($_SESSION['role'] !== 'MASTER'){
            echo 'restricted';
        }

    }

    public function updateLead(){
        $cors = new Cors();
        $cors->headers();
        
        if(isset($_POST)){

            $id = $_POST['id_lead'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $status = $_POST['status'];

            $lead = new Lead();
            $lead->setIdLead($id);
            $lead->setName($name);
            $lead->setPhone($phone);
            $lead->setEmail($email);
            $lead->setLeadStatus($status);
            $update = $lead->update();

            if($update){
                echo 'succes';
            }else{
                echo 'failed';
            }
        }
    }
}