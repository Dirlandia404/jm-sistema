# Sistema de Ordem de Serviços — JM Informática

Desenvolvi este sistema para controlar os serviços realizados pelos funcionários da JM Informática. O projeto permite cadastrar usuários, realizar login, cadastrar serviços, acompanhar os serviços pendentes e finalizados e calcular a comissão dos funcionários.

## Funcionalidades

- Cadastro e login de usuários;
- Cadastro, edição e exclusão de serviços;
- Finalização de serviços;
- Cálculo de comissão;
- Envio de e-mail ao finalizar um serviço;
- Valor total dos serviços do usuário logado;
- Lista dos últimos serviços pendentes;
- Filtros por período, serviço, status e funcionário;
- Dashboard responsivo.

## Regras de comissão

| Valor do serviço      | Comissão |
| --------------------- | -------: |
| Até R$ 1.000,00       |       5% |
| Acima de R$ 1.000,00  |      10% |
| Acima de R$ 10.000,00 |      20% |

## Tecnologias utilizadas

- PHP;
- MySQL;
- PDO;
- HTML;
- CSS;
- JavaScript;
- Arquitetura MVC.

O sistema foi desenvolvido sem frameworks e sem Composer, conforme solicitado no teste prático.

## Como executar

### 1. Clone o projeto

```bash
git clone https://github.com/Dirlandia404/jm-sistema.git
cd jm-sistema
```

### 2. Crie o banco de dados

O script de criação das tabelas está em `database/schema.sql`.

Execute:

```bash
mysql -u root -p < database/schema.sql
```

### 3. Configure a conexão

Crie o arquivo de configuração:

```bash
cp config/database.example.php config/database.php
```

Depois, informe os dados do seu banco em `config/database.php`.

### 4. Inicie o sistema

```bash
php -S localhost:8000 -t public
```

Acesse no navegador:

```text
http://localhost:8000
```

## Observação sobre o e-mail

O envio de e-mail utiliza a função `mail()` do PHP. Para funcionar, é necessário configurar um serviço de envio, como Sendmail ou msmtp.

## Limitações encontradas

O campo de senha foi definido como VARCHAR(45) na modelagem fornecida, não possuindo espaço suficiente para armazenar com segurança o resultado de password_hash(). Por isso, mantive o formato original do banco.

A modelagem fornecida mistura nomes de campos em português e inglês, como `ativo`. Mantive a estrutura original para evitar mudanças no modelo, mas futuramente os campos poderiam ser padronizados para `active` e `updated_at` isso na tabela de user.
