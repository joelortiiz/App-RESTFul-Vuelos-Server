<?php

class VuelosModel extends Basedatos {

    private $table;
    private $conexion;

    public function __construct() {
        $this->table = "vuelo";
        $this->conexion = $this->getConexion();
    }

    // Devuelve un array departamento
    public function getUnVuelo($nuvuel) {
        try {
            $sql = "SELECT * FROM $this->table WHERE identificador=?";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(1, $nuvuel);
            $sentencia->execute();
            $row = $sentencia->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row;
            }
            return "SIN DATOS";
        } catch (PDOException $e) {
            return "ERROR AL CARGAR.<br>" . $e->getMessage();
        }
    }

    public function getAll() {
        try {
            $sql = "SELECT 
                        v.identificador, 
                        ao.codaeropuerto AS 'aeropuertoorigen',  ao.nombre AS 'aeropuertonombreorigen',  ao.pais AS 'paisorigen', ad.codaeropuerto AS 'aeropuertodestino', 
                        ad.nombre AS 'aeronamedestino',  ad.pais AS 'paisdestino',  v.tipovuelo, COUNT(p.idpasaje) AS 'idpasaje' 
                    FROM vuelo v 
                    JOIN 
                        aeropuerto ao ON v.aeropuertoorigen = ao.codaeropuerto 
                    JOIN 
                        aeropuerto ad ON v.aeropuertodestino = ad.codaeropuerto 
                    LEFT JOIN 
                        pasaje p ON v.identificador = p.identificador 
                    GROUP BY 
                        v.identificador;";

            $statement = $this->conexion->query($sql);
            $allvuelos = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement = null;
            // Retorna el array de registros
            return $allvuelos;
        } catch (PDOException $e) {
            return "ERROR AL INTENTAR CARGAR TODOS LOS VUELOS.<br>" . $e->getMessage();
        }
    }

}
