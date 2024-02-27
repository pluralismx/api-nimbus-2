<?php

class user{
    
    private $email;
    private $password;
    private $id_user;
    private $verification_code;

    public function __construct(){
        $this->db = Database::connect();
    }

    public function setEmail($email){
        $this->email=$email;
    }

    public function setPassword($password){
        $this->password=$password;
    }

    public function setIdUser($id_user){
        $this->id_user=$id_user;
    }

    public function setVerificationCode($verification_code){
        $this->verification_code=$verification_code;
    }
    
    public function setRole($role){
        $this->role=$role;
    }

    // GETTERS

    public function getEmail(){
        return $this->db->real_escape_string($this->email);
    }

    public function getPassword(){
        return password_hash($this->db->real_escape_string($this->password), PASSWORD_BCRYPT, ['cost'=> 4]);
    }

    public function getIdUser(){
        return $this->db->real_escape_string($this->id_user);
    }
    
    public function getRole(){
        return $this->db->real_escape_string($this->role);
    }

    public function getVerificationCode(){
        return $this->db->real_escape_string($this->verification_code);
    }

    // CLASS METHODS

    public function login(){
        
        $email = $this->getEmail();
        $password = $this->db->real_escape_string($this->password);
        
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows == 1){
            $response = $result->fetch_assoc();
            if(password_verify($password, $response['password'])){
                $this->setIdUser($response['id_user']);
                $this->setRole($response['role']);
                $success = true;
            }else{
                $success = false;
            }
        }else{
            $success = false;
        }
        return $success;
    }

    public function verificationCode(){
        $email = $this->getEmail();

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $response = $stmt->get_result();
        
        if($response->num_rows == 1){
            $verification_code = $this->codeGenerator();
            $this->setVerificationCode($verification_code);
            
            $headers = "From: Pluralis <no-reply@pluralis.com.mx>\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            
            $content = "El código para reestablecer su contraseña es: {$verification_code}. Por favor procure anotar su nueva contraseña y guardarla en un lugar accesible como su cartera o bolso.";
            
            mail($email, 'Solicitud para restablecer su contraseña', $content, $headers);
            
            $sql = "UPDATE users SET p_reset_code = ? WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("is", $verification_code, $email);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
            
        }else{
            return false;
        }
    }

    public function validateVerificationCode(){
        $sql = "SELECT * FROM users WHERE p_reset_code = ? AND email = ?";
        $code = $this->getVerificationCode();
        $email = $this->getEmail();
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $code, $email);

        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows  == 1){
                $response = $result->fetch_assoc();
                $this->setIdUser($response['id_user']);
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function updatePassword(){

        $email = $this->getEmail();
        $new_password = $this->getPassword();
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $new_password, $email);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    private function codeGenerator(){
        $numbers = '0123456789';
        $randomDigits = substr(str_shuffle($numbers), 0, 6);
        return $randomDigits;
    }
}