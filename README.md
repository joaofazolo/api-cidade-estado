# API para crud de cidades e estados utilizando php, slim e mondogb

## Instalar dependências

Rode este comando na raiz do projeto.

```bash
composer install
```

## Rodar o projeto

Para rodar a aplicação em desenvolvimento rode este comando na raiz

```bash
composer start
```

Ou você pode usar `docker-compose` para rodar a aplicação com `docker`. Para isto rode estes comando na raiz:

```bash
docker-compose up -d --build
```

Este comando iniciará tanto a aplicação em `http://localhost:8080` quanto o container do banco e do cliente para o banco que pode ser acessado digitando `http://localhost:8081` no seu browser

## Telas de pesquisa
As telas de pesquisa de cidade e estado se encontram em `http://localhost:8080/pesquisarCidade` e `http://localhost:8080/pesquisarEstado` respectivamente

## Documentação da api
Para gerar a documentação rode

```bash
./vendor/bin/openapi -o doc ./src ./app ./doc
```
a documentação será gerada no formato OpenAPI 3 em doc/openapi.yaml
