<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Prize;
use App\Http\Requests\PrizeRequest;
use Exception;
use Illuminate\Http\Request;


class PrizesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $prizes = Prize::all();
        return view('prizes.index', [
            'prizes' => $prizes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $totalVal = Prize::sum('probability');
        $message = null;
        $success = null;

        if ($totalVal >= 100) {
            $success = 0;
            $message = 'Prizes probability weightage alredy exceeds to 100%';
            
        } elseif ($totalVal < 100) {

            $remainVal = 100 - $totalVal;
            $success = 1;
            $message = "Can Add the prizes with probability upto  $remainVal %.";
        }

        session()->flash('toast', [
            'success' => $success,
            'message' => $message,
        ]);

        return view('prizes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PrizeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PrizeRequest $request)
    {

        $totalVal = Prize::sum('probability');
        $newProb  = $request['probability'];

        //edit case -> get new total probability val
        if ($request['id'] !== null) {

            $currentPrize = Prize::findOrFail($request['id']);
            $totalVal = $totalVal - $currentPrize['probability'];
        }

        //Validate if the new probability added to total not exceed limit 100 
        if (($newProb + $totalVal) > 100) {

            session()->flash('toast', [
                'success' => 0,
                'message' => 'Total Probability can not exceed 100%'
            ]);

            return back()
                ->withErrors('Total Probability can not exceed 100%')
                ->withInput();

        } 

        $validateData = $request->validate([
            'id'             => 'nullable|integer|exists:prizes,id',
            'title'          => 'required|string|max:255',
            'probability'    => 'required|numeric|min:0|max:100'
        ]);

        $prize = Prize::updateOrCreate(
            [
                'id' => $request['id']
            ],
            $validateData
        );

        $message = $request['id'] ? 'Prize Updated Successfully' : 'Prize Added Successfull';

        session()->flash('toast', [
            'success' => 1,
            'message' => $message
        ]);

        return redirect()->route('prizes.index');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        try {

            $prize = Prize::findOrFail($id);

            session()->flash('toast', [
                'success' => 1,
                'message' => 'Prize fetched successfully'
            ]);

            return view('prizes.create', ['prize' => $prize]);

        } catch (\Exception $e) {

            session()->flash('toast', [
                'success' => 0,
                'message' => 'Something went wrong while fetching data, Please Try Again !'
            ]);

            return back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PrizeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PrizeRequest $request, $id)
    {
      //code here
    }

    /**weigh
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {

        try {

            Prize::findOrFail($id)->delete();

            session()->flash('toast', [
                'success' => 1,
                'message' => 'Prize deleted successfully.'
            ]);

            return redirect()->route('prizes.index');
            
        } catch (\Exception $e) {

            session()->flash('toast', [
                'success' => 0,
                'message' => 'Opps, Something went wrong while deleting, Please try again'
            ]);

            return redirect()->route('prizes.index');
        }
    }


    public function simulate(Request $request)
    {

        $validProbability = Prize::sum('probability');

        //Case - total prize prob. is not 100% return error
        if (abs($validProbability) !== 100.0) {

            session()->flash('toast', [
                'success' => 0,
                'message' => 'The Total prize probability must equal 100%.'
            ]);

            return back()->withErrors('The Total prize probability is not upto 100%');
        }

        //if total prob. is 100%

        $validatedData = $request->validate([
            'number_of_prizes' => 'required|min:0|integer'
        ]);

        $participants = $request['number_of_prizes'];
        $prizes = Prize::all();
        $results = [];

        //loop of each participants
        for ($i = 1; $i <= $participants; $i++) {

            //pick random number from range 100
            $randNo = rand(1, 100);

            //set cummulative range for prize
            $cummulativeSum = 0; 

            //check random number prize range fall
            foreach ($prizes as $prize) {

                $cummulativeSum = $cummulativeSum + $prize['probability'];

                if ($randNo <= $cummulativeSum) {

                    if (isset($results[$prize['title']])) {
                        $results[$prize['title']]++;
                    } else {
                        $results[$prize['title']] = 1;
                    }
                    break;
                }
            }
        }

        //save the awarded count to prizes table
        foreach ($results as $title => $awardedCount) {
            Prize::where('title', $title)->update(['awarded' => $awardedCount]);
        }

        // Calculate results percentages
        $totalResults = array_sum($results);
        $resultsPercentage = [];

        foreach ($results as $key => $value) {
            $resultsPercentage[$key] = round(($value / $totalResults) * 100, 2);
        }
        
        //sort base on awarded counts
        asort($resultsPercentage);

        //set probability value for graph
        $graphProb = Prize::pluck('probability', 'title')->sort();


        return view('prizes.index', [
            'prizes'            => Prize::all(),
            'graphProb'         => $graphProb,
            'resultsPercentage' => $resultsPercentage,
        ]);
    }

    public function reset()
    {

        Prize::query()->update(['awarded' => null]);
        
        return redirect()->route('prizes.index')->with('toast', [
            'success' => 1,
            'message' => 'Form Reset Successfully'
        ]);

    }

}
