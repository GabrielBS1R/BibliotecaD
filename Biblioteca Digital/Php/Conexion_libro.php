<?php

class DatabaseRepository {
    private $conn;

    public function __construct() {
        $serverName = 'DESKTOP-NQ8VIDM';
        $connectionOptions = [
            'Database' => 'Biblioteca',
            'Uid' => 'sa',
            'PWD' => 'Mazda787B',
            'CharacterSet' => 'UTF-8'
        ];

        $this->conn = sqlsrv_connect($serverName, $connectionOptions);
        
        if ($this->conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function fetchAllBooks() {
        $query = "SELECT * FROM libros";
        $stmt = sqlsrv_query($this->conn, $query);
        
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        
        $books = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $books[] = $row;
        }
        return $books;
    }

    public function insertBook($titulo, $fecha_publicacion, $isbn) {
        $query = "INSERT INTO libros (titulo, fecha_publicacion, isbn) VALUES (?, ?, ?)";
        $params = [$titulo, $fecha_publicacion, $isbn];
        $stmt = sqlsrv_query($this->conn, $query, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        return true;
    }

    public function executeQuery($query, $params = []) {
        $stmt = sqlsrv_query($this->conn, $query, $params);
        
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        
        $results = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }
        return $results;
    }
}

// Ejemplo de uso
//$db = new DatabaseRepository();
//$books = $db->fetchAllBooks();
//print_r($books);
//$db->insertBook('Mi Libro', '2024-03-20', '123-456-789');

?>