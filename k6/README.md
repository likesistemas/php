# K6.io

Para rodar os testes iniciar o PHP usando o docker-compose da versão escolhida e executar o k6 como o exemplo abaixo usando o PHP 7.3.

```shell
docker compose -f docker-compose-74.yml up --build -d
k6 run --vus 40 --duration 30s index.js
```

Neste exemplo ele irá rodar com 40 conexões simultaneas por 30 segundos.

Para instalar o k6 seguir os passos no próprio [site](https://k6.io/docs/getting-started/installation/).
