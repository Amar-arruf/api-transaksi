<?php

namespace App\Services;
use App\Repository\CustomRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerService
{
    protected $repository;

    public function __construct(CustomRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|numeric', 
                'address' => 'required|string|max:255',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $validator->validated();

            // validation phone number with app.abstractapi.com
            $phone = $data['phone'];
            $apiKey = env('ABSTRACT_API_KEY', ''); 
            $url = "https://phonevalidation.abstractapi.com/v1/?api_key={$apiKey}&phone={$phone}";

            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $url);

           $validationPhone = json_decode($response->getBody(), true);
        

            if (isset($validationPhone['valid'])  && !$validationPhone['valid']) {
                return response()->json(['error' => 'Invalid phone number'], 422);
            }



            $customer = $this->repository->create($data);
            return response()->json($customer, 201);
        
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'phone' => 'sometimes|required|numeric',
                'address' => 'sometimes|required|string|max:255',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $validator->validated();
            // validation phone number with app.abstractapi.com
            $phone = $data['phone'];
            $apiKey = env('ABSTRACT_API_KEY', ''); 
            $url = "https://phonevalidation.abstractapi.com/v1/?api_key={$apiKey}&phone={$phone}";

            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $url);
            $validationPhone = json_decode($response->getBody(), true);
        

            if (isset($validationPhone['valid'])  && !$validationPhone['valid']) {
                return response()->json(['error' => 'Invalid phone number'], 422);
            }

            $customer = $this->repository->update( (int) $id, $data);
            return response()->json($customer, 200);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

  
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function all()
    {
        return $this->repository->all();
    }
}