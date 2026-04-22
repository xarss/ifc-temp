<?php
// Habilita a exibição de todos os erros e warnings
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Aumenta o limite de upload para 10MB
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');
ini_set('max_execution_time', 300);
ini_set('memory_limit', '256M');
ini_set('max_file_uploads', 50);

// Debug: Exibe o conteúdo de $_POST e $_FILES
echo "<pre>";
print_r($_POST);
print_r($_FILES);
echo "</pre>";

// Verifica se um arquivo foi enviado pela câmera ou pelo upload de arquivo
if (isset($_FILES['foto_camera']) && $_FILES['foto_camera']['error'] == 0) {
    $foto = $_FILES['foto_camera']; // Usa a foto da câmera
} elseif (isset($_FILES['foto_arquivo']) && $_FILES['foto_arquivo']['error'] == 0) {
    $foto = $_FILES['foto_arquivo']; // Usa a foto do arquivo
} else {
    die("Nenhuma foto foi enviada ou ocorreu um erro no upload.");
}

// Verifica o tamanho do arquivo (não pode ser maior que 10MB)
if ($foto['size'] > 10 * 1024 * 1024) { // 10MB
    die("Erro: O arquivo é muito grande. O tamanho máximo permitido é 10MB.");
}

// Verificações de segurança
$permitidos = ['image/jpeg', 'image/png', 'image/gif']; // Tipos de arquivo permitidos
if (!in_array($foto['type'], $permitidos)) {
    die("Erro: Apenas arquivos JPEG, PNG e GIF são permitidos.");
}

// Verifica se o arquivo é realmente uma imagem
if (!getimagesize($foto['tmp_name'])) {
    die("Erro: O arquivo não é uma imagem válida.");
}

// Processa o arquivo
$nomeArquivo = $foto['name']; // Nome do arquivo
$caminhoTemporario = $foto['tmp_name']; // Caminho temporário
$pastaDestino = 'repositorio/'; // Caminho absoluto para a pasta "repositorio"

// Verifica se a pasta existe, se não, cria
if (!file_exists($pastaDestino)) {
    if (!mkdir($pastaDestino, 0777, true)) {
        die("Erro: Não foi possível criar a pasta de destino.");
    }
}

// Gera um nome único para o arquivo
$nomeUnico = uniqid() . '_' . $nomeArquivo;

// Move o arquivo para a pasta de destino
if (move_uploaded_file($caminhoTemporario, $pastaDestino . $nomeUnico)) {
    // Redireciona para a página de confirmação
    header('Location: confirmacao.html');
    exit(); // Encerra a execução do script após o redirecionamento
} else {
    echo "Erro ao enviar a foto. Verifique as permissões da pasta.";
    echo "<pre>";
    print_r(error_get_last()); // Exibe o último erro ocorrido
    echo "</pre>";
}
?>