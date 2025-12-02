"""
Testes unitários para o módulo manipulacao_dados.
"""

import json
import sys
from pathlib import Path

import pandas as pd
import pytest

sys.path.insert(0, str(Path(__file__).parent.parent))

from manipulacao_dados import (
    ResultadoProcessamento,
    detectar_delimitador,
    garantir_extensao,
    ler_csv,
    normalizar_preco,
    processar_produtos,
    salvar_json,
)


class TestNormalizarPreco:
    """Testes para a função normalizar_preco."""

    # Formato brasileiro (decimal com vírgula)
    def test_decimal_br_simples(self) -> None:
        """Testa decimal brasileiro simples: 89,90"""
        assert normalizar_preco("89,90") == 89.90

    def test_decimal_br_com_milhares(self) -> None:
        """Testa formato brasileiro com milhares: 3.499,90"""
        assert normalizar_preco("3.499,90") == 3499.90

    def test_decimal_br_milhoes(self) -> None:
        """Testa formato brasileiro com milhões: 1.234.567,89"""
        assert normalizar_preco("1.234.567,89") == 1234567.89

    # Formato americano (decimal com ponto)
    def test_decimal_us_simples(self) -> None:
        """Testa decimal americano simples: 89.90"""
        assert normalizar_preco("89.90") == 89.90

    def test_decimal_us_com_milhares(self) -> None:
        """Testa formato americano com milhares: 3,499.90"""
        assert normalizar_preco("3,499.90") == 3499.90

    def test_decimal_us_milhoes(self) -> None:
        """Testa formato americano com milhões: 1,234,567.89"""
        assert normalizar_preco("1,234,567.89") == 1234567.89

    # Formatos mistos/ambíguos
    def test_multiplos_pontos_com_decimal(self) -> None:
        """Testa múltiplos pontos com decimal: 1.299.00"""
        assert normalizar_preco("1.299.00") == 1299.00

    def test_sem_decimal(self) -> None:
        """Testa número inteiro: 100"""
        assert normalizar_preco("100") == 100.0

    def test_milhares_sem_decimal_br(self) -> None:
        """Testa milhares sem decimal (3 dígitos após ponto): 1.299"""
        assert normalizar_preco("1.299") == 1299.0

    def test_milhares_sem_decimal_us(self) -> None:
        """Testa milhares sem decimal formato US: 1,299"""
        assert normalizar_preco("1,299") == 1299.0

    # Casos de borda
    def test_string_vazia(self) -> None:
        """Testa string vazia."""
        assert normalizar_preco("") is None

    def test_espacos(self) -> None:
        """Testa string com apenas espaços."""
        assert normalizar_preco("   ") is None

    def test_valor_com_espacos(self) -> None:
        """Testa valor com espaços ao redor."""
        assert normalizar_preco("  89,90  ") == 89.90

    def test_valor_invalido(self) -> None:
        """Testa valor não numérico."""
        assert normalizar_preco("abc") is None

    def test_valor_texto_invalido(self) -> None:
        """Testa texto inválido."""
        assert normalizar_preco("invalido") is None

    def test_valor_numerico(self) -> None:
        """Testa passando um número diretamente."""
        assert normalizar_preco(99.99) == 99.99

    def test_valor_inteiro(self) -> None:
        """Testa passando um inteiro."""
        assert normalizar_preco(100) == 100.0

    def test_um_digito_decimal(self) -> None:
        """Testa decimal com um dígito: 89.9"""
        assert normalizar_preco("89.9") == 89.9


class TestDetectarDelimitador:
    """Testes para a função detectar_delimitador."""

    def test_delimitador_ponto_virgula(self, tmp_path: Path) -> None:
        """Testa detecção de delimitador ';'."""
        arquivo = tmp_path / "test.csv"
        arquivo.write_text("produto;preco\nItem;100", encoding="utf-8")
        assert detectar_delimitador(arquivo) == ";"

    def test_delimitador_virgula(self, tmp_path: Path) -> None:
        """Testa detecção de delimitador ','."""
        arquivo = tmp_path / "test.csv"
        arquivo.write_text("produto,preco\nItem,100", encoding="utf-8")
        assert detectar_delimitador(arquivo) == ","

    def test_sem_delimitador_padrao_virgula(self, tmp_path: Path) -> None:
        """Testa que padrão é ',' quando não há delimitador claro."""
        arquivo = tmp_path / "test.csv"
        arquivo.write_text("produto\nItem", encoding="utf-8")
        assert detectar_delimitador(arquivo) == ","


