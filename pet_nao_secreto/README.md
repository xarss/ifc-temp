# Campanha de Natal - ONG de Animais

Este é um sistema para a campanha de Natal da ONG de animais, permitindo que as pessoas conheçam os animais resgatados e façam doações.

## Estrutura do Projeto

- `pet_nao_secreto.php` - Página principal que exibe a galeria de animais
- `minha_historia.php` - Página que mostra os detalhes de um animal específico
- `config.php` - Configurações de conexão com o banco de dados
- `db.sql` - Script SQL para criar o banco de dados e tabelas
- `fotos_resgatados_natal/` - Pasta para armazenar as fotos dos animais

## Configuração Inicial

1. **Banco de Dados**
   - Importe o arquivo `db.sql` no seu phpMyAdmin ou execute o script SQL no seu servidor MySQL
   - Atualize as credenciais no arquivo `config.php` se necessário

2. **Pasta de Fotos**
   - Crie uma pasta chamada `fotos_resgatados_natal` dentro do diretório `pet_nao_secreto/`
   - As fotos dos animais devem ser salvas nesta pasta

3. **Cadastro de Animais**
   - Acesse o phpMyAdmin
   - Vá para a tabela `animais` do banco de dados `ong_natal`
   - Clique em "Inserir" para adicionar novos animais com os seguintes campos:
     - `nome`: Nome do animal
     - `imagem_url`: Caminho para a foto (ex: `fotos_resgatados_natal/nome_do_arquivo.jpg`)
     - `historia`: História completa do animal
     - `whatsapp`: Número de telefone para contato (apenas números, com código do país)

## Como Usar

1. Acesse `pet_nao_secreto.php` no navegador para ver a galeria de animais
2. Clique em "Ver Minha História" para ver os detalhes de um animal específico
3. Na página do animal, use o botão do WhatsApp para entrar em contato e fazer uma doação

## Personalização

- Você pode personalizar as cores e estilos editando as classes CSS nos arquivos
- Atualize o número do WhatsApp no banco de dados para o número da sua ONG
- Adicione mais campos à tabela `animais` se precisar de informações adicionais

## Segurança

- Certifique-se de que a pasta `fotos_resgatados_natal` tem permissões de escrita adequadas
- Mantenha as credenciais do banco de dados em segredo
- Valide e limpe todas as entradas do usuário para evitar injeção de SQL
