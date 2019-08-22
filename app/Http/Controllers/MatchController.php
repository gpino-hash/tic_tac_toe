<?php

namespace App\Http\Controllers;

use App\Board;
use App\Factory\ArrayFactory;
use Illuminate\Support\Facades\Input;

class MatchController extends Controller {

    public function index() {
        return view('index');
    }

    /**
     * Returns a list of matches
     *
     * TODO it's mocked, make this work :)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function matches() {
        return response()->json($this->fakeMatches());
    }

    /**
     * Returns the state of a single match
     *
     * TODO it's mocked, make this work :)
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function match(Board $id)
    {
        return response()->json($id);
    }

    /**
     * Makes a move in a match
     *
     * TODO it's mocked, make this work :)
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function move(Board $board)
    {

        if ($board->winner != 1 ){
            $data = [];
            $boards = $board->board;
            $data['next'] = $board->next == 1 ? 2 : 1;
            $position = Input::get('position');
            if (!empty($boards[$position])) return response()->json(['error' => 'posision ocupada']);
            $boards[$position] = $data['next'];
            $data['board'] = $boards;
            if ($this->hasWon($boards)) $data['winner'] = 1;
            $board->update($data);
            return response()->json($board);
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
        $board = Board::query();

        $board->create([
            'name' => 'Match '.($board->get()->last()['id']),
            'next' => 1,
        ]);
        return response()->json($board->get());
    }

    /**
     * Deletes the match and returns the new list of matches
     *
     * TODO it's mocked, make this work :)
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Board $id)
    {
        $id->delete();
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

    private function hasWon($spaces)
    {
        $factory = new ArrayFactory();
        // Check if three in a row in any direction
        if ($factory->maxSameRow($spaces) == 3) {
            return true;
        }
        /**if ($factory->maxSameColumn($spaces) == 3) {
            return true;
        }
        /*if ($factory->maxDiagonal($spaces) == 3) {
            return true;
        }*/
        return false;
    }

}