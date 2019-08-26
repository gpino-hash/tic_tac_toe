<?php

namespace App\Http\Controllers;

use App\Board;
use App\Factory\ArrayFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;

/**
 * Class MatchController
 * @package App\Http\Controllers
 */
class MatchController extends Controller {

    /**
     * Returns a list of matches
     *
     * TODO it's mocked, make this work :)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function matches()
    {
        return response()->json($this->fakeMatches());
    }

    /**
     * Returns the state of a single match
     *
     * TODO it's mocked, make this work :)
     *
     * @param $board
     * @return \Illuminate\Http\JsonResponse
     */
    public function match(Board $board)
    {

        if (empty($board->id)) {
            throw new ModelNotFoundException('Board not found by ID ' . $board->id);
        }

        return response()->json($board);
    }

    /**
     * Makes a move in a match
     *
     * TODO it's mocked, make this work :)
     *
     * @param Board $board
     * @return \Illuminate\Http\JsonResponse
     */
    public function move(Board $board)
    {
        try {
            if ($board->winner == 0){
                $validator = \Validator::make(Input::get(), [
                    "position" => 'required|numeric'
                ]);
                if ($validator->fails()) return response()->json($validator->errors(), 422);
                $data = [];
                $boards = $board->board;
                $data['next'] = $board->next == Board::X ? Board::O : Board::X;
                $position = Input::get('position');
                if (!empty($boards[$position])) return response()->json(['error' => 'occupied position'], 422);
                $boards[$position] = $data['next'];
                $data['board'] = $boards;
                if ($this->hasWon($boards)) $data['winner'] = $data['next'];
                $board->update($data);
                return response()->json($board);
            }
        } catch (\Exception $exception) {
            response()->json($exception->getMessage(), 500);
        }


    }

    /**
     * Creates a new match and returns the new list of matches
     *
     * TODO it's mocked, make this work :)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        try {
            $board = Board::query();

            $board->create([
                'name' => 'Match '.($board->get()->last()['id']),
                'next' => 1,
            ]);
            return response()->json($board->get());
        } catch (\Exception $e) {
            response()->json($e->getMessage(), 500);
        }

    }

    /**
     * Deletes the match and returns the new list of matches
     *
     * TODO it's mocked, make this work :)
     *
     * @param Board $board
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Board $board)
    {
        $board->delete();
        return response()->json($this->fakeMatches());
    }

    /**
     * Creates a fake array of matches
     *
     * @return \Illuminate\Support\Collection
     */
    private function fakeMatches()
    {
        return Board::all();
    }

    /**
     * @param $spaces
     * @return bool
     */
    private function hasWon($spaces)
    {
        $factory = new ArrayFactory($spaces);
        // Check if three in a row in any direction
        if ($factory->maxSameRow() == 3) {
            return true;
        }
        if ($factory->maxSameColumn() == 3) {
            return true;
        }
        if ($factory->maxDiagonal() == 3) {
            return true;
        }
        return false;
    }

}