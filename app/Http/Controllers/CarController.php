<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Car::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Car::create($request->all());
        return response()->json(['message' => 'Car created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Car::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $car = Car::find($id);
        $car->update($request->all());
        return response()->json(['message' => 'Car updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $car = Car::find($id);
        $car->delete();
        return response()->json(['message' => 'Car deleted successfully'], 200);
    }
    public function estimate(Request $request)
    {

        $similarCars = Car::where('marque', 'LIKE', '%' . $request->marque . '%')
            ->where('modele', 'LIKE', '%' . $request->modele . '%')
            ->where('annee', 'LIKE', '%' . $request->annee . '%')
            ->get();

        if ($similarCars->isEmpty()) {
            return response()->json(['message' => 'No similar cars found'], 404);
        }

        $totalPrice = $similarCars->sum('prix');
        $estimatedPrice = $totalPrice / $similarCars->count();

        return response()->json(['estimatedPrice' => $estimatedPrice, 'totalPrice' => $totalPrice, 'count' => $similarCars->count()]);
    }
}
