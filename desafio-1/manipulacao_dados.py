"""
Desafio 1 - Manipulação de Dados
Leitura de CSV, processamento e geração de JSON.
"""

import argparse
import json
import re
import sys
from pathlib import Path
from typing import NamedTuple

import pandas as pd
from loguru import logger

logger.remove()
logger.add(sys.stderr, format="<level>{level}:</level> {message}", level="INFO")


class ResultadoProcessamento(NamedTuple):
    total_produtos: int
    produto_mais_caro: str
    produto_mais_barato: str
    preco_medio: float


def detectar_delimitador(caminho: Path) -> str:
    """
    Detecta o delimitador do CSV (';' ou ',').

    Args:
        caminho: Caminho para o arquivo CSV.

    Returns:
        str: O delimitador detectado (';' ou ',').
    """
    with open(caminho, encoding="utf-8") as f:
        primeira_linha = f.readline()
    return ";" if ";" in primeira_linha else ","


def normalizar_preco(valor: object) -> float | None:
    """
    Normaliza um valor de preço para float usando regex.

    Formatos suportados:
    - 3499.90 ou 3499,90 (decimal simples)
    - 3.499,90 (BR: milhares='.', decimal=',')
    - 3,499.90 (US: milhares=',', decimal='.')
    - 3.499 ou 3,499 (apenas milhares, sem decimal)

    Args:
        valor: Valor a ser normalizado (número ou string).

    Returns:
        float | None: O valor normalizado como float, ou None se inválido.
    """
    if not (valor := str(valor).strip()):
        return None

    try:
        if re.search(r",\d{1,2}$", valor):
            # BR: termina com ,XX → remove pontos, troca vírgula por ponto
            valor = valor.replace(".", "").replace(",", ".")
        elif re.search(r"\.\d{1,2}$", valor):
            # US: termina com .XX → remove vírgulas e pontos extras
            valor = re.sub(r"[.,](?=.*\.)", "", valor)
        else:
            # Sem decimal: remove todos os separadores
            valor = re.sub(r"[.,]", "", valor)

        return float(valor)
    except ValueError:
        return None


def ler_csv(caminho_arquivo: str) -> pd.DataFrame:
    """
    Lê um arquivo CSV contendo produtos e preços.

    Detecta automaticamente:
    - Delimitador de colunas: ',' ou ';'
    - Formato de cada preço individualmente (BR ou US)

    Args:
        caminho_arquivo: Caminho para o arquivo CSV.

    Returns:
        DataFrame com produtos válidos.

    Raises:
        FileNotFoundError: Se o arquivo não existir.
        ValueError: Se o arquivo estiver vazio ou sem dados válidos.
    """
    arquivo = Path(caminho_arquivo)

    if not arquivo.exists():
        raise FileNotFoundError(f"Arquivo não encontrado: {caminho_arquivo}")

    delimitador = detectar_delimitador(arquivo)
    df = pd.read_csv(arquivo, encoding="utf-8", sep=delimitador, dtype={"preco": str})

    if df.empty:
        raise ValueError("Arquivo CSV está vazio")

    df.columns = df.columns.str.strip().str.lower()

    if "produto" not in df.columns or "preco" not in df.columns:
        raise ValueError("CSV deve conter colunas 'produto' e 'preco'")

    total_inicial = len(df)

    df = df[df["produto"].notna() & (df["produto"].astype(str).str.strip() != "")]

    df["preco"] = df["preco"].apply(normalizar_preco)

    df = df[df["preco"].notna() & (df["preco"] >= 0)]

    linhas_invalidas = total_inicial - len(df)
    if linhas_invalidas > 0:
        logger.warning(f"{linhas_invalidas} linha(s) inválida(s) ignorada(s)")

    if df.empty:
        raise ValueError("Nenhum produto válido encontrado no arquivo")

    return df


def processar_produtos(products_df: pd.DataFrame) -> ResultadoProcessamento:
    """
    Processa o DataFrame de produtos e calcula estatísticas.

    Args:
        products_df: DataFrame com produtos.

    Returns:
        ResultadoProcessamento com as estatísticas calculadas.
    """
    if products_df.empty:
        raise ValueError("DataFrame de produtos está vazio")

    idx_mais_caro = products_df["preco"].idxmax()
    idx_mais_barato = products_df["preco"].idxmin()

    return ResultadoProcessamento(
        total_produtos=len(products_df),
        produto_mais_caro=str(products_df.loc[idx_mais_caro, "produto"]),
        produto_mais_barato=str(products_df.loc[idx_mais_barato, "produto"]),
        preco_medio=round(float(products_df["preco"].mean()), 2),
    )


