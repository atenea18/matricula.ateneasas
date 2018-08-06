<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 3/08/2018
 * Time: 3:12 PM
 */

namespace App\Helpers\Utils;


class GenerateRating
{

    /**
     * Recibe un vector de estudiantes con datos básicos:
     * average, tav, name, id para generar el vector de puestos correspondiente
     */
    public static function createVectorRating($arrayStudentAverage)
    {

        #Array donde se va almacenar objetos de estudiantes de arrayStudentAverage, pero con una estructra un poco modificada
        $vectorOfStudents = array();

        #En este vector se va a guardar el número de asignaras evaluada por cada estudiante
        $vectorNumberAsignatures = array();

        #
        $count = 0;

        foreach ($arrayStudentAverage as $key => $value) {
            $vectorStudent = array(
                'id' => $value->id,
                'last_name' => $value->last_name,
                'name' => $value->name,
                'average' => $value->average,
                'tav' => $value->tav
            );

            #Se guarda la nueva estructura en el vector por cada estudiante
            $vectorOfStudents[$key] = $vectorStudent;

            # Se guarda el tav de asignatura del estudiante i o count..
            $vectorNumberAsignatures[$key] = $value->tav;

        }

        #Obtengo y almaceno el número maximo de asignaturas evaluadas
        if (count($vectorNumberAsignatures)) {
            if (max($vectorNumberAsignatures) > 0) {
                $numberMaxOfAsignatures = max($vectorNumberAsignatures);
            } else {
                $numberMaxOfAsignatures = 1;
            }
        } else {
            $numberMaxOfAsignatures = 1;
        }


        #Este es un nuevo vector donde se va a guardar los mismo estudiantes pero con el promedio levemente modificado
        $vectorOfStudentsAux = array();
        foreach ($vectorOfStudents as $value) {

            #Esta formula da como resultado un promedio auxiliar,
            #Nos soluciona el problema de aquellos estudiantes que tienen un promedio alto pero con menor
            #asignaturas evaluadas
            $averageAux = (($value['average'] * $value['tav']) / $numberMaxOfAsignatures);

            $vectorStudent = array(
                'id' => $value['id'],
                'last_name' => $value['last_name'],
                'name' => $value['name'],
                'averageAux' => $averageAux,
                'average' => $value['average'],
                'tav' => $value['tav']
            );
            #usamos el id de estudiante como el indice del vector
            $vectorOfStudentsAux[$value['id']] = $vectorStudent;
        }

        $vectorOfStudentsAux = self::sortVector($vectorOfStudentsAux, 'averageAux', true);
        $vectorOfStudentsAux = self::generateRating($vectorOfStudentsAux);
        $vectorOfStudentsAux = self::sortVector($vectorOfStudentsAux, 'rating', false);
        return $vectorOfStudentsAux;
    }


    private static function sortVector($toOrderArray, $field, $inverse = false)
    {
        $position = array();
        $newRow = array();
        foreach ($toOrderArray as $key => $row) {
            $position[$key] = $row[$field];
            $newRow[$key] = $row;
        }
        if ($inverse) {
            arsort($position);
        } else {
            asort($position);
        }
        $returnArray = array();
        foreach ($position as $key => $pos) {
            $returnArray[] = $newRow[$key];
        }
        return $returnArray;
    }

    private static function generateRating($vectorOfStudentsAux)
    {
        #variable con la que voy asignar el puesto de cada estudiante
        $countAux = 1;

        #promedio auxiliar que comienza en cero
        $averageAux = 0;
        $vectorRating = array();

        #Vamos a recorrer el vector auxiliar de estudiante, ya esta ordenado segun al promdedio modificado
        foreach ($vectorOfStudentsAux as $key => $value) {

            #Si es mayor
            if ($value['averageAux'] > $averageAux) {
                $vectorOfStudent = array(
                    'id' => $value['id'],
                    'last_name' => $value['last_name'],
                    'name' => $value['name'],
                    'rating' => $countAux,
                    'average' => $value['average'],
                    'tav' => $value['tav']
                );
                $averageAux = $value['averageAux'];
                $vectorRating[$value['id']] = $vectorOfStudent;
                $countAux++;
            }
            #Si es igual
            if ($value['averageAux'] == $averageAux) {
                $vectorOfStudent = array(
                    'id' => $value['id'],
                    'last_name' => $value['last_name'],
                    'name' => $value['name'],
                    'rating' => $countAux - 1,
                    'average' => $value['average'],
                    'tav' => $value['tav']
                );
                $averageAux = $value['averageAux'];
                $vectorRating[$value['id']] = $vectorOfStudent;
            }
            #Si es menor
            if ($value['averageAux'] < $averageAux) {
                $vectorOfStudent = array(
                    'id' => $value['id'],
                    'last_name' => $value['last_name'],
                    'name' => $value['name'],
                    'rating' => $countAux,
                    'average' => $value['average'],
                    'tav' => $value['tav']
                );
                $averageAux = $value['averageAux'];
                $vectorRating[$value['id']] = $vectorOfStudent;
                $countAux++;
            }

        }

        return $vectorRating;
    }

}