<?php
require_once 'models/user.php';
require_once 'config/cors.php';


class userController{

    public function login(){
        if(isset($_POST)){
            $cors = new Cors();
            $cors->headers();
            $response = array();
            $user =  new User();
            $user->setEmail($_POST['email']);
            $user->setPassword($_POST['password']);
            $login = $user->login();

            if($login){
                $_SESSION['email'] = $user->getEmail();
                $_SESSION['id_user'] = $user->getIdUser();
                $_SESSION['role'] = $user->getRole();
                
                $response['status'] = 'success';
                $response['id_user'] = $_SESSION['id_user'];

                echo json_encode($response);
            }else{
                $response['status'] = 'error';
                echo json_encode($response);
            }
        }else{
            echo 'no se enviaron los datos';
        }
    }

    public function logout(){
        if(isset($_POST['log_out'])){
            $cors = new Cors();
            $cors->headers();
            $_SESSION = array();
            session_destroy();
            $response = array();
            $response['status'] = 'success';
            echo json_encode($response);
        }

    }

    public function resetPassword(){
        if(isset($_POST)){
            if(isset($_POST['email'])){
                $_SESSION['email'] = $_POST['email'];
                $reset = new User();
                $reset->setEmail($_SESSION['email']);
                $response = $reset->verificationCode();
                if($response == true){
                    echo 'true';
                }else{
                    echo 'false';
                }
            }else if(isset($_POST['code'])){
                $reset = new User();
                $reset->setVerificationCode($_POST['code']);
                $reset->setEmail($_SESSION['email']);
                $response = $reset->validateVerificationCode();
                if($response == true){
                    $_SESSION['reset'] = true;
                    $_SESSION['id_user'] = $reset->getIdUser();
                    header('Location: '.base_url.'user/passwordResetForm');
                }else{
                    echo 'failed';
                    die();
                    session_destroy();
                    header('Location: '.base_url.'index.php');
                }
            }else if(isset($_POST['update']) && isset($_POST['password'])){
                $update = new User();
                $update->setPassword($_POST['password']);
                $update->setEmail($_SESSION['email']);
                
                $response = $update->updatePassword();
                if($update == true){
                    echo 'Cambios gurdados';
                    die();
                }else{
                    echo ":(";
                }
            }
        }
    }

    public function passwordResetForm(){
        include_once 'views/user/reset_password.php';
        include_once 'views/layout/footer.php';
    }
}