if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Variáveis de ambiente configuradas no Render
    $host = getenv('Hostname');
    $port = getenv('Port');
    $dbname = getenv('DB_Name');
    $user = getenv('Username');
    $password = getenv('Password');

    // Conexão ao banco de dados
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

    if (!$conn) {
        die("Erro na conexão: " . pg_last_error());
    }

    // Captura e sanitização dos dados do formulário
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $terapia = filter_input(INPUT_POST, 'terapia', FILTER_SANITIZE_STRING);
    $observacao = filter_input(INPUT_POST, 'observacao', FILTER_SANITIZE_STRING);
    $whatsapp = filter_input(INPUT_POST, 'whatsapp', FILTER_SANITIZE_STRING);
    $receber_info_whatsapp = isset($_POST['selecione_whatsapp']) ? 'TRUE' : 'FALSE';
    $receber_info_email = isset($_POST['selecione_email']) ? 'TRUE' : 'FALSE';
    $formato = filter_input(INPUT_POST, 'formato', FILTER_SANITIZE_STRING);

    // Validações básicas
    if (!$email) {
        echo "E-mail inválido. Preencha corretamente.";
        pg_close($conn);
        exit;
    }

    if (strlen($whatsapp) !== 11 || !is_numeric($whatsapp)) {
        echo "Número de telefone inválido. Deve conter 11 dígitos (DDD + número).";
        pg_close($conn);
        exit;
    }

    // Inserção de dados no banco de dados
    $query = "INSERT INTO formulario (nome, email, terapia, observacao, whatsapp, receber_info_whatsapp, receber_info_email, formato) 
              VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";
    $params = [$nome, $email, $terapia, $observacao, $whatsapp, $receber_info_whatsapp, $receber_info_email, $formato];
    $result = pg_query_params($conn, $query, $params);

    if ($result) {
        echo "Formulário enviado com sucesso!";
    } else {
        echo "Erro ao enviar: " . pg_last_error($conn);
    }

    // Fechar a conexão
    pg_close($conn);
}