def exibir_resultado(resultado: ResultadoProcessamento) -> None:
    """Exibe o resultado do processamento no console."""
    print("=" * 50)
    print("RESULTADO DO PROCESSAMENTO".center(50))
    print("=" * 50)
    print(f"Total de produtos: {resultado.total_produtos}")
    print(f"Produto mais caro: {resultado.produto_mais_caro}")
    print(f"Produto mais barato: {resultado.produto_mais_barato}")
    print(f"Preço médio: R$ {resultado.preco_medio:.2f}")
    print("=" * 50)


def salvar_json(resultado: ResultadoProcessamento, caminho_saida: str) -> None:
    """
    Salva o resultado em um arquivo JSON.

    Args:
        resultado: Resultado do processamento.
        caminho_saida: Caminho do arquivo JSON de saída.
    """
    dados = {
        "total_produtos": resultado.total_produtos,
        "produto_mais_caro": resultado.produto_mais_caro,
        "produto_mais_barato": resultado.produto_mais_barato,
        "preco_medio": resultado.preco_medio,
    }

    with open(caminho_saida, "w", encoding="utf-8") as f:
        json.dump(dados, f, ensure_ascii=False, indent=2)

    logger.success(f"Resultado salvo em '{caminho_saida}'")


def processar_arquivo_csv(
    caminho_csv: str, caminho_json: str = "resultado.json"
) -> ResultadoProcessamento:
    """
    Função principal que orquestra todo o processamento.

    Args:
        caminho_csv: Caminho do arquivo CSV de entrada.
        caminho_json: Caminho do arquivo JSON de saída.

    Returns:
        ResultadoProcessamento com as estatísticas.
    """
    df = ler_csv(caminho_csv)
    resultado = processar_produtos(df)
    exibir_resultado(resultado)
    salvar_json(resultado, caminho_json)
    return resultado


def garantir_extensao(caminho: Path, extensao: str) -> Path:
    """Adiciona extensão ao arquivo se não estiver presente."""
    if caminho.suffix.lower() != extensao:
        return caminho.with_suffix(extensao)
    return caminho


def parse_args() -> argparse.Namespace:
    """Processa argumentos da linha de comando."""
    parser = argparse.ArgumentParser(
        description="Processa arquivo CSV de produtos e gera estatísticas em JSON.",
        formatter_class=argparse.RawDescriptionHelpFormatter,
        add_help=False,
        epilog="""
exemplos:
  %(prog)s                         # usa produtos.csv e gera resultado.json
  %(prog)s produtos                # usa produtos.csv e gera resultado.json  
  %(prog)s dados resultado         # usa dados.csv e gera resultado.json
  %(prog)s entrada.csv saida.json  # usa entrada.csv e gera saida.json
""",
    )
    parser.add_argument(
        "-h",
        "--help",
        action="help",
        help="exibe esta mensagem de ajuda e sai",
    )
    parser.add_argument(
        "entrada",
        nargs="?",
        default="produtos.csv",
        help="arquivo CSV de entrada (default: produtos.csv)",
    )
    parser.add_argument(
        "saida",
        nargs="?",
        default="resultado.json",
        help="arquivo JSON de saída (default: resultado.json)",
    )
    return parser.parse_args()


def main() -> None:
    """Ponto de entrada do script."""
    args = parse_args()
    diretorio_script = Path(__file__).parent

    caminho_csv = garantir_extensao(diretorio_script / args.entrada, ".csv")
    caminho_json = garantir_extensao(Path(args.saida), ".json")

    try:
        processar_arquivo_csv(str(caminho_csv), str(caminho_json))
    except FileNotFoundError as e:
        logger.error(str(e))
        sys.exit(1)
    except ValueError as e:
        logger.error(f"Validação: {e}")
        sys.exit(1)
    except Exception as e:
        logger.exception(f"Erro inesperado: {e}")
        sys.exit(1)


if __name__ == "__main__":
    main()
