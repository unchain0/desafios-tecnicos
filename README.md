# Desafios Técnicos

Repositório com soluções para desafios técnicos de programação.

## Estrutura

```txt
desafios-tecnicos/
├── desafio-1/
├── desafio-2/
├── desafio-3/
├── .gitignore
├── .python-version
├── pyproject.toml
├── README.md
└── uv.lock
```

## Desafios

| #   | Título                | Descrição                                            | Status |
| --- | --------------------- | ---------------------------------------------------- | ------ |
| 1   | Manipulação de Dados  | Leitura de CSV, processamento e geração de JSON      | ✅     |
| 2   | Compactação de String | Implementação de algoritmo de compactação de strings | ✅     |
| 3   | —                     | —                                                    | ⏳     |

## Requisitos

- [git](https://git-scm.com/install/) para clonar o repositório
- [uv](https://docs.astral.sh/uv/getting-started/installation/) para gerenciamento de dependências e execução dos scripts

## Inicialização

```bash
git clone https://github.com/unchain0/desafios-tecnicos.git
cd desafios-tecnicos
uv sync
```

## Verificação de Qualidade do Código

```bash
uv run ruff check .
uv run mypy .
```
