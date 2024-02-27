<?php
require_once 'config/cors.php';
require_once 'models/Note.php';
require_once 'models/Lead.php';

    class dashboardController {
        public function dashboardData(){

            $cors = new Cors();
            $cors->headers();
            
            $note = new Note();
            $note->setIdUser($_SESSION['id_user']);
            // $note->setIdUser('14');
            $note->setIdWebsite($_POST['id_website']);
            $notes = $note->getNotes();


            $lead = new Lead();
            $lead->setIdUser($_SESSION['id_user']);
            // $lead->setIdUser('14');
            $lead->setIdWebsite($_POST['id_website']);
            $leads = $lead->getLeads();
            
            $dashboard = array(
                'status' => 'succes',
                'notes' => $notes,
                'leads' => $leads
            );

            echo json_encode($dashboard);
   
        }
    }

?>