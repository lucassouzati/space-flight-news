<h1 align="center">
    Space Flight News API
</h1>

>  This is a challenge by [Coodesh](https://coodesh.com/)

## :boat: Sobre o projeto

Nesse projeto foi desenvolvida uma API para disponibilização de dados de artigos consumidos na API [Space Flight News](https://api.spaceflightnewsapi.net/documentation). Primeiramente implementou-se um script para importação de todos artigos disponíveis na API, e depois outro para importação diária de novos artigos publicados. Também foi implementado um sistema de alerta por e-mail para situações de ocorrência de falhas nas importações. 

Mais detalhes sobre o sistema estão descritos nos próximos tópicos.

## :hammer: Tecnologias:
- **[PHP 8.1](https://www.php.net)**
- **[Laravel](https://laravel.com/)**
- **[MySQL](https://www.mysql.com/)**
- **[MailHog](https://github.com/mailhog/MailHog)**
- **[Swagger-PHP](https://zircote.github.io/swagger-php/)**
- **[PHPUnit](https://phpunit.de)**

## :rocket: Como rodar esse projeto
Primeiramente, clone este repositório no local que você costuma desenvolver seus projetos:
```
git clone git@github.com:lucassouzati/space-flight-news.git
```

Vá para a pasta do projeto e copie o arquivo com as variáveis de ambiente:
```
 cd space-flight-news/ && cp .env.example .env
 ```
 
Para executar o projeto de forma rápida, você precisa ter o Docker e o Docker Compose instalado em seu computador. Caso não tenha ambiente de desenvolvimento e seja usuário do Windows, recomendo que utilize WSL (Windows Subsystem for Linux) junto com Ubuntu e Docker instalado diretamente nele. 
Execute o seguinte comando para instalar as dependências do projeto:
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

Agora execute o comando:
```
docker-compose up -d
```

Em seguida:
```
docker-compose exec app php artisan key:generate && docker-compose exec app php artisan migrate
```

Para testar se está tudo funcionando:
```
docker-compose exec app php artisan test
```

O sistema já se encontra funcional e você pode verificar a documentação da API pelo endereço http://localhost:8000/swagger. 
Agora se desejar realizar a importação de todos artigos da API, execute o seguinte comando (demora alguns minutos):
```
docker-compose exec app php artisan db:seed
```

## :computer: Features

### Articles CRUD

Deve ser possível adicionar, visualizar, editar e remover registros referente a Artigos (Articles).
<h4 align="center">
    <img title="Endpoints para o gerenciamento de artigos" src=".github/readme/endpointsarticles.png" width="1024px" />
</h4>

Na listagem de artigos também foi implementado um parâmetro chamado "paginate", pois buscou-se evitar requisições pesadas usando uma paginação padrão de 300 artigos por requisição. Mas possibilitou-se que o usuário da API altere esse valor até o limite de 1000 artigos. Acima disso, a API recusa a requisição.
<h4 align="center">
    <img title="Parâmetro paginate no endpoint de articles" src=".github/readme/paginatearticles.png" width="1024px" />
</h4>

### Importação diária de artigos
Utilizando o recurso de Task Scheduling do Laravel, foi possível implementar uma rotina de importação de novos artigos. Para que ela funcione no servidor, basta adicionar o seguinte comando no cron do servidor:
```
* * * * * cd /caminho-ate-o-projeto && php artisan schedule:run >> /dev/null 2>&1
```

O horário da importação foi implementado em uma variável de ambiente no arquivo .ENV do projeto. Dessa forma, é possível alterar o horário padrão de 09 horas para outro sem a necessidade de alteração do código do sistema.
```
.env

IMPORT_TIME=09:00
```

### Sistema de alerta de falhas na importação
Na ocorrência de alguma falha nas importações, o sistema irá disparar uma mensagem para e-mail definido na variável de ambiente APP_ADMIN_MAIL. Para fins de testes em ambiente de desenvolvimento, foi quebrado temporiamente a URL da API, e utilizado um e-mail fictício chamado "admin@admin.com" para visualização da mensagem no serviço [MailHog](https://github.com/mailhog/MailHog), conforme imagem a seguir:
<h4 align="center">
    <img title="Exemplo de e-mail do sistema de alertas" src=".github/readme/teste_disparo_de_emails.png" width="1024px" />
</h4>

## :top: Informações adicionais e boas práticas
### Documentação da API com Open API 3.0 e Swagger
Esta API foi documentada seguindo os preceitos do Open API 3.0. Através do [Swagger-PHP](https://zircote.github.io/swagger-php/), foi possível gerar um arquivo yaml com todas as definições dos endpoints da API, e disponibilizado de forma visual através do caminho /swagger. Dessa forma é possível verificar todos endpoints e especificações dos possíveis parâmetros, além de testá-los. 
<h4 align="center">
    <img title="Swagger com especificações" src=".github/readme/swaggerpostexample.png" width="1024px" />
</h4>

### API RESTFul (Nível 3 de maturidade no Modelo de Richardson) 
Além do uso de endpoints patronizados, verbos HTTP e status code corretos, foi utilizado o conceito de HATEOAS (Hypermedia as the Engine of Application State) para atingir o nível 3 de maturidade no Modelo de Richardson. Nesse caso, buscou-se implementar o conceito de hipermídia nas listagens de artigos além das informações do modelo de dados, adicionando metadados como links para consultar cada artigo individual, bem como para voltar a listagem. De forma bem resumida, o arquivo JSON se torna "navegável" como se fosse um HTML. 
``` json
{
    "data": [
        {
            "id": 1,
            "featured": 0,
            "title": "No commercial crew test flights expected this year",
            "url": "https://spaceflightnow.com/2018/10/06/no-commercial-crew-test-flights-expected-this-year/",
            "imageUrl": "https://mk0spaceflightnoa02a.kinstacdn.com/wp-content/uploads/2018/10/ccp-countdown-header-326x245.jpg",
            "newsSite": "Spaceflight Now",
            "summary": "",
            "publishedAt": "2018-10-05T22:00:00.000Z",
            "launches": [],
            "events": [],
            "links": {
                "rel": "self",
                "href": "http://localhost/articles/1"
            }
        },
       ...
       ]
   }
   ```

### Importação de todos artigos e desacoplamento
Foi identificado a necessidade de duas rotinas de importação de dados da api Space Fligth News. Dessa forma, foi criado duas classes, sendo a ImportNewArticles ImportAllArticles. Buscando desacoplamento com o uso de Orientação a Objetos, foi criado uma classe abstrata chamada BaseServiceSpaceFlightNewsApi, onde se encontra as rotinas de acesso a API e a varíavel de ambiente da URL base. Dessa forma, cria-se um cenário onde uma possível mudança na API torna-se fácil no código, sem a necessidade de ficar alterando diversos lugares.

### Coluna "api_id"
Foi identificado também um possível caso de conflito de id, uma vez que é possível inserir artigos por essa API, e também pela importação da API da Space Flight News. Então os artigos importados da API são armazenados com uma coluna "api_id" onde é salvo o id da API de origem. Essa coluna é utilizada para referência ao importar novos artigos, pois no caso desse artigo já tiver sido importado, ele é atualizado, conforme código abaixo.
```php
class ImportNewArticles extends BaseServiceSpaceFlightNewsApi
{
 . . .
 public function execute()
    {
        $result = $this->getJsonResult('articles');
        if ($result != null) {
            foreach ($result as $data) {
                $article = Article::updateOrCreate(['api_id' => $data['id']], [
                    'api_id' => $data['id'],
                    'featured' => $data['featured'],
                    'title' => $data['title'],
                    'url' => $data['url'],
                    'imageUrl' => $data['imageUrl'],
                    'newsSite' => $data['newsSite'],
                    'summary' => $data['summary'],
                    'publishedAt' => $data['publishedAt'],
                ]);
                
                ...
            }
           ...
        }

    }
}

```

### Cobertura de testes
Com a presença de rotinas adicionais, foi identificado a necessidade de testar outras funcionalidades além daquelas relacionadas a disponibilização dos endpoints. Nesse projeto, foi implementado uma cobertura de testes de 73%, utilizando recursos do Laravel para dublê de testes, como o Http::fake() para simular respostas da API e testar possíveis cenários, até os excepcionais.
<h4 align="center">
    <img title="Cobertura de testes" src=".github/readme/test-coverage-1.png" width="800" />
</h4>
<h4 align="center">
    <img title="Cobertura de testes" src=".github/readme/test-coverage-2.png" width="800px" />
</h4>

## :raising_hand: Como contribuir para o projeto

- Faça um **fork** do projeto;
- Crie uma nova branch com as suas alterações: `git checkout -b my-feature`
- Salve as alterações e crie uma mensagem de commit contando o que você fez:`git commit -m "feature: My new feature"`
- Envie as suas alterações: `git push origin my-feature`

> Caso tenha alguma dúvida confira este [guia de como contribuir no GitHub](https://github.com/firstcontributions/first-contributions)

## :pencil: Licença

Este projeto esta sobe a licença MIT. Veja a [LICENÇA](https://opensource.org/licenses/MIT) para saber mais.

Feito com ❤️ por Lucas Siqueira 👋🏽 [Entre em contato!](https://www.linkedin.com/in/lucas-de-souza-siqueira-a6469952/)
