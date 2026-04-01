<?php

require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $cpf_cnpj = $_POST['cpf_cnpj'] ?? '';

    try {
        if (
            empty($nome) || empty($tipo) ||
            (
                strtolower($tipo) !== "fisica" && strtolower($tipo) !== "juridica"
            )
            || empty($cpf_cnpj)
        ) {
            header("Location: index.php?error=Por+favor,+preencha+todos+os+campos.");
            exit();
        }

        switch (strtolower($tipo)) {
            case "fisica": {
                    $tipo = "F";
                    break;
                }
            case "juridica": {
                    $tipo = "J";
                    break;
                }
        }

        $conn = new Connection("localhost", "exercicio", "root", "root");
        $pdoConn = $conn->getConnection();

        $sql = "INSERT INTO pessoas (nome, tipo, cpf_cnpj) VALUES (:nome, :tipo, :cpf_cnpj)";
        $stmt = $pdoConn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':cpf_cnpj', $cpf_cnpj);
        $stmt->execute();

        header("Location: index.php?success=Dados inseridos com sucesso!");
        exit();
    } catch (PDOException $e) {
        header("Location: index.php?error=Tente novamente.");
        exit();
    }
}
