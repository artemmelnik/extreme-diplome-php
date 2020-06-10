<?php

namespace App\Models;

abstract class Model
{
    protected $db;

    public function __construct()
    {
        global $db;

        $this->db = $db;
    }

    public function all()
    {
        return $this->db->query('SELECT * FROM ' . static::$table . ' ORDER BY id DESC');
    }

    public function whereId(int $id)
    {
        $query = $this->db->query('SELECT * FROM ' . static::$table . ' WHERE id = :id', [
            'id' => $id
        ]);

        return $query[0] ?? null;
    }

    public function create(array $attributes)
    {
        $fields = array_keys($attributes);
        $vars = [];

        foreach ($fields as $field) {
            $vars[] = ':' . $field;
        }

        $sql = 'INSERT INTO ' . static::$table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $vars) . ')';

        $this->db->query($sql, $attributes);

        return $this->find($this->db->lastInsertId());
    }

    public function update(int $id, array $attributes)
    {
        $fields = '';

        $i = 1;
        foreach ($attributes as $key => $value) {

            if (count($attributes) === $i) {
                $fields .= "$key = '$value' ";
            } else {
                $fields .= "$key = '$value', ";
            }

            $i++;
        }

        $sql = 'UPDATE ' . static::$table . ' SET ' . $fields . ' WHERE id = ' . $id;

        $this->db->query($sql, $attributes);

        return $this->find($id);
    }

    public function delete(int $id)
    {
        return $this->db->query('DELETE FROM ' . static::$table . ' WHERE id = :id', [
            'id' => $id
        ]);
    }

    public function find(int $id)
    {
        $query = $this->db->query('SELECT * FROM ' . static::$table . ' WHERE id = :id LIMIT 1', [
            'id' => $id
        ]);

        return $query[0] ?? null;
    }

}
