# Desafio 2 - Compactação de String

Implementação de uma função que compacta strings substituindo sequências de caracteres repetidos consecutivos por `caractere+quantidade`.

## Exemplos

| Entrada     | Saída      |
| ----------- | ---------- |
| `aaabbcaaa` | `a3b2c1a3` |
| `abc`       | `a1b1c1`   |
| `aaaaa`     | `a5`       |
| (vazia)     | (vazia)    |

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

```python
from compactacao_string import compactar_string

resultado = compactar_string("aaabbcaaa")
print(resultado)  # a3b2c1a3
```

### Via CLI

```bash
uv run compactacao_string.py "aaabbcaaa"
# Original:   'aaabbcaaa'
# Compactada: 'a3b2c1a3'
```

## Executar os Testes

### Com pytest (recomendado)

```bash
uv run pytest -v
```

### Executar diretamente

```bash
python test_compactacao_string.py
```

## Cobertura de Testes

Os testes cobrem:

- **Casos normais**: caracteres repetidos, sem repetições, todos iguais
- **Casos de borda**: string vazia, caractere único, dois caracteres
- **Caracteres especiais**: números, símbolos, espaços
- **Case sensitivity**: maiúsculas vs minúsculas
- **Unicode**: caracteres acentuados e emojis
- **Strings longas**: sequências extensas
