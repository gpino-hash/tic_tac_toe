<?php
namespace App\Factory;

use Illuminate\Support\Arr;

class ArrayFactory
{
    public function maxSameRow($remainder)
    {
        $c = collect($this->cube($remainder));
        foreach ($c as $row) {
            $rows = collect($row);
            $count_x = $rows->filter(function ($value) {
                return $value == 1;
            })->count();

            $count_y = $rows->filter(function ($value) {
                return $value == 2;
            })->count();
            $max = max($count_x, $count_y);
            if ($max == 3) return $max;
        }

    }

    public function maxSameColumn($remainder)
    {
        $c = $this->cube($remainder);
        $value = array_column($remainder, 0);

        dd($value);

    }
    public function maxDiagonal($remainder,$size = 3)
    {
        $remainder = collect($remainder);
        $remainder = $remainder->filter(function ($value) use ($size) {
            return $value[0] == $value[1] || $value[0] + $value[1] == $size + 1;
        });
        $max_count = 0;
        // Check diagonals relative to every square (no discarding)
        foreach ($remainder as $square) {
            $negative_diagonal_count = 0;
            // Positive slope, count squares to left and below
            $next_square = $square;
            while (!empty($next_square)) {
                $negative_diagonal_count = $negative_diagonal_count + 1;
                $next_square = $remainder->first(function ($value) use ($next_square) {
                    return $value[0] == $next_square[0] + 1 && $value[1] == $next_square[1] - 1;
                });
            }
            $next_square = $square;
            // Positive slope, count squares to right and above
            $negative_diagonal_count = $negative_diagonal_count - 1;
            while (!empty($next_square)) {
                $negative_diagonal_count = $negative_diagonal_count + 1;
                $next_square = $remainder->first(function ($value) use ($next_square) {
                    return $value[0] == $next_square[0] - 1 && $value[1] == $next_square[1] + 1;
                });
            }
            $positive_diagonal_count = 0;
            // Negative slope, count squares to right and below
            $next_square = $square;
            while (!empty($next_square)) {
                $positive_diagonal_count = $positive_diagonal_count + 1;
                $next_square = $remainder->first(function ($value) use ($next_square) {
                    return $value[0] == $next_square[0] + 1 && $value[1] == $next_square[1] + 1;
                });
            }
            // Negative slope, count squares to left and above
            $next_square = $square;
            $positive_diagonal_count = $positive_diagonal_count - 1;
            while (!empty($next_square)) {
                $positive_diagonal_count = $positive_diagonal_count + 1;
                $next_square = $remainder->first(function ($value) use ($next_square) {
                    return $value[0] == $next_square[0] - 1 && $value[1] == $next_square[1] - 1;
                });
            }
            if ($positive_diagonal_count > $max_count) {
                $max_count = $positive_diagonal_count;
            }
            if ($negative_diagonal_count > $max_count) {
                $max_count = $negative_diagonal_count;
            }
        }

        return $max_count;
    }

    function cube($remainder) {
        $one = array_slice($remainder, 0, 3);
        $two = array_slice($remainder, 2, 3);
        $three = array_slice($remainder, 6, 3);
        return Arr::collapse([[$one], [$two], [$three]]);
    }
}