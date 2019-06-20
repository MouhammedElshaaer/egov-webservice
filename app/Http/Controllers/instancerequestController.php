<?php

namespace App\Http\Controllers;

use App\Instance_Attachment;
use App\Instance_Fees;
use App\Instance_Fees_Details;
use App\Instance_Request;
use App\Transaction;
use App\Customer;
use App\Address_structure;
use Illuminate\Http\Request;

class instancerequestController extends Controller
{
    public function instanceRequestFetch(Request $request) 
    {
        $instance_request = $request->data["request_instance"];
        $transaction = $request->data["transaction"];
        $request = \App\Request::where("request_name", $instance_request["request"]["name"])->first();
        $customer = Customer::where("citizen_national_id", $instance_request["customer"]["national_id"])->first();

        $requestInstance =  new Instance_Request();
        $requestInstance->request_id = $request->id;
        $requestInstance->structure_id = $instance_request["structure"]["id"];
        $requestInstance->customer_id = $customer->id;
        // $requestInstance->current_state = $instance_request->current_state; // added by ahmed salah 12/6/2019
        // $requestInstance->bool = $request->bool;
        
        $requestInstance->save();

        $this->insertTransaction($transaction, $request->id);

        return response()->json(["saved"],200);
    }

    public function getRequestInstance(Request $request)
    {
        $instanceRequest = Instance_Request::where('id',$request->id)->first();
        $request = \App\Request::find($instanceRequest->request_id);
        $structure = Address_structure::find($instanceRequest->structure_id);
        $customer = Customer::find($instanceRequest->customer_id);

        $data = [
            "instanceRequest" => $instanceRequest,
            "request" => $request,
            "structure" => $structure,
            "customer" => $customer,
        ];

        return response()->json($data,200);
    }

    public function getRequestsInstances()
    {
        $requestInstances = Instance_Request::all();
        return response()->json(["requests_instances" => $requestInstances],200);
    }

    public function insertTransaction($transaction, $request_id){

        $newTransaction = new Transaction();
        $newTransaction->Instance_id = $request_id;
        $newTransaction->Bond_Agency_id = $transaction["agency"]["id"];
        $newTransaction->save();

    }

    public function getTransaction(Request $request)
    {
        $transaction = Transaction::where('id',$request->id)->first();
        return response()->json(["transaction" => $transaction],200);
    }

    public function getTransactions()
    {
        $transactions = Transaction::all();
        return response()->json(["transactions" => $transactions],200);
    }

    public function instanceAttachments(Request $request)
    {
        $i = new Instance_Attachment();
        $i->attachment_id = $request->attachment_id;
        $i->cat = $request->cat;
        $i->ORG_id = $request->ORG_id;
        $i->Archived = $request->Archived;
        $i->Received =$request->Received;
        $i->deleted = $request->deleted;
        $i->mandatory_optional = $request->mandatory_optional;
        $i->archive_document_id = $i->archive_document_id;
        $i->save();
        return response()->json(['instance attachment',"saved"],200);

    }

    public function instanceFees(Request $request)
    {
        $i = new Instance_Fees();

        $i->request_step_id = $request->request_step_id;
        $i->instance_request_id = $request->instance_request_id;
        $i->fees_id = $request->fees_id;
        $i->ORG_id = $request->ORG_id;
        $i->customer_id = $request->customer_id;
        $i->receptor_empid = $request->receptor_empid;
        $i->evaluation_date = $request->evaluation_date;
        $i->total = $request->total;
        $i->payment_date = $request->payment_date;
        $i->treasure_number = $request->treasure_number;
        $i->esal_number = $request->esal_number;
        $i->evaluator_empid= $request->evaluator_empid;
        $i->payed_requeststep_id= $request->payed_requeststep_id;
        $i->payed_value = $request->payed_value;
        $i->return_percentage = $request->return_percentage;
        $i->notes = $request->notes;
        $i->installment_number = $request->installment_number;
        $i->payment_type = $request->payment_type;
        $i->LUS_id = $request->LUS_id;
        $i->canceled = $request->canceled;
        $i->check_number = $request->check_number;
        $i->check_bank_id = $request->check_bank_id;
        $i->check_date = $request->check_date;
        $i->check_value = $request->check_value;
        $i->save();
        return response()->json(['instance fees',"saved"],200);
    }

    public function instanceFeesDetails(Request $request)
    {
        $i = new Instance_Fees_Details;
        $i->request_step_id = $request->request_step_id;
        $i->fees_id = $request->fees_id;
        $i->container_id = $request->container_id;
        $i->value = $request->value;
        $i->save();
        return response()->json(['instance fees details',"saved"],200);
    }

    public function instanceInspection(Request $request)
    {
        $i = new Instance_Request();
        $i->Request_Step_id = $request->Request_Step_id;
        $i->Instance_id  = $request->Instance_id;
        $i-> ORG_id = $request->ORG_id;
        $i->Employee_id  = $request->Employee_id;
        $i->Inspection_Date  = $request->Inspection_Date;
        $i->Status  = $request->Status;
        $i->Notes  = $request->Notes;
        $i-> Receiving_Date = $request->Receiving_Date;
        $i->Received_Request_Step_id  = $request->Received_Request_Step_id;
        $i->save();
        return response()->json(['instance request',"saved"],200);
    }
}
