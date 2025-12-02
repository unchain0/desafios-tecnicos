# Desafio 1 - Manipulação de Dados

Script Python para leitura de arquivo CSV, processamento de dados e geração de relatório em JSON.

## Funcionalidades

- Leitura de arquivo CSV com produtos e preços
- Cálculo de estatísticas (total, mais caro, mais barato, média)
- Tratamento de erros (arquivo não encontrado, linhas inválidas, valores não numéricos)
- Geração de arquivo JSON com resultados

## Execução

### Usando arquivo padrão (produtos.csv)

Entre na pasta `desafio-1` e execute:

```bash
uv run manipulacao_dados.py
```

### Especificando arquivo de entrada

```bash
uv run manipulacao_dados.py produtos.csv
```

### Especificando entrada e saída

```bash
uv run manipulacao_dados.py produtos.csv resultado.json
```

## Formato do CSV de Entrada

O arquivo CSV deve conter duas colunas:

| Coluna  | Tipo   | Descrição        |
| ------- | ------ | ---------------- |
| produto | string | Nome do produto  |
| preco   | float  | Preço do produto |

O script também detecta automaticamente o formato:

| Formato    | Delimitador | Decimal | Exemplo   |
| ---------- | ----------- | ------- | --------- |
| Brasileiro | `;`         | `,`     | `3499,90` |
| Americano  | `,`         | `.`     | `3499.90` |

## Formato do JSON de Saída

```json
{
  "total_produtos": 10,
  "produto_mais_caro": "Notebook Dell",
  "produto_mais_barato": "Mousepad XL",
  "preco_medio": 716.69
}
```

## Tratamento de Erros

O script trata os seguintes casos:

- **Arquivo não encontrado**: Exibe mensagem e encerra com código 1
- **Linhas com colunas insuficientes**: Ignora a linha e exibe aviso
- **Nome do produto ou preço vazios**: Ignora a linha e exibe aviso
- **Preço não numérico**: Ignora a linha e exibe aviso
- **Preço negativo**: Ignora a linha e exibe aviso
- **Arquivo sem dados válidos**: Exibe mensagem e encerra com código 1

## Exemplo de Execução

```txt
$ uv run manipulacao_dados.py
==================================================
            RESULTADO DO PROCESSAMENTO
==================================================
Total de produtos: 10
Produto mais caro: Notebook Dell
Produto mais barato: Mousepad XL
Preço médio: R$ 716.69
==================================================
SUCCESS: Resultado salvo em 'resultado.json'
```
