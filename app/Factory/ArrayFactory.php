<?php
namespace App\Factory;

use Illuminate\Support\Arr;

class ArrayFactory
{
    private $collection, $max_count;

    public function __construct(array $collection)
    {
        $this->collection = collect($collection)->split(3);
        $this->max_count = 0;
    }

    public function maxSameRow()
    {
        $max_count = 0;

        if (!$this->collection->isEmpty()) {
            foreach ($this->collection as $row){
                $row_number = head($row);
                $row_count = $row->filter(function ($value) use ($row_number) {
                    return $value == head($row_number) && $value != 0;
                })->count();

                if ($row_count > $max_count) {
                    // Keep count if greatest
                    $max_count = $row_count;
                }
                // Discard squares on row once counted
                $row = $row->reject(function ($value) use ($row_number) {
                    return $value == $row_number && $value != 0;
                });
            }
        }

        return $max_count;

    }

    public function maxSameColumn()
    {
        $data = [];

        if (!$this->collection->isEmpty()) {
            # Cambiando collection a array
            $arry = Arr::collapse([
                [array_values($this->collection->toArray()[0])],
                [array_values($this->collection->toArray()[1])],
                [array_values($this->collection->toArray()[2])]
            ]);
            # Organizando el array a columnas
            foreach ($arry as $key => $value) {
                foreach ($value as $k => $v) {
                    $data[$k][] = $v;
                }
            }
            foreach ($data as $k => $v) {
                $col[] = collect($v)->filter(function ($value) use ($v) {
                    $head = head($v);
                    return $value == $head && $value != 0;
                })->count();
            }

            return max($col);
        }
    }

    public function maxDiagonal()
    {
        if (!$this->collection->isEmpty()) {
            # Cambiando collection a array
            $arry = Arr::collapse([
                [array_values($this->collection->toArray()[0])],
                [array_values($this->collection->toArray()[1])],
                [array_values($this->collection->toArray()[2])]
            ]);
            # Organizando el array a columnas
            foreach ($arry as $key => $value) {
                foreach ($value as $k => $v) {
                    $data[$k][] = $v;
                }
            }
            #Comprobando la validacion
            foreach ($data as $k => $v) {
                # Diagonal
                $col1[$k] = array_fill_keys([$k],$v[$k]);
            }

            $data2 = array_reverse($data);
            #Comprobando la validacion
            foreach ($data2 as $k => $v) {
                # Diagonal
                $col2[$k] = array_fill_keys([$k],$v[$k]);
            }

            $col1 = Arr::collapse($col1);
            $col1 = collect($col1)->filter(function ($value) use ($col1) {
                $head = head($col1);
                return $value == $head && $value != 0;
            })->count();

            $col2 = Arr::collapse($col2);
            $col2 = collect($col2)->filter(function ($value) use ($col2) {
                $head = head($col2);
                return $value == $head && $value != 0;
            })->count();
            return max([$col1, $col2]);

        }
    }

}