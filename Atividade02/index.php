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

		table,
		th,
		td {
			border: 1px solid black;
		}

		th,
		td {
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

	$conn = new Connection("localhost", "exercicio", "root", "root");
	$pdoConn = $conn->getConnection();

	?>

	<h1>Exercício de Integração (frontend, backend e banco de dados)</h1>

	<form id="f" method="post" action="insert.php">

		<label for="nome"> Nome: </label>
		<input type="text" id="nome" name="nome" size="40" maxlength="40" />
		<div><b class="error" id="nomeError"></b></div>

		<br />

		Tipo de Pessoa:

		<input type="radio" id="pfisica" name="tipo" value="fisica" />
		<label for="pfisica"> Fisica </label>

		<input type="radio" id="pjuridica" name="tipo" value="juridica" />
		<label for="pjuridica"> Jurídica </label>

		<div><b class="error" id="tipoError"></b></div>

		<br />

		<label for="cpf_cnpj"> CPF/CNPJ: </label>
		<input type="text" id="cpf_cnpj" name="cpf_cnpj" />
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

	<!-- NOVA TABELA -->
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
					echo "<td>" . $row["id"] . "</td>";
					echo "<td>" . $row["nome"] . "</td>";
					echo "<td>" . ($row["tipo"] == "F" ? "Física" : "Juridica") . "</td>";
					echo "<td>" . $row["cpf_cnpj"] . "</td>";
					echo "</tr>";
				}
			} else {
				echo "Nenhum registro encontrado.";
			}

			?>
		</tbody>
	</table>


</body>

</html>