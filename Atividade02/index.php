<?php
session_start();

$formData = $_SESSION['form_data'] ?? ['nome' => '', 'tipo' => '', 'cpf_cnpj' => ''];

if (!isset($_GET['error'])) {
    $formData = ['nome' => '', 'tipo' => '', 'cpf_cnpj' => ''];
    unset($_SESSION['form_data']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8" />

	<title>Integração</title>

	<style>
		table {
			border-collapse: collapse;
			width: 70%;
			margin-top: 10px;
		}

		table, th, td {
			border: 1px solid black;
		}

		th, td {
			padding: 8px;
			text-align: left;
		}

		th {
			background-color: #ddd;
		}
	</style>

	<script src="script.js"></script>

	<link rel="stylesheet" href="styles.css">

</head>

<body>

	<?php
	require_once 'connection.php';

	$conn = new Connection("localhost", "exercicio", "root", "");
	$pdoConn = $conn->getConnection();
	?>

	<h1>Exercício de Integração (frontend, backend e banco de dados)</h1>

	<form id="f" method="post" action="insert.php">

		<label for="nome"> Nome: </label>
		<input type="text" id="nome" name="nome" size="40" maxlength="40" value="<?php echo htmlspecialchars($formData['nome']); ?>" />
		<div><b class="error" id="nomeError"></b></div>

		<br />

		Tipo de Pessoa:

		<input type="radio" id="pfisica" name="tipo" value="fisica" <?php echo ($formData['tipo'] === 'fisica') ? 'checked' : ''; ?> />
		<label for="pfisica"> Fisica </label>

		<input type="radio" id="pjuridica" name="tipo" value="juridica" <?php echo ($formData['tipo'] === 'juridica') ? 'checked' : ''; ?> />
		<label for="pjuridica"> Jurídica </label>

		<div><b class="error" id="tipoError"></b></div>

		<br />

		<label for="cpf_cnpj"> CPF/CNPJ: </label>
		<input type="text" id="cpf_cnpj" name="cpf_cnpj" value="<?php echo htmlspecialchars($formData['cpf_cnpj']); ?>" />
		<div><b class="error" id="cpf_cnpjError"></b></div>

		<br />

		<input type="submit" id="enviar" value="   Enviar   " />

		<br />

		<input type="reset" id="limpar" value="   Limpar   " />

		<br />

		<?php if (isset($_GET['error'])): ?>
			<p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
		<?php endif; ?>

		<?php if (isset($_GET['success'])): ?>
			<p style="color: green;"><?php echo htmlspecialchars($_GET['success']); ?></p>
		<?php endif; ?>

	</form>

	<h2>Dados Cadastrados</h2>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Nome</th>
				<th>Tipo de Pessoa</th>
				<th>CPF/CNPJ</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$sql = "SELECT * FROM pessoas;";
			$result = $pdoConn->query($sql);

			if ($result->rowCount() > 0) {
				while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
					echo "<tr>";
					echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
					echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";
					echo "<td>" . ($row["tipo"] == "F" ? "Física" : "Jurídica") . "</td>";
					echo "<td>" . htmlspecialchars($row["cpf_cnpj"]) . "</td>";
					echo "</tr>";
				}
			} else {
				echo "<tr><td colspan='4'>Nenhum registo encontrado.</td></tr>";
			}
			?>
		</tbody>
	</table>

</body>
</html>