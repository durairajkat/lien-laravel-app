<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class StateController for state Management
 * @package App\Http\Controllers
 */
class StateController extends Controller
{
    /**
     * Show a list of States
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $search = request()->get('stateSearch');
            $query = State::orderBy('code', 'ASC');
            if (is_null($search)) {
                $states = $query->paginate(20);
            } else {
                $states = $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('short_code', 'LIKE', '%' . $search . '%')
                    ->paginate(20);
            }
            return view('state.list', [
                'states' => $states
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Add State
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addState(Request $request)
    {
        try {
            $state = State::where('name', $request->state)->first();
            if ($state == '') {
                $stateCode = State::where('code', $request->code)->first();
                if ($stateCode == '') {
                    $stateShortCode = State::where('short_code', $request->short_code)->first();
                    if ($stateShortCode == '') {
                        $state = new State();
                        $state->name = $request->state;
                        $state->code = $request->code;
                        $state->short_code = $request->short_code;
                        $state->save();

                        return response()->json([
                            'status' => true,
                            'type' => 'success',
                            'message' => 'State Added'
                        ], 201);
                    } else {
                        return response()->json([
                            'status' => false,
                            'type' => 'error',
                            'message' => 'State short code is already in use'
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'type' => 'error',
                        'message' => 'State code is already in use'
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'type' => 'error',
                    'message' => 'State name is already in use'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * Delete a State
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse3
     */
    public function deleteState(Request $request)
    {
        try {
            $state = State::findOrFail($request->id);
            $state->delete();
            return response()->json([
                'status' => true,
                'type' => 'success',
                'message' => 'State Added'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * Edit STATE
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editState(Request $request)
    {
        try {
            $state = State::where('name', $request->state)->where('id', '!=', $request->id)->first();
            if ($state == '') {
                $stateCode = State::where('code', $request->code)->where('id', '!=', $request->id)->first();
                if ($stateCode == '') {
                    $stateShortCode = State::where('short_code', $request->short_code)->where('id', '!=', $request->id)->first();
                    if ($stateShortCode == '') {
                        $state = State::findOrFail($request->id);
                        $state->name = $request->state;
                        $state->code = $request->code;
                        $state->short_code = $request->short_code;
                        $state->update();

                        return response()->json([
                            'status' => true,
                            'type' => 'success',
                            'message' => 'State edited successfully'
                        ], 201);
                    } else {
                        return response()->json([
                            'status' => false,
                            'type' => 'error',
                            'message' => 'State short code is already in use'
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'type' => 'error',
                        'message' => 'State code is already in use'
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'type' => 'error',
                    'message' => 'State name is already in use'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $exception->getMessage()
            ], 200);
        }
    }
}
