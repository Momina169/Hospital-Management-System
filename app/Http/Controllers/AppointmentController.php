<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\Staff;
use App\Models\Deppartment;
use App\Models\docterData;
use App\Models\checkout;
use Illuminate\Http\Request;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Validation\Rule;
use session;

class AppointmentController extends Controller
{
    public function getStaffDeppartment()
    {
        $staffDeppartmentBreakdown = Staff::select('deppartment', DB::raw('count(*) as count'))
        ->groupBy('deppartment')
        ->get(); 
        return response()->json($staffDeppartmentBreakdown);
    }

    public function getDoctorBreakdown()
    {
        $doctorBreakdown = docterData::select('DoctorName', DB::raw('count(*) as count'))
        ->groupBy('DoctorName')
        ->get();
        return response()->json($doctorBreakdown);
    }
   

    public function index()
    {
        $data['appointList']=Appointment::all();
        return view('appoint.index',$data);
    }

    
    public function create()
    {
        
        return view('/appoint.create');
    }

    public function checkEmailValidity(Request $request)
    {
        $email = $request->input('email');
       
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
        ]); 
    
        $exists = Appointment::where('email', $email)->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This email address is already in use.'
            ]);
        } else {
            $departmentBreakdown = Deppartment::get();
            
            return response()->json([
                'success' => true,
                'message' => 'This email address is available.',
                'departmentBreakdown' => $departmentBreakdown,
            ]);
        }
    }
    

    public function store(Request $request)
    {
        request()->validate([

            'email' => 'required|email|unique:appointments'
        ]);
      

        $appoint=new Appointment;
        $appoint->firstname=$request->firstname;
        $appoint->lastname=$request->lastname;
       $appoint->dateofbirth=$request->dateofbirth;
       $appoint->gender=$request->gender;
       $appoint->email=$request->email;

       $emailExists = Appointment::where('email', $request->email)->exists();

       if ($emailExists) {
           return redirect()->back()->withInput()->withErrors(['email' => 'The email address is already in use.']);
       }
       $appoint->zipcode=$request->zipcode;
       $appoint->phonenumber=$request->phonenumber;
       $appoint->streetaddress=$request->streetaddress;
       $appoint->state=$request->state;
       $appoint->city=$request->city;
       $appoint->concern=$request->concern;
       $appoint->department=$request->department;
       $appoint->doctor=$request->doctor;
        $appoint->save();
        return view('/checkout.checkout');
       
    }

    public function session(Request $request) {
        request()->validate([

            'email' => 'required|email|unique:appointments'
        ]);
        
        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $username = $request->get('name');
        $emailid = $request->get('email');
        $total_amount = $request->get('total_amount');
    
        // Check Email validity
        $emailExists = checkout::where('email', $request->email)->exists();

       if ($emailExists) {
           return redirect()->back()->withInput()->withErrors(['email' => 'The email address is already in use.']);
       }
        // total amount to cents
        $total_cents = $total_amount * 100;
    
        // Store data 
        $payment = new checkout;
        $payment->name = $username;
        $payment->email = $emailid;
        $payment->total_amount = $total_amount;
        $payment->save();
    
        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'USD',
                        'product_data' => [
                            'name' => $username,
                        ],
                        'unit_amount' => $total_cents,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('checkout.success', ['id' => $payment->id]),
            'cancel_url' => route('checkout.cancel')
        ]);
    
        return redirect()->away($session->url);
    }
    
    

    public function success($id)
    {
        $paymentData = checkout::find($id);
    
        return view('checkout.success', compact('paymentData'));
    }
    

    public function cancel(){
        return view('checkout.cancel');
    }




    public function detail(Appointment $appoint)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function appointedit($id)
    {
        $appointdata=Appointment::find($id);
        $departments = Deppartment::get();
        return view('appoint.edit', compact('appointdata', 'departments'));
    }
   
    /**
     * Update the specified resource in storage.
     */
    public function appointupdate(Request $request)
    {

        $request->validate([
            'email' => 'required|email|unique:appointments,email,',
        ]);
            
        $id= $request->id;
        $appoint=Appointment::find($id);
        $appoint->firstname=$request->firstname;
        $appoint->lastname=$request->lastname;
       $appoint->dateofbirth=$request->dateofbirth;
       $appoint->gender=$request->gender;
       $appoint->email=$request->email;
       $appoint->zipcode=$request->zipcode;
       $appoint->phonenumber=$request->phonenumber;
       $appoint->streetaddress=$request->streetaddress;
       $appoint->state=$request->state;
       $appoint->city=$request->city;
       $appoint->concern=$request->concern;
    //    $appoint->department=$request->department;
       $appoint->doctor=$request->doctor;
        $appoint->save();
        return redirect('/appoint');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteappoint($id)
    {
        
        $data=Appointment::find($id);
        $data->delete();
        return redirect('/appoint');
    }
}