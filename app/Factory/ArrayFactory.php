<?php
namespace App\Factory;

use App\Exceptions\MaxArrayException;
use Illuminate\Support\Arr;

/**
 * Class ArrayFactory
 * @package App\Factory
 */
class ArrayFactory
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private $collection, $max_count, $array;

    /**
     * ArrayFactory constructor.
     * @param array $collection
     */
    public function __construct(array $collection)
    {
        if ( count($collection) != 9 ) throw MaxArrayException::countArray();

        $this->collection = collect($collection)->split(3);
        $this->max_count = 0;
        # Changing collection to array
        $this->array = Arr::collapse([
            [array_values($this->collection->toArray()[0])],
            [array_values($this->collection->toArray()[1])],
            [array_values($this->collection->toArray()[2])]
        ]);
    }

    /**
     * row check
     * @return int
     */
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
                $row->reject(function ($value) use ($row_number) {
                    return $value == $row_number && $value != 0;
                });
            }
        }

        return $max_count;

    }

    /**
     * column check
     * @return mixed
     */
    public function maxSameColumn()
    {
        $data = [];
        $col = [];

        if (!$this->collection->isEmpty()) {

            # Organizando el array a columnas
            foreach ($this->array as $key => $value) {
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

    /**
     * Diagonal check
     * @return mixed
     */
    public function maxDiagonal()
    {
        if (!$this->collection->isEmpty()) {

            # Organizing the array to columns
            foreach ($this->array as $key => $value) {
                foreach ($value as $k => $v) {
                    $data[$k][] = $v;
                }
            }
            # Checking the validation
            foreach ($data as $k => $v) {
                # Diagonal
                $col1[$k] = array_fill_keys([$k],$v[$k]);
            }

            # Inverting the array
            $data2 = array_reverse($data);

            # Checking the validation
            foreach ($data2 as $k => $v) {
                # Diagonal reverse
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