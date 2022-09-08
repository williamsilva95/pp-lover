# PP Lover :green_heart:

### Um belo dia ...
Os caras lá da Pay criaram um desafio teste de realizar transferências entre usuários de forma rápida e escalável. A partir dai nasceu o **PP Lover** :green_heart:, uma API incrível de uma carteira virtual onde você poderá transferir dinheiro entre pessoas e lojistas através dos nossos endpoints.


## Tecnologias

- **PHP com framework Laravel**


## Requisitos

- Para ambos tipos de usuário, precisamos do Nome Completo, CPF, e-mail e Senha. CPF/CNPJ e e-mails devem ser únicos no sistema. Sendo assim, seu sistema deve permitir apenas um cadastro com o mesmo CPF ou endereço de e-mail.
- Usuários podem enviar dinheiro (efetuar transferência) para lojistas e entre usuários.
- Lojistas só recebem transferências, não enviam dinheiro para ninguém.
- Validar se o usuário tem saldo antes da transferência.
- Antes de finalizar a transferência, deve-se consultar um serviço autorizador externo, use este mock para simular (https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6).
- A operação de transferência deve ser uma transação (ou seja, revertida em qualquer caso de inconsistência) e o dinheiro deve voltar para a carteira do usuário que envia.
- No recebimento de pagamento, o usuário ou lojista precisa receber notificação (envio de email, sms) enviada por um serviço de terceiro e eventualmente este serviço pode estar indisponível/instável. Use este mock para simular o envio (http://o4d9z.mocklab.io/notify).
- Este serviço deve ser RESTFul.


## Execução

Para iniciar é necessário clonar o projeto do GitHub num diretório de sua preferência:

```bash
  git clone https://github.com/williamsilva95/pp-lover.git
```

#### Faça uma copia do arquivo .env.example e deixe apenas como .env na mesma pasta do projeto

Com o terminal aberto na pasta do projeto, instale as dependências do projeto 

```bash
  composer install
```

Rode as migrations

```bash
  php artisan migrate
```

Utilize o comando abaixo para gerar o link de inicialização do projeto

```bash
  php artisan serve
```

Rode os testes

```bash
  vendor/bin/phpunit
```


## API Routes


#### Users and Transactions Routes

| HTTP Method	| Path | Action | Scope | Desciption  |
| ----- | ----- | ----- | ---- |------------- |
| GET      | /user | index | users:list | Get all users
| POST     | /user/register | store | users:create | Create an user
| GET      | /user/show/{user_id} | show | users:read |  Fetch an user by id
| PUT      | /user/update/{user_id} | update | users:write | Update an user by id
| DELETE      | /user/delete/{user_id} | destroy | users:delete | Delete an user by id
| GET      | user/transaction/balance | getBalance | transaction:balance | Get balance from user
| POST     | user/transaction/transfer | transfer | transaction:transfer | Transfer values between users


## Exemplos de retornos da API


#### POST /user/register
- Cadastra um novo usuário PF ou PJ
```json
{
    "name" : "William Silva",
    "email": "will@pplover.com",
    "document": "99999999999",
    "type": "PF",
    "password": "secret123",
    "balance": 50
}
```
```json
{
    "name" : "Fulano De Tal",
    "email": "fulano@gmail.com",
    "document": "11111111111111",
    "type": "PJ",
    "password": "password123",
    "balance": 100
}
```

#### GET /user
- Recupera lista de usuários
```json
{
    "users" : {
        {
            "name": "William Silva",
            "email": "will@pplover.com",
            "password": "%$%$%$%%$%$%$%$%$$%$",
            "document": "99999999999",
            "type": "PF",
            "password": "secret123",
            "balance": 50
        },
        {
            "name": "Fulano De Tal",
            "email": "fulano@gmail.com",
            "password": "%$%$%$%%$%$%$%$%$$%$",
            "document": "11111111111111",
            "type": "PJ",
            "password": "password123",
            "balance": 100
        },
        {}
    }
}
```

#### PUT /user/update
- Atualiza um usuário
```json
{
    "user_id" : "1",
    "name" : "William Silva",
    "email": "willsilva@pplover.com",
}
```

#### DELETE /user/delete
- Deleta um usuário
```json
{
    "user_id" : "1",
}
```

## Transaction API 


#### GET /user/transaction/balance
- Recupera o saldo do usuário
```json
{
    "user_id" : 1,
    "Balance": 50
}
```

#### POST /user/transaction/transfer
- Realiza a transferência entre usuários (Apenas Pessoas físicas podem transferir)
```json
{
    "payer_id" : 1,
    "payee_id" : 2,
    "amount": 10
}
```