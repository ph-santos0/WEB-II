<?php
session_start();
require_once 'connection.php';

function validaCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);
    if (strlen($cpf) != 11) return false;
    if (preg_match('/(\d)\1{10}/', $cpf)) return false;
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    return true;
}

function validaCNPJ($cnpj) {
    $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
    if (strlen($cnpj) != 14) return false;
    if (preg_match('/(\d)\1{13}/', $cnpj)) return false;
    for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
        $soma += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }
    $resto = $soma % 11;
    if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) return false;
    for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
        $soma += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }
    $resto = $soma % 11;
    return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $cpf_cnpj = $_POST['cpf_cnpj'] ?? '';

    $_SESSION['form_data'] = [
        'nome' => $nome,
        'tipo' => $tipo,
        'cpf_cnpj' => $cpf_cnpj
    ];

    try {
        if (empty($nome) || empty($tipo) || (strtolower($tipo) !== "fisica" && strtolower($tipo) !== "juridica") || empty($cpf_cnpj)) {
            header("Location: index.php?error=Por+favor,+preencha+todos+os+campos.");
            exit();
        }

        if (strlen(trim($nome)) < 3) {
            header("Location: index.php?error=O+nome+deve+ter+pelo+menos+3+caracteres.");
            exit();
        }

        if (strtolower($tipo) === "fisica") {
            if (!validaCPF($cpf_cnpj)) {
                header("Location: index.php?error=CPF+inválido.");
                exit();
            }
            $tipo_db = "F";
        } else {
            if (!validaCNPJ($cpf_cnpj)) {
                header("Location: index.php?error=CNPJ+inválido.");
                exit();
            }
            $tipo_db = "J";
        }

        $conn = new Connection("localhost", "exercicio", "root", "");
        $pdoConn = $conn->getConnection();

        $sql = "INSERT INTO pessoas (nome, tipo, cpf_cnpj) VALUES (:nome, :tipo, :cpf_cnpj)";
        $stmt = $pdoConn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':tipo', $tipo_db);
        $stmt->bindParam(':cpf_cnpj', $cpf_cnpj);
        $stmt->execute();

        unset($_SESSION['form_data']);

        header("Location: index.php?success=Dados+inseridos+com+sucesso!");
        exit();
    } catch (PDOException $e) {
        header("Location: index.php?error=Erro+no+banco+de+dados.+Tente+novamente.");
        exit();
    }
}
?>