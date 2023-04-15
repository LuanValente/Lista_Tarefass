<!DOCTYPE html>
<html>
<head>
    <title>Lista de Tarefas</title>
</head>
<body>
    <h1>Lista de Tarefas</h1>
    <link rel="stylesheet" href="style.css">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <br>
        <label for="tarefa">Tarefa:</label>
        <input type="text" id="tarefa" name="tarefa" required>
        <br>
        <label for="prioridade">Prioridade:</label>
        <select id="prioridade" name="prioridade" required>
            <option value="alta">Alta</option>
            <option value="média">Média</option>
            <option value="baixa">Baixa</option>
        </select>
        <br>
        <input type="submit" value="Adicionar Tarefa">
    </form>

    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "root", "", "list");

    // Verifica se a conexão foi estabelecida com sucesso
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Recebe os dados do formulário
    $nome = $_POST["nome"];
    $tarefa = $_POST["tarefa"];
    $prioridade = $_POST["prioridade"];

    // Insere a tarefa no banco de dados
    $sql = "INSERT INTO tarefas (nome, tarefa, prioridade) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $tarefa, $prioridade);
    if ($stmt->execute()) {
        echo "Tarefa adicionada com sucesso!";
    } else {
        echo "Erro ao adicionar tarefa: " . $stmt->error;
    }

    // Fecha a conexão com o banco de dados
    $stmt->close();
    $conn->close();
}
?>
 <h2>Tarefas cadastradas:</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Tarefa</th>
        <th>Prioridade</th>
        <th>Status</th>
        <th>Ação</th>
    </tr>
    <?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "list");

// Verifica se a conexão foi estabelecida com sucesso
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se foi enviado um formulário de atualização de status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && isset($_POST["status"])) {
    $id = $_POST["id"];
    $status = $_POST["status"];
 // Atualiza o status no banco de dados
 $sql = "UPDATE tarefas SET status = ? WHERE id = ?";
 $stmt = $conn->prepare($sql);
 $stmt->bind_param("si", $status, $id);
 if ($stmt->execute()) {
     echo "Status da tarefa atualizado com sucesso!";
 } else {
     echo "Erro ao atualizar o status da tarefa: " . $stmt->error;
 }

 // Fecha a conexão com o banco de dados
 $stmt->close();
 $conn->close();
}
?>
   <?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "list");

// Verifica se a conexão foi estabelecida com sucesso
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se foi enviado um formulário de atualização de status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && isset($_POST["status"])) {
    $id = $_POST["id"];
    $status = $_POST["status"];

    // Atualiza o status no banco de dados
    $sql = "UPDATE tarefas SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    // Verifica se a atualização foi realizada com sucesso
    if ($stmt->affected_rows > 0) {
        echo "Status atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar status: " . $stmt->error;
    }

    // Fecha o statement
    $stmt->close();
}

// Consulta as tarefas cadastradas no banco de dados
$sql = "SELECT id, nome, tarefa, prioridade, status FROM tarefas";
$result = $conn->query($sql);

// Exibe as tarefas em uma tabela
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["nome"] . "</td>";
        echo "<td>" . $row["tarefa"] . "</td>";
        echo "<td>" . $row["prioridade"] . "</td>";
        echo "<td>" . $row["status"] . "</td>";
        echo "<td>";
        echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
        echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
        echo "<select name='status'>";
        echo "<option value='cancelado' " . ($row["status"] == "cancelado" ? "selected" : "") . ">Cancelado</option>";
        echo "<option value='em andamento' " . ($row["status"] == "em andamento" ? "selected" : "") . ">Em Andamento</option>";
        echo "<option value='concluído' " . ($row["status"] == "concluído" ? "selected" : "") . ">Concluído</option>";
        echo "</select>";
        echo "<input type='submit' value='Atualizar Status'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr>";
    echo "<td colspan='5'>Nenhuma tarefa cadastrada.</td>";
    echo "</tr>";
}
// Fecha a conexão com o banco de dados
$conn->close();
?>
</table>
</body>
</html>