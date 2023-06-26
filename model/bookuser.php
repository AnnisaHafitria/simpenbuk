<?php

Class BookUser
{
    private $fillable = [
        'user_id',
        'book_id',
        'quantity',
        'borrow_date',
        'due_date',
        'date_return',
        'penalties',
        'borrow_note',
        'return_note',
        'return_quantity',
        'status'
    ];

    public function create($data)
    {
        $sql = 'INSERT INTO book_user ({col}) VALUES{value}';
        $col = '';
        $val = '';

        foreach($this->fillable as $key => $value) {
            $col .= $value;

            if ($key < count($this->fillable) - 1) {
                $col .= ",";
            }
        }

        foreach($data as $key => $user) {
            $i = 0;
            $val .= '(';
            foreach ($user as $key2 => $value2) {
                $val .= $value2 != null ? '"'.$user[$key2].'"' : 'NULL';
                if ($i < count($user) - 1) {
                    $val .= ',';
                }
                $i++;
            }
            $val .= ')';

            if ($key < count($data) - 1) {
                $val .= ',';
            }
        }

        $sql = str_replace('{col}', $col, $sql);
        $sql = str_replace('{value}', $val, $sql);

        return $sql;
    }

    public function update($data, $id)
    {
        $sql = 'UPDATE book_user SET {col} WHERE id = {id}';
        $col = '';

        $cols = $this->fillable;

        foreach($cols as $key => $value) {
            $col .= $value."='".$data[$value]."'";

            if ($key < count($cols) - 1) {
                $col .= ",";
            }
        }
        
        $col .= ',updated_at=NOW()';
        $sql = str_replace('{col}', $col, $sql);
        $sql = str_replace('{id}', $id, $sql);
        return $sql;
    }
}