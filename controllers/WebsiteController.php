<?php
require_once 'models/website.php';
require_once 'config/cors.php';

class websiteController{
    
    public function getSites(){
        if(isset($_SESSION['id_user'])){
            $cors = new Cors();
            $cors->headers();
            $site = new Website();
            $site->setIdUser($_SESSION['id_user']);
            $sites = $site->getSites();
            $response = array();
            
            $response['status'] = 'success';
            $response['websites'] = $sites;
            echo json_encode($response);
        }else{
            $response = array();
            $response['status'] = 'no funciona';
            echo json_encode($response);
        }

    }

    public function getWebsiteInfo(){
        $id_website = $_POST['id_website'];
        $website = new Website();
        $website->setIdWebsite($id_website);
        $info = $website->getWebsiteInfo();
        return $info;
    }

}