class TestLerCsv:
    """Testes para a função ler_csv."""

    def test_csv_valido_br(self, tmp_path: Path) -> None:
        """Testa leitura de CSV válido formato brasileiro."""
        arquivo = tmp_path / "test.csv"
        arquivo.write_text(
            "produto;preco\nNotebook;3.499,90\nMouse;89,90", encoding="utf-8"
        )
        df = ler_csv(str(arquivo))
        assert len(df) == 2
        assert df.iloc[0]["preco"] == 3499.90
        assert df.iloc[1]["preco"] == 89.90

    def test_csv_valido_us(self, tmp_path: Path) -> None:
        """Testa leitura de CSV válido formato americano."""
        arquivo = tmp_path / "test.csv"
        arquivo.write_text(
            "produto,preco\nNotebook,3499.90\nMouse,89.90", encoding="utf-8"
        )
        df = ler_csv(str(arquivo))
        assert len(df) == 2
        assert df.iloc[0]["preco"] == 3499.90

    def test_csv_formatos_mistos(self, tmp_path: Path) -> None:
        """Testa CSV com formatos de preço mistos."""
        arquivo = tmp_path / "test.csv"
        arquivo.write_text(
            "produto;preco\nA;3.499,90\nB;89.90\nC;1,299.00", encoding="utf-8"
        )
        df = ler_csv(str(arquivo))
        assert len(df) == 3
        assert df.iloc[0]["preco"] == 3499.90
        assert df.iloc[1]["preco"] == 89.90
        assert df.iloc[2]["preco"] == 1299.00

    def test_arquivo_nao_encontrado(self) -> None:
        """Testa erro quando arquivo não existe."""
        with pytest.raises(FileNotFoundError, match="Arquivo não encontrado"):
            ler_csv("/caminho/inexistente.csv")

    def test_csv_vazio(self, tmp_path: Path) -> None:
        """Testa erro quando CSV está vazio (só header)."""
        arquivo = tmp_path / "test.csv"
        arquivo.write_text("produto;preco", encoding="utf-8")
        with pytest.raises(ValueError, match="Arquivo CSV está vazio"):
            ler_csv(str(arquivo))

    def test_csv_sem_colunas_obrigatorias(self, tmp_path: Path) -> None:
        """Testa erro quando CSV não tem colunas obrigatórias."""
        arquivo = tmp_path / "test.csv"
        arquivo.write_text("nome;valor\nItem;100", encoding="utf-8")
        with pytest.raises(ValueError, match="deve conter colunas"):
            ler_csv(str(arquivo))

    def test_ignora_linhas_invalidas(self, tmp_path: Path) -> None:
        """Testa que linhas inválidas são ignoradas."""
        arquivo = tmp_path / "test.csv"
        arquivo.write_text(
            "produto;preco\nValido;100\n;50\nOutro;abc\nNegativo;-10",
            encoding="utf-8",
        )
        df = ler_csv(str(arquivo))
        assert len(df) == 1
        assert df.iloc[0]["produto"] == "Valido"

    def test_colunas_com_espacos(self, tmp_path: Path) -> None:
        """Testa que espaços nos nomes das colunas são tratados."""
        arquivo = tmp_path / "test.csv"
        arquivo.write_text(" Produto ; Preco \nItem;100", encoding="utf-8")
        df = ler_csv(str(arquivo))
        assert len(df) == 1


