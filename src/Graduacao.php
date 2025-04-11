<?php

namespace Uspdev\Forms;

use Uspdev\Replicado\DB;
use Uspdev\Replicado\Graduacao as GraduacaoReplicado;

class Graduacao extends GraduacaoReplicado
{
    /*

    * Método para obter as disciplinas ativas de graduação
    *
    * Derivado do método Uspdev\Replicado\Graduacao::obterDisciplinas
    * Adicionado verificação de disciplina ativa e limite de disciplinas
    *
    * @param String|Array $coddis
    * @param int $limit
    * @return array()
    */
    public static function procurarDisciplinas($coddis, $limit = null)
    {
        if (is_array($coddis)) {
            $coddisCondition = [];
            foreach ($coddis as $sgldis) {
                $coddisCondition[] = "D1.coddis LIKE '$sgldis%'";
            }
            $coddisCondition = implode(' OR ', $coddisCondition);    
        } else {
            $coddisCondition = "D1.coddis LIKE '$coddis%'";
        }

        $query = "SELECT D1.* FROM DISCIPLINAGR AS D1 WHERE $coddisCondition
                    AND D1.verdis = (
                    SELECT MAX(D2.verdis) 
                    FROM DISCIPLINAGR AS D2 
                    WHERE D2.coddis = D1.coddis
                ) ";

        if ($limit) {
            $query .= "LIMIT $limit";
        }

        $query .= "AND D1.dtadtvdis IS NULL 
                   AND D1.dtaatvdis IS NOT NULL
                   ORDER BY D1.coddis ASC";

        return DB::fetchAll($query);
    }
}