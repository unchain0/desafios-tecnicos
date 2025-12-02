"""
Desafio 2 - Compactação de String

Implementação da função compactar_string que substitui sequências de
caracteres repetidos consecutivos por caractere+quantidade.
"""

import argparse


def compactar_string(s: str) -> str:
    """
    Compacta uma string substituindo sequências de caracteres repetidos
    consecutivos por caractere+quantidade.

    Args:
        s: String a ser compactada.

    Returns:
        String compactada no formato caractere+quantidade.

    Examples:
        >>> compactar_string('aaabbcaaa')
        'a3b2c1a3'
        >>> compactar_string('abc')
        'a1b1c1'
        >>> compactar_string('')
        ''
    """
    if not s:
        return ""

    resultado: list[str] = []
    char_atual = s[0]
    contagem = 1

    for char in s[1:]:
        if char == char_atual:
            contagem += 1
        else:
            resultado.append(f"{char_atual}{contagem}")
            char_atual = char
            contagem = 1

    resultado.append(f"{char_atual}{contagem}")

    return "".join(resultado)


def main():
    """CLI simples para compactação de strings."""
    parser = argparse.ArgumentParser(
        description="Compacta uma string substituindo caracteres repetidos consecutivos."
    )
    parser.add_argument("string", help="String a ser compactada")
    args = parser.parse_args()

    resultado = compactar_string(args.string)
    print(f"Original:   '{args.string}'")
    print(f"Compactada: '{resultado}'")


if __name__ == "__main__":
    main()
