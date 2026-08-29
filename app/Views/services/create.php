<?php

declare(strict_types=1);    

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Cadastro Serviço</title>
</head>
<body>
    <h1>Cadastrar novo Serviço</h1>

    <form action="index.php?route=service-store" method="POST">
        <label for="description">Descriçao:</label>
        <input 
            type="text" 
            name="description" 
            id="description" 
            required
        >
        <br>
        <label for="price">Preço:</label>
        <input
            type="number"
            id="price"
            name="price"
            min="0.01"
            step="0.01"
            required
        >
        <br>
        <button type="submit">Cadastrar</button>
        <a href="index.php?route=dashboard">Voltar</a>
    </form>
</body>
</html>