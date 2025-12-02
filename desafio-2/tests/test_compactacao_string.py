"""
Testes unitários para a função compactar_string.
"""

import sys
from pathlib import Path

import pytest

sys.path.insert(0, str(Path(__file__).parent.parent))

from compactacao_string import compactar_string


class TestCompactarString:
    """Testes para a função compactar_string."""

    # Casos normais
    def test_caracteres_repetidos_consecutivos(self):
        """Testa sequências de caracteres repetidos."""
        assert compactar_string("aaabbcaaa") == "a3b2c1a3"

    def test_sem_repeticoes(self):
        """Testa string sem caracteres repetidos consecutivos."""
        assert compactar_string("abc") == "a1b1c1"

    def test_todos_iguais(self):
        """Testa string com todos caracteres iguais."""
        assert compactar_string("aaaaa") == "a5"

    def test_alternados(self):
        """Testa caracteres alternados."""
        assert compactar_string("ababab") == "a1b1a1b1a1b1"

    # Casos de borda
    def test_string_vazia(self):
        """Testa string vazia."""
        assert compactar_string("") == ""

    def test_caractere_unico(self):
        """Testa string com apenas um caractere."""
        assert compactar_string("a") == "a1"

    def test_dois_caracteres_iguais(self):
        """Testa string com dois caracteres iguais."""
        assert compactar_string("aa") == "a2"

    def test_dois_caracteres_diferentes(self):
        """Testa string com dois caracteres diferentes."""
        assert compactar_string("ab") == "a1b1"

    # Caracteres especiais
    def test_numeros(self):
        """Testa string contendo números."""
        assert compactar_string("111223") == "132231"

    def test_caracteres_especiais(self):
        """Testa string com caracteres especiais."""
        assert compactar_string("!!!@@#") == "!3@2#1"

    def test_espacos(self):
        """Testa string com espaços."""
        assert compactar_string("   aa  ") == " 3a2 2"

    def test_misturado(self):
        """Testa string com letras, números e caracteres especiais."""
        assert compactar_string("aaa111!!!") == "a313!3"

    # Maiúsculas e minúsculas
    def test_case_sensitive(self):
        """Testa que maiúsculas e minúsculas são tratadas como diferentes."""
        assert compactar_string("AAAaaa") == "A3a3"

    def test_mixed_case(self):
        """Testa alternância entre maiúsculas e minúsculas."""
        assert compactar_string("AaAa") == "A1a1A1a1"

    # Strings longas
    def test_string_longa(self):
        """Testa string mais longa."""
        entrada = "a" * 100 + "b" * 50 + "c" * 25
        esperado = "a100b50c25"
        assert compactar_string(entrada) == esperado

    # Unicode
    def test_unicode(self):
        """Testa caracteres unicode."""
        assert compactar_string("àààééô") == "à3é2ô1"

    def test_emojis(self):
        """Testa emojis."""
        assert compactar_string("😀😀😀") == "😀3"


if __name__ == "__main__":
    pytest.main([__file__, "-v"])
