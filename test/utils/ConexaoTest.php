<?php

namespace test\utils;

use Exception;
use PHPUnit\Framework\TestCase;
use utils\Conexao;

class ConexaoTest extends TestCase
{
    public function testConectarBanco(): void
    {
        try {
            $conexao = Conexao::getEntityManager();
            self::assertNotNull($conexao);
        } catch (Exception $e) {
            self::markTestSkipped('Banco indisponivel para o teste de conexao: ' . $e->getMessage());
        }
    }
}
