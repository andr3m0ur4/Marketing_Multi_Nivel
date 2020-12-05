<?php

    function listar($id, $limite) {
        $lista = [];
        global $pdo;

        $sql = "SELECT * FROM usuarios WHERE id_pai = :id_pai";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(':id_pai', $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $lista = $sql->fetchAll(PDO::FETCH_OBJ);

            foreach ($lista as $chave => $usuario) {
                $lista[$chave]->filhos = [];

                if ($limite > 0) {
                    $lista[$chave]->filhos = listar($usuario->id, $limite - 1);
                }
            }
        }

        return $lista;
    }

    function exibir($array) {
        echo '<ul>';

        foreach ($array as $usuario) {
            echo '<li>';
            echo "{$usuario->nome} (" . count($usuario->filhos) . " usuários cadastrados)";

            if (count($usuario->filhos) > 0) {
                exibir($usuario->filhos);
            }

            echo '</li>';
        }

        echo '</ul>';
    }