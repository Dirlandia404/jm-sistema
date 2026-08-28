<?php

declare(strict_types=1);    

namespace App\Controllers;  

use App\Models\Service;

//controlaas ações relacionadas aos serviçoes
class ServiceController{
   private Service $serviceModel;
   
   //recebe o Model de serviços
   public function __construct(Service $serviceModel){
       $this->serviceModel = $serviceModel;
   }
   public function create(): void{
    //verifica se o usuario esta logado
    if(!isset($_SESSION["user"])){
        header("Location: /login");
        exit;
    }

    //carrega a tela de cadastro de serviço
    require __DIR__ . '/../Views/services/create.php';
   }
       //processa o cadastro de serviço
    public function store(): void{
        //verifica usuario logado
        if(!isset($_SESSION["user"])){
            header("Location: /login");
            exit;
        }
        //recebe os dados do formulario
        $description = trim((string) ($_POST["description"] ?? ''));
        $price = trim((string) ($_POST["price"] ?? ''));
        $price = str_replace(',', '.', $price);

        //valida campos obrigatorios
        if($description === '' || !is_numeric($price) || (float) $price <= 0){
            $_SESSION['service_error'] = 'Não foi possivel cadastrar serviço';
            header('Location: index.php?route=dashboard');
            exit;
        }
        $userId = (int) $_SESSION['user']['id_user'];
        try{
            $create = $this->serviceModel->create($description, $price, $userId);
        }
        catch(\PDOException $e){
            error_log($e->getMessage());
            $create = false;
        }
        if($create){
            $_SESSION['service_success'] = 'Serviço cadastrado com sucesso';
        }else{
            $_SESSION['service_error'] = 'Não foi possivel cadastrar o serviço';
        }
        header('Location: index.php?route=dashboard');
        exit;
    }
}

