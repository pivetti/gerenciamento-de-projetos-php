<?php

namespace dao;

use Exception;
use model\GenericModel;
use utils\Conexao;

abstract class GenericDAO
{
    protected static string $modelClass;

    public static function salvar(GenericModel $model): GenericModel
    {
        $em = Conexao::getEntityManager();

        try {
            $em->beginTransaction();
            $em->persist($model);
            $em->flush();
            $em->commit();

            return $model;
        } catch (Exception $ex) {
            if ($em->getConnection()->isTransactionActive()) {
                $em->rollback();
            }

            throw new Exception('Falha ao salvar os dados. ' . $ex->getMessage());
        }
    }

    public static function listar(): array
    {
        try {
            return self::getRepository()->findAll();
        } catch (Exception $ex) {
            throw new Exception('Falha ao listar os dados. ' . $ex->getMessage());
        }
    }

    public static function deletar(GenericModel $model): void
    {
        $em = Conexao::getEntityManager();

        try {
            $em->beginTransaction();
            $em->remove($model);
            $em->flush();
            $em->commit();
        } catch (Exception $ex) {
            if ($em->getConnection()->isTransactionActive()) {
                $em->rollback();
            }

            throw new Exception('Falha ao deletar os dados. ' . $ex->getMessage());
        }
    }

    public static function buscarId(GenericModel $model): ?object
    {
        try {
            return self::getRepository()->find($model->getId());
        } catch (Exception $ex) {
            throw new Exception('Falha ao buscar pelo ID. ' . $ex->getMessage());
        }
    }

    protected static function buscarPor(array $criteria): array
    {
        try {
            return self::getRepository()->findBy($criteria);
        } catch (Exception $ex) {
            throw new Exception('Falha ao buscar os dados. ' . $ex->getMessage());
        }
    }

    protected static function getRepository(): object
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(static::$modelClass);
    }
}
