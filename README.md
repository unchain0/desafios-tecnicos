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

| #   | Título               | Descrição                                       | Status |
| --- | -------------------- | ----------------------------------------------- | ------ |
| 1   | Manipulação de Dados | Leitura de CSV, processamento e geração de JSON | ✅     |
| 2   | —                    | —                                               | ⏳     |
| 3   | —                    | —                                               | ⏳     |

## Requisitos

- [uv](https://docs.astral.sh/uv/getting-started/installation/) para gerenciamento de dependências

## Executando

```bash
git clone https://github.com/unchain0/desafios-tecnicos.git
cd desafios-tecnicos
uv sync
```

## Qualidade de Código

```bash
uv run ruff check .
uv run mypy .
```