class TestProcessarProdutos:
    """Testes para a função processar_produtos."""

    def test_processamento_basico(self) -> None:
        """Testa processamento básico de produtos."""
        df = pd.DataFrame(
            {"produto": ["Caro", "Barato", "Medio"], "preco": [100.0, 10.0, 50.0]}
        )
        resultado = processar_produtos(df)
        assert resultado.total_produtos == 3
        assert resultado.produto_mais_caro == "Caro"
        assert resultado.produto_mais_barato == "Barato"
        assert resultado.preco_medio == 53.33

    def test_produto_unico(self) -> None:
        """Testa com apenas um produto."""
        df = pd.DataFrame({"produto": ["Unico"], "preco": [99.99]})
        resultado = processar_produtos(df)
        assert resultado.total_produtos == 1
        assert resultado.produto_mais_caro == "Unico"
        assert resultado.produto_mais_barato == "Unico"
        assert resultado.preco_medio == 99.99

    def test_precos_iguais(self) -> None:
        """Testa quando todos os preços são iguais."""
        df = pd.DataFrame({"produto": ["A", "B", "C"], "preco": [50.0, 50.0, 50.0]})
        resultado = processar_produtos(df)
        assert resultado.preco_medio == 50.0

    def test_dataframe_vazio(self) -> None:
        """Testa erro com DataFrame vazio."""
        df = pd.DataFrame({"produto": [], "preco": []})
        with pytest.raises(ValueError, match="DataFrame de produtos está vazio"):
            processar_produtos(df)

    def test_arredondamento_preco_medio(self) -> None:
        """Testa arredondamento do preço médio."""
        df = pd.DataFrame({"produto": ["A", "B", "C"], "preco": [10.0, 20.0, 30.0]})
        resultado = processar_produtos(df)
        assert resultado.preco_medio == 20.0


class TestGarantirExtensao:
    """Testes para a função garantir_extensao."""

    def test_adiciona_extensao_csv(self) -> None:
        """Testa adição de extensão .csv."""
        resultado = garantir_extensao(Path("arquivo"), ".csv")
        assert resultado == Path("arquivo.csv")

    def test_adiciona_extensao_json(self) -> None:
        """Testa adição de extensão .json."""
        resultado = garantir_extensao(Path("resultado"), ".json")
        assert resultado == Path("resultado.json")

    def test_mantem_extensao_existente(self) -> None:
        """Testa que extensão existente é mantida."""
        resultado = garantir_extensao(Path("arquivo.csv"), ".csv")
        assert resultado == Path("arquivo.csv")

    def test_substitui_extensao_diferente(self) -> None:
        """Testa substituição de extensão diferente."""
        resultado = garantir_extensao(Path("arquivo.txt"), ".csv")
        assert resultado == Path("arquivo.csv")

    def test_normaliza_para_lowercase(self) -> None:
        """Testa que extensão é normalizada para lowercase."""
        resultado = garantir_extensao(Path("arquivo.CSV"), ".csv")
        assert resultado == Path("arquivo.csv")


class TestSalvarJson:
    """Testes para a função salvar_json."""

    def test_salva_json_corretamente(self, tmp_path: Path) -> None:
        """Testa salvamento de JSON."""
        resultado = ResultadoProcessamento(
            total_produtos=10,
            produto_mais_caro="Notebook",
            produto_mais_barato="Mouse",
            preco_medio=500.50,
        )
        caminho = tmp_path / "resultado.json"
        salvar_json(resultado, str(caminho))

        with open(caminho, encoding="utf-8") as f:
            dados = json.load(f)

        assert dados["total_produtos"] == 10
        assert dados["produto_mais_caro"] == "Notebook"
        assert dados["produto_mais_barato"] == "Mouse"
        assert dados["preco_medio"] == 500.50

    def test_json_utf8(self, tmp_path: Path) -> None:
        """Testa que caracteres especiais são salvos corretamente."""
        resultado = ResultadoProcessamento(
            total_produtos=1,
            produto_mais_caro="Café Açúcar",
            produto_mais_barato="Pão",
            preco_medio=10.0,
        )
        caminho = tmp_path / "resultado.json"
        salvar_json(resultado, str(caminho))

        with open(caminho, encoding="utf-8") as f:
            dados = json.load(f)

        assert dados["produto_mais_caro"] == "Café Açúcar"
        assert dados["produto_mais_barato"] == "Pão"


if __name__ == "__main__":
    pytest.main([__file__, "-v"])
