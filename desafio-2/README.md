# Desafio 2 - Compactação de String

Implementação de uma função que compacta strings substituindo sequências de caracteres repetidos consecutivos por `caractere+quantidade`.

## Exemplos

| Entrada     | Saída      |
| ----------- | ---------- |
| `aaabbcaaa` | `a3b2c1a3` |
| `abc`       | `a1b1c1`   |
| `aaaaa`     | `a5`       |
| `''`        | `''`       |

## Estrutura do Projeto

```text
desafio-2/
├── tests/
│   └── test_compactacao_string.py
├── compactacao_string.py
└── README.md
```

## Como Usar

Entre na pasta `desafio-2` e execute:

### Como módulo Python

Primeiro inicie o Python no terminal:

```bash
uv run python
```

Depois, execute o código Python no prompt:

```python
from compactacao_string import compactar_string

resultado = compactar_string("aaabbcaaa")
print(resultado)  # a3b2c1a3
```

### Via CLI

Para executar via CLI, use o comando:

```bash
uv run compactacao_string.py "aaabbcaaa"
# Original:   'aaabbcaaa'
# Compactada: 'a3b2c1a3'
```

## Executar os Testes

```bash
uv run pytest -v
```

Os testes cobrem:

- **Casos normais**: caracteres repetidos, sem repetições, todos iguais
- **Casos de borda**: string vazia, caractere único, dois caracteres
- **Caracteres especiais**: números, símbolos, espaços
- **Case sensitivity**: maiúsculas vs minúsculas
- **Unicode**: caracteres acentuados e emojis
- **Strings longas**: sequências extensas
