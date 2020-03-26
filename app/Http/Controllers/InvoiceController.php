<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Structure;
use Illuminate\Support\Facades\Auth;
use App\Block;
use App\View;
use App\Asset_cat;
use App\ActionCategory;
use App\User;
use App\User_auth;
use App\Partner;
use App\Partner_block;
use App\CaseChannel;
use App\Person;
use App\Member_type;
use App\Country;
use App\Subdistrict;
use App\District;
use App\Province;
use App\Asset_type;
use App\match_member_id;
use App\Portfolio;
use App\Asset;
use App\CaseCategory;
use App\CaseType;
use App\CaseSubType;
use App\Cases;
use App\match_id;
use App\Procedures_To_Process;
use App\Process;
use App\Member_group;
use App\Family;
use App\Pid_group;
use App\Partner_group;
use App\CaseAuth;
use App\File;
use App\Asset_Attacht;
use App\Case_Attacht;
use App\Offer;
use App\Proposal;
use App\Case_proposal;
use App\Case_log;
use App\Casemiddledata;
use App\Casemiddledatatype;
use App\Offer_Attacht;
use App\Promotion;
use App\Case_condition;
use App\CaseAction;
use App\Stage;
use PDF;
use App\Http\Controllers\CaseCenterController;
use FPDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\DataController;
use App\Http\Controllers\SidebarController;
class InvoiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('view');
        $this->datacenter = new DataController;
    }
     public function invoice($id)
      {
        $casecansee =  $this->datacenter->showcasecanseeall();

      if(!in_array($id,$casecansee))
      {
        return view('error');
      }
 	   $day = date("d");
        $month = date("m");
        $year = date("Y")+543;
        $date = $day.'/'.$month.'/'.$year;
        $currentuserid = Auth::user()->id;
        $matchid = match_id::where('user_id',$currentuserid)->value('id');
        $matchid= match_id::find($matchid);
        $username = $matchid->public_name;
        $useremail = $matchid->public_email;
        $usermobile = $matchid->public_mobile;
        $findcase = Cases::with(['Person','Stage','Cases','Block','Partner_block','CaseType','CaseSubType','Asset','match_id','CaseStatus','coordiantor','CaseChannel'])->find($id);
        $customername = $findcase->person->name;
        $customerlastname = $findcase->person->lname;
        $customermobile = $findcase->person->mobile;
        $contactname = $findcase->require_value16;
        $contactmobile = $findcase->require_value17;
        $carcode = $findcase->Asset->ref_info7;
        $carbrand = $findcase->Asset->ref_info3;
        $cargeneration = $findcase->Asset->ref_info4;
        $vehicleregistration = $findcase->Asset->name;
        $vehicleyear = $findcase->Asset->ref_info5;
        $subgeneration = $findcase->Asset->ref_info6;
        $decoratevalue = $findcase->Asset->ref_info10;
        $decoratelist = $findcase->Asset->ref_info12;
        $moredetail = $findcase->require_value15;

        $casemiddledata = Casemiddledata::where('case_id',$id)->pluck('offer_id')->toArray();
        $offerinsuranceid = Offer::whereIn('id',$casemiddledata)->whereIn('type_id',[1,2,3,4,5,6])->orderBy('id','DESC')->take(1)->value('id');
        $offerinsurance = Offer::with(['promotion','campaign','OfferType','match_id','Person','Proposal','branch'])->find($offerinsuranceid);

        if($offerinsurance == NULL || $offerinsurance == '' )
        {
          $offerinsurancecompany = '';
          $offerinsurancepartner = '';
          $offerinsurancefilepublicname = '';
          $offerinsurancefileid = '';
          $offerinsurancepaymentpremium = 0;
          $offerinsurancepaymentdiscount15 = 0;
          $offerinsurancepaymentdiscount16 = 0;
          $offerinsurancepaymentdiscount20 = 0;
          $offerinsurancepaymenttaxdeduction= 0;
          $offerinsurancepaymentpartnerconsultfee= 0;
          $offerinsurancepaymentuserservicefee= 0;
          $offerinsurancepaymentgrosscom = 0;
          $offerinsurancecopypaymentfilepublicname = '';
          $offerinsurancecopypaymentfileid = '';
          $offerinsurancepaymenttocompanycopyfilepublicname = '';
          $offerinsurancepaymenttocompanycopyfileid = '';
          $insurancestamp = 0;
          $insurancevat = 0;
          $insurancetype = '';
          $companyaddress = '';
          $organizecitizen = '';
          $offervalue3 = '';
          $offervalue1 = '';
          $offervalue2 = '';
          $offervalue4 = '';
          $offervalue5 = '';
          $offervalue7 = '';
          $offervalue14 = '';
          $offervalue8 = '';
          $offervalue6 = '';
          $offervalue10 = '';
          $offervalue19 = '';
          $offervalue9 = '';
          $offervalue12 = '';
          $offervalue11 = '';
          $offervalue13 = '';
          $offervaluename3 = '';
          $offervaluename1 = '';
          $offervaluename2 = '';
          $offervaluename4 = '';
          $offervaluename5 = '';
          $offervaluename7 = '';
          $offervaluename14 = '';
          $offervaluename8 = '';
          $offervaluename6 = '';
          $offervaluename10 = '';
          $offervaluename19 = '';
          $offervaluename9 = '';
          $offervaluename12 = '';
          $offervaluename11 = '';
          $offervaluename13 = '';
          $offerpaymentvaluename1 = '';
          $offerpaymentvaluename2 = '';
          $offerpaymentvaluename3 = '';

        }
        else
        {
          $offerinsurancecompany = $offerinsurance->Person->name;
          $offerinsurancepartner = $offerinsurance->Proposal->Partner_block->name;
          $offerinsurancepaymentpremium   = $offerinsurance->offer_payment_value4;
          $offerinsurancepaymentdiscount15 = $offerinsurance->offer_payment_value15;
          $offerinsurancepaymentdiscount16 = $offerinsurance->offer_payment_value16;
          $offerinsurancepaymentdiscount20 = $offerinsurance->offer_payment_value20;
          $offerinsurancepaymenttaxdeduction = $offerinsurance->offer_payment_value5;
          $offerinsurancepaymentpartnerconsultfee = $offerinsurance->offer_payment_value17;
          $offerinsurancepaymentuserservicefee = $offerinsurance->offer_payment_value19;
          $offerinsurancepaymentgrosscom = $offerinsurance->offer_payment_value8;
          $insurancestamp = $offerinsurance->offer_payment_value2;
          $insurancevat = $offerinsurance->offer_payment_value3;
          $insurancetype = $offerinsurance->OfferType->name;
          $organizecitizen = $offerinsurance->Person->id_num;
          $offervalue3 = $offerinsurance->offer_value3;
          $offervalue1 = $offerinsurance->offer_value1;
          $offervalue2 = $offerinsurance->offer_value2;
          $offervalue4 = $offerinsurance->offer_value4;
          $offervalue5 = $offerinsurance->offer_value5;
          $offervalue7 = $offerinsurance->offer_value7;
          $offervalue14 = $offerinsurance->offer_value14;
          $offervalue6 = $offerinsurance->offer_value6;
          $offervalue8 = $offerinsurance->offer_value8;
          $offervalue19 = $offerinsurance->offer_value19;
          $offervalue9 = $offerinsurance->offer_value9;
          $offervalue12 = $offerinsurance->offer_value12;
          $offervalue10 = $offerinsurance->offer_value10;
          $offervalue11 = $offerinsurance->offer_value11;
          $offervalue13 = $offerinsurance->offer_value13;
          $offervaluename3 = $offerinsurance->OfferType->offer_value_name3;
          $offervaluename1 = $offerinsurance->OfferType->offer_value_name1;
          $offervaluename2 = $offerinsurance->OfferType->offer_value_name2;
          $offervaluename4 = $offerinsurance->OfferType->offer_value_name4;
          $offervaluename5 = $offerinsurance->OfferType->offer_value_name5;
          $offervaluename7 = $offerinsurance->OfferType->offer_value_name7;
          $offervaluename14 = $offerinsurance->OfferType->offer_value_name14;
          $offervaluename6 = $offerinsurance->OfferType->offer_value_name6;
          $offervaluename8 = $offerinsurance->OfferType->offer_value_name8;
          $offervaluename19 = $offerinsurance->OfferType->offer_value_name19;
          $offervaluename9 = $offerinsurance->OfferType->offer_value_name9;
          $offervaluename12 = $offerinsurance->OfferType->offer_value_name12;
          $offervaluename10 = $offerinsurance->OfferType->offer_value_name10;
          $offervaluename11 = $offerinsurance->OfferType->offer_value_name11;
          $offervaluename13 = $offerinsurance->OfferType->offer_value_name13;
          $offerpaymentvaluename1 = $offerinsurance->OfferType->offer_payment_name1;
          $offerpaymentvaluename2 = $offerinsurance->OfferType->offer_payment_name2;
          $offerpaymentvaluename3 = $offerinsurance->OfferType->offer_payment_name3;

          if($offerinsurance->ref_branch_id == NULL ||$offerinsurance->ref_branch_id == ''||$offerinsurance->ref_branch_id == 0)
          {
            $companyaddress = '';
            $addno = $offerinsurance->Person->add2;
            $addalley = $offerinsurance->Person->add2_alley;
            $addroad = $offerinsurance->Person->add2_road;
            $addpostcode = $offerinsurance->Person->add2_postcode;
            $branchname = '';
            $branchnumber = '';

            if($offerinsurance->Person->add2_subdistrict == NULL ||$offerinsurance->Person->add2_subdistrict == '' ||$offerinsurance->Person->add2_subdistrict == 0)
            {
              $addsubdistrict = '';
            }
            else
            {
              $addsubdistrict = $offerinsurance->Person->subdistrict2->name_in_thai;
            }
            if($offerinsurance->Person->add2_district == NULL ||$offerinsurance->Person->add2_district == '' || $offerinsurance->Person->add2_district == 0)
            {
              $adddistrict = '';
            }
            else
            {
              $adddistrict = $offerinsurance->Person->district2->name_in_thai;
            }
            if($offerinsurance->Person->add2_city == NULL ||$offerinsurance->Person->add2_city == '' || $offerinsurance->Person->add2_city == 0)
            {
              $addcity = '';
            }
            else
            {
              $addcity = $offerinsurance->Person->city2->name_in_thai;
            }
            if($offerinsurance->Person->add2_country == NULL ||$offerinsurance->Person->add2_country == '' || $offerinsurance->Person->add2_country == 0)
            {
              $addcountry = '';
            }
            else
            {
              $addcountry = $offerinsurance->Person->country2->name;
            }
          }
          else
          {
            $addno = $offerinsurance->branch->add_no;
            $addalley = $offerinsurance->branch->add_alley;
            $addroad = $offerinsurance->branch->add_road;
            $branchname = $offerinsurance->branch->name;
            $branchnumber = $offerinsurance->branch->number;

            if($offerinsurance->branch->add_subdistrict == NULL ||$offerinsurance->branch->add_subdistrict == '' || $offerinsurance->branch->add_subdistrict == 0)
            {
              $addsubdistrict = '';
            }
            else
            {
              $addsubdistrict = $offerinsurance->branch->Subdistrict->name_in_thai;
            }
            if($offerinsurance->branch->add_district == NULL ||$offerinsurance->branch->add_district == '' || $offerinsurance->branch->add_district == 0)
            {
              $adddistrict = '';
            }
            else
            {
              $adddistrict = $offerinsurance->branch->District->name_in_thai;
            }
            if($offerinsurance->branch->add_city == NULL ||$offerinsurance->branch->add_city == '' || $offerinsurance->branch->add_city == 0)
            {
              $addcity = '';
            }
            else
            {
              $addcity = $offerinsurance->branch->city->name_in_thai;
            }
            if($offerinsurance->branch->add_country == NULL ||$offerinsurance->branch->add_country == '' || $offerinsurance->branch->add_country == 0)
            {
              $addcountry = '';
            }
            else
            {
              $addcountry = $offerinsurance->branch->Country->name;
            }
            $addpostcode = $offerinsurance->branch->add_postcode;

          }
          //$companyaddress = "เลขที่ ".$addno." ซอย ".$addalley." ถนน ".$addroad." แขวง ".$addsubdistrict." เขต ".$adddistrict." จังหวัด ".$addcity." ประเทศ ".$addcountry." รหัรหัสไปรษณีย์ ".$addpostcode;
          $companyaddress = $addno."  ".$addalley."  ".$addroad."  ".$addsubdistrict."  ".$adddistrict."  ".$addcity."  ".$addcountry."  ".$addpostcode;

          }

        $offeractid = Offer::with(['promotion','campaign','OfferType','match_id','Person','Proposal','branch'])->whereIn('id',$casemiddledata)->where('type_id',7)->orderBy('id','DESC')->take(1)->value('id');
        $offeract = Offer::with(['promotion','campaign','OfferType','match_id','Person','Proposal','branch'])->find($offeractid);
        if($offeract == NULL || $offeract == '' )
        {
          $offeractcompany = '';
          $offeractpartner = '';
          $offeractfilepublicname = '';
          $offeractfileid = '';
          $offeractpaymentpremium = 0;
          $offeractpaymentdiscount15 = 0;
          $offeractpaymentdiscount16 = 0;
          $offeractpaymentdiscount20 = 0;
          $offeractpaymenttaxdeduction = 0;
          $offeractpaymentpartnerconsultfee = 0;
          $offeractpaymentuserservicefee = 0;
          $offeractpaymentgrosscom = 0;
          $offeractcopypaymentfilepublicname = '';
          $offeractcopypaymentfileid = '';
          $offeractpaymenttocompanycopyfilepublicname = '';
          $offeractpaymenttocompanycopyfileid = '';
          $actstamp = 0;
          $actvat = 0;
          if($offerinsurance == NULL ||$offerinsurance == ''  )
          {
            $branchname = '';
            $branchnumber = '';
            $organizecitizen ='';
          }
        }
        else
        {
          $offeractcompany = $offeract->Person->name;
          $offeractpartner = $offeract->Proposal->Partner_block->name;
          $offeractpaymentpremium   = $offeract->offer_payment_value4;
          $offeractpaymentdiscount15 = $offeract->offer_payment_value15;
          $offeractpaymentdiscount16 = $offeract->offer_payment_value16;
          $offeractpaymentdiscount20 = $offeract->offer_payment_value20;
          $offeractpaymenttaxdeduction = $offeract->offer_payment_value5;
          $offeractpaymentpartnerconsultfee = $offeract->offer_payment_value17;
          $offeractpaymentuserservicefee = $offeract->offer_payment_value19;
          $offeractpaymentgrosscom = $offeract->offer_payment_value8;
          $actstamp = $offeract->offer_payment_value2;
          $actvat = $offeract->offer_payment_value3;
          $organizecitizen = $offeract->Person->id_num;
          if($offerinsurance == NULL ||$offerinsurance == ''  )
          {
          $organizecitizen = $offeract->Person->id_num;
          if($offeract->ref_branch_id == NULL ||$offeract->ref_branch_id == ''||$offeract->ref_branch_id == 0)
          {
            $companyaddress = '';
            $addno = $offeract->Person->add2;
            $addalley = $offeract->Person->add2_alley;
            $addroad = $offeract->Person->add2_road;
            $addpostcode = $offeract->Person->add2_postcode;
            $branchname = '';
            $branchnumber = '';

            if($offeract->Person->add2_subdistrict == NULL ||$offeract->Person->add2_subdistrict == '' ||$offeract->Person->add2_subdistrict == 0)
            {
              $addsubdistrict = '';
            }
            else
            {
              $addsubdistrict = $offeract->Person->subdistrict2->name_in_thai;
            }
            if($offeract->Person->add2_district == NULL ||$offeract->Person->add2_district == '' || $offeract->Person->add2_district == 0)
            {
              $adddistrict = '';
            }
            else
            {
              $adddistrict = $offeract->Person->district2->name_in_thai;
            }
            if($offeract->Person->add2_city == NULL ||$offeract->Person->add2_city == '' || $offeract->Person->add2_city == 0)
            {
              $addcity = '';
            }
            else
            {
              $addcity = $offeract->Person->city2->name_in_thai;
            }
            if($offeract->Person->add2_country == NULL ||$offeract->Person->add2_country == '' || $offeract->Person->add2_country == 0)
            {
              $addcountry = '';
            }
            else
            {
              $addcountry = $offeract->Person->country2->name;
            }
          }
          else
          {
            $branchname = $offeract->branch->name;
            $branchnumber = $offeract->branch->number;
            $addno = $offeract->branch->add_no;
            $addalley = $offeract->branch->add_alley;
            $addroad = $offeract->branch->add_road;
            if($offeract->branch->add_subdistrict == NULL ||$offeract->branch->add_subdistrict == '' || $offeract->branch->add_subdistrict == 0)
            {
              $addsubdistrict = '';
            }
            else
            {
              $addsubdistrict = $offeract->branch->Subdistrict->name_in_thai;
            }
            if($offeract->branch->add_district == NULL ||$offeract->branch->add_district == '' || $offeract->branch->add_district == 0)
            {
              $adddistrict = '';
            }
            else
            {
              $adddistrict = $offeract->branch->District->name_in_thai;
            }
            if($offeract->branch->add_city == NULL ||$offeract->branch->add_city == '' || $offeract->branch->add_city == 0)
            {
              $addcity = '';
            }
            else
            {
              $addcity = $offeract->branch->city->name_in_thai;
            }
            if($offeract->branch->add_country == NULL ||$offeract->branch->add_country == '' || $offeract->branch->add_country == 0)
            {
              $addcountry = '';
            }
            else
            {
              $addcountry = $offeract->branch->Country->name;
            }
            $addpostcode = $offeract->branch->add_postcode;

          }
          //$companyaddress = "เลขที่ ".$addno." ซอย ".$addalley." ถนน ".$addroad." แขวง ".$addsubdistrict." เขต ".$adddistrict." จังหวัด ".$addcity." ประเทศ ".$addcountry." รหัรหัสไปรษณีย์ ".$addpostcode;
          $companyaddress = $addno."  ".$addalley."  ".$addroad."  ".$addsubdistrict."  ".$adddistrict."  ".$addcity."  ".$addcountry."  ".$addpostcode;

          }
        }
        $offertaxid = Offer::with(['promotion','campaign','OfferType','match_id','Person','Proposal','branch'])->whereIn('id',$casemiddledata)->where('type_id',8)->orderBy('id','DESC')->take(1)->value('id');
        $offertax = Offer::with(['promotion','campaign','OfferType','match_id','Person','Proposal','branch'])->find($offertaxid);
        if($offertax == NULL || $offertax == '' )
        {
          $offertaxcompany = '';
          $offertaxpartner = '';
          $offertaxfilepublicname = '';
          $offertaxfileid = '';
          $offertaxpaymentpremium = 0;
          $offertaxpaymentdiscount15 = 0;
          $offertaxpaymentdiscount16 = 0;
          $offertaxpaymentdiscount20 = 0;
          $offertaxpaymenttaxdeduction = 0;
          $offertaxpaymentuserservicefee = 0;
          $offertaxpaymentgrosscom = 0;
          $offertaxpaymentpartnerconsultfee = 0;
          $offertaxcopypaymentfilepublicname = '';
          $offertaxcopypaymentfileid = '';
          $offertaxpaymenttocompanycopyfilepublicname = '';
          $offertaxpaymenttocompanycopyfileid = '';
          $taxstamp = 0;
          $taxvat = 0;
          if($offerinsurance == NULL ||$offerinsurance == ''  )
          {
          $organizecitizen = '';
          $branchname = '';
          $branchnumber = '';

          }
        }
        else
        {
          $offertaxcompany = $offertax->Person->name;
          $offertaxpartner = $offertax->Proposal->Partner_block->name;
          $offertaxpaymentpremium   = $offertax->offer_payment_value4;
          $offertaxpaymentdiscount15 = $offertax->offer_payment_value15;
          $offertaxpaymentdiscount16 = $offertax->offer_payment_value16;
          $offertaxpaymentdiscount20 = $offertax->offer_payment_value20;
          $offertaxpaymenttaxdeduction = $offertax->offer_payment_value5;
          $offertaxpaymentpartnerconsultfee = $offertax->offer_payment_value17;
          $offertaxpaymentuserservicefee = $offertax->offer_payment_value19;
          $offertaxpaymentgrosscom = $offertax->offer_payment_value8;
          $taxstamp = $offertax->offer_payment_value2;
          $taxvat = $offertax->offer_payment_value3;
          $organizecitizen = $offeract->Person->id_num;
          if($offerinsurance == NULL ||$offerinsurance == ''  )
          {
          $organizecitizen = $offertax->Person->id_num;
          if($offertax->ref_branch_id == NULL ||$offertax->ref_branch_id == ''||$offertax->ref_branch_id == 0)
          {
            $companyaddress = '';
            $addno = $offertax->Person->add2;
            $addalley = $offertax->Person->add2_alley;
            $addroad = $offertax->Person->add2_road;
            $addpostcode = $offertax->Person->add2_postcode;
            $branchname ='';
            $branchnumber = '';

            if($offertax->Person->add2_subdistrict == NULL ||$offertax->Person->add2_subdistrict == '' ||$offertax->Person->add2_subdistrict == 0)
            {
              $addsubdistrict = '';
            }
            else
            {
              $addsubdistrict = $offertax->Person->subdistrict2->name_in_thai;
            }
            if($offertax->Person->add2_district == NULL ||$offertax->Person->add2_district == '' || $offertax->Person->add2_district == 0)
            {
              $adddistrict = '';
            }
            else
            {
              $adddistrict = $offertax->Person->district2->name_in_thai;
            }
            if($offertax->Person->add2_city == NULL ||$offertax->Person->add2_city == '' || $offertax->Person->add2_city == 0)
            {
              $addcity = '';
            }
            else
            {
              $addcity = $offertax->Person->city2->name_in_thai;
            }
            if($offertax->Person->add2_country == NULL ||$offertax->Person->add2_country == '' || $offertax->Person->add2_country == 0)
            {
              $addcountry = '';
            }
            else
            {
              $addcountry = $offertax->Person->country2->name;
            }
          }
          else
          {
            $addno = $offertax->branch->add_no;
            $addalley = $offertax->branch->add_alley;
            $addroad = $offertax->branch->add_road;
            $branchname = $offertax->branch->name;
            $branchnumber = $offertax->branch->number;

            if($offertax->branch->add_subdistrict == NULL ||$offerinsurance->branch->add_subdistrict == '' || $offerinsurance->branch->add_subdistrict == 0)
            {
              $addsubdistrict = '';
            }
            else
            {
              $addsubdistrict = $offertax->branch->Subdistrict->name_in_thai;
            }
            if($offertax->branch->add_district == NULL ||$offerinsurance->branch->add_district == '' || $offerinsurance->branch->add_district == 0)
            {
              $adddistrict = '';
            }
            else
            {
              $adddistrict = $offertax->branch->District->name_in_thai;
            }
            if($offertax->branch->add_city == NULL ||$offertax->branch->add_city == '' || $offertax->branch->add_city == 0)
            {
              $addcity = '';
            }
            else
            {
              $addcity = $offertax->branch->city->name_in_thai;
            }
            if($offertax->branch->add_country == NULL ||$offertax->branch->add_country == '' || $offertax->branch->add_country == 0)
            {
              $addcountry = '';
            }
            else
            {
              $addcountry = $offertax->branch->Country->name;
            }
            $addpostcode = $offertax->branch->add_postcode;

          }
          //$companyaddress = "เลขที่ ".$addno." ซอย ".$addalley." ถนน ".$addroad." แขวง ".$addsubdistrict." เขต ".$adddistrict." จังหวัด ".$addcity." ประเทศ ".$addcountry." รหัรหัสไปรษณีย์ ".$addpostcode;
          $companyaddress = $addno."  ".$addalley."  ".$addroad."  ".$addsubdistrict."  ".$adddistrict."  ".$addcity."  ".$addcountry."  ".$addpostcode;


          }
        }
        //////////// Calculation For Case Payment ////////////
        if($offerinsurancepaymentdiscount15 == 'NaN')
        {
          $offerinsurancepaymentdiscount15 = 0;
        }
        if($offeractpaymentdiscount16 == 'NaN')
        {
          $offeractpaymentdiscount16 = 0;
        }
        if($offertaxpaymentdiscount20 == 'NaN')
        {
          $offertaxpaymentdiscount20 = 0;
        }
        $alldiscountinsurance = $offerinsurancepaymentdiscount15+$offeractpaymentdiscount16+$offertaxpaymentdiscount20;
        $alldiscountinsurance = round($alldiscountinsurance,2);
        if($offerinsurancepaymentpremium == 'NaN')
        {
          $offerinsurancepaymentpremium = 0;
        }
        if($alldiscountinsurance == 'NaN')
        {
          $alldiscountinsurance = 0;
        }
        $calculatebeforetaxdeductinsurance =$offerinsurancepaymentpremium-$alldiscountinsurance;
        $calculatebeforetaxdeductinsurance =  round($calculatebeforetaxdeductinsurance,2);
        $calculateaftertaxdeductinsurance =$calculatebeforetaxdeductinsurance-$offerinsurancepaymenttaxdeduction;
        $calculateaftertaxdeductinsurance =  round($calculateaftertaxdeductinsurance,2);
        if($calculateaftertaxdeductinsurance == 'NaN' || $calculateaftertaxdeductinsurance == NULL || $calculateaftertaxdeductinsurance == '')
        {
          $calculateaftertaxdeductinsurance = 0;
        }
        if($offerinsurancepaymentpartnerconsultfee == 'NaN' || $offerinsurancepaymentpartnerconsultfee == NULL || $offerinsurancepaymentpartnerconsultfee == '')
        {
          $offerinsurancepaymentpartnerconsultfee = 0;
        }
        $totalpaidpartnerinsurance =$calculateaftertaxdeductinsurance-$offerinsurancepaymentpartnerconsultfee;
        $totalpaidpartnerinsurance =  round($totalpaidpartnerinsurance,2);
        if($calculateaftertaxdeductinsurance == 'NaN' || $calculateaftertaxdeductinsurance == NULL || $calculateaftertaxdeductinsurance == '')
        {
          $calculateaftertaxdeductinsurance = 0;
        }
        if($totalpaidpartnerinsurance == 'NaN' || $totalpaidpartnerinsurance == NULL || $totalpaidpartnerinsurance == '')
        {
          $offerinsurancepaymentuserservicefee = 0;
        }
        $totalpaiduserinsurance =$totalpaidpartnerinsurance-$offerinsurancepaymentuserservicefee;
        $totalpaiduserinsurance =  round($totalpaiduserinsurance,2);
        if($offerinsurancepaymentpremium == 'NaN' || $offerinsurancepaymentpremium == NULL || $offerinsurancepaymentpremium == '')
        {
          $offerinsurancepaymentpremium = 0;
        }
        if($offerinsurancepaymentgrosscom == 'กรุณาเลือกหมวดการคำนวณ' || $offerinsurancepaymentgrosscom == NULL || $offerinsurancepaymentgrosscom == '')
        {
          $offerinsurancepaymentgrosscom = 0;
        }
        if($offerinsurancepaymenttaxdeduction == 'NaN' || $offerinsurancepaymenttaxdeduction == NULL || $offerinsurancepaymenttaxdeduction == '')
        {
          $offerinsurancepaymenttaxdeduction = 0;
        }
        $totalpaidcompanyinsurance =  $offerinsurancepaymentpremium-$offerinsurancepaymentgrosscom-$offerinsurancepaymenttaxdeduction;
        $totalpaidcompanyinsurance =round($totalpaidcompanyinsurance,2);

        $alldiscountact = $offerinsurancepaymentdiscount15+$offeractpaymentdiscount16+$offertaxpaymentdiscount20;
        $alldiscountact = round($alldiscountact,2);
        $calculatebeforetaxdeductact =$offeractpaymentpremium-$alldiscountact;
        $calculatebeforetaxdeductact =  round($calculatebeforetaxdeductact,2);
        $calculateaftertaxdeductact =$calculatebeforetaxdeductact-$offeractpaymenttaxdeduction;
        $calculateaftertaxdeductact =  round($calculateaftertaxdeductact,2);
        $totalpaidpartneract =$calculateaftertaxdeductact-$offeractpaymentpartnerconsultfee;
        $totalpaidpartneract =  round($totalpaidpartneract,2);
        $totalpaiduseract =$totalpaidpartneract-$offeractpaymentuserservicefee;
        $totalpaiduseract =  round($totalpaiduseract,2);
        $totalpaidcompanyact =  $offeractpaymentpremium-$offeractpaymentgrosscom-$offeractpaymenttaxdeduction;
        $totalpaidcompanyact =round($totalpaidcompanyact,2);

        $alldiscounttax = $offerinsurancepaymentdiscount15+$offeractpaymentdiscount16+$offertaxpaymentdiscount20;
        $alldiscounttax = round($alldiscounttax,2);
        $calculatebeforetaxdeducttax =$offertaxpaymentpremium-$alldiscounttax;
        $calculatebeforetaxdeducttax =  round($calculatebeforetaxdeducttax,2);
        $calculateaftertaxdeducttax =$calculatebeforetaxdeducttax-$offertaxpaymenttaxdeduction;
        $calculateaftertaxdeducttax =  round($calculateaftertaxdeducttax,2);
        $totalpaidpartnertax =$calculateaftertaxdeducttax-$offertaxpaymentpartnerconsultfee;
        $totalpaidpartnertax =  round($totalpaidpartnertax,2);
        $totalpaidusertax =$totalpaidpartnertax-$offertaxpaymentuserservicefee;
        $totalpaidusertax =  round($totalpaidusertax,2);
        $totalpaidcompanytax =  $offertaxpaymentpremium-$offertaxpaymentgrosscom-$offertaxpaymenttaxdeduction;
        $totalpaidcompanytax =round($totalpaidcompanytax,2);

        $allpremium =  $offerinsurancepaymentpremium+$offeractpaymentpremium+$offertaxpaymentpremium;
        $allpremium = round($allpremium,2);
        $alltaxdeduct = $offerinsurancepaymenttaxdeduction+$offeractpaymenttaxdeduction+$offertaxpaymenttaxdeduction;
        $alltaxdeduct = round($alltaxdeduct,2);
        $alldiscount =$alldiscountinsurance+$alldiscountact+$alldiscounttax;
        $alldiscount = round($alldiscount,2);
        $allcalculatebeforetaxdeduct = $calculatebeforetaxdeductinsurance+$calculatebeforetaxdeductact+$calculatebeforetaxdeducttax;
        $allcalculatebeforetaxdeduct = round($allcalculatebeforetaxdeduct,2);
        $allcalculateaftertaxdeduct = $calculateaftertaxdeductinsurance+$calculateaftertaxdeductact+$calculateaftertaxdeducttax;
        $allcalculateaftertaxdeduct = round($allcalculateaftertaxdeduct,2);
        $alltotalpaidpartner = $totalpaidpartnerinsurance+$totalpaidpartneract+$totalpaidpartnertax;
        $alltotalpaidpartner = round($alltotalpaidpartner,2);
        $alltotalpaiduser = $totalpaiduserinsurance+$totalpaiduseract+$totalpaidusertax;
        $alltotalpaiduser = round($alltotalpaiduser,2);
        $alltotalpaidcompany = $totalpaidcompanyinsurance+$totalpaidcompanyact+$totalpaidcompanytax;
        $alltotalpaidcompany = round($alltotalpaidcompany,2);
        $allstamp = $insurancestamp+$actstamp+$taxstamp;
        $allstamp = round($allstamp,2);
        $allvat = $insurancevat+$actvat+$taxvat;
        $allvat = round($allvat,2);

 	   $wealththailogo = 'data:image/png;base64,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';
 		 $carinsurancelogo = 'data:image/png;base64,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';
 		$html = "<style>
 @font-face {
   font-family: 'THSarabunNew';
   font-style: normal;
   font-weight: normal;
   src: url(http://wealththai.org/fonts/THSarabunNew.ttf) format('truetype');
 }
 @font-face {
   font-family: 'THSarabunNew-Bold';
   font-style: normal;
   font-weight: 700;
   src: url(http://wealththai.org/fonts/THSarabunNew Bold.ttf) format('truetype');
 }
 body{    font-family: 'THSarabunNew';
 }

 table {
   border-collapse: collapse;
   border-spacing:0.5px;
   width: 100%;
   font-size: 15.5px
 }
 .table
 {
    font-size:13px;
     border:solid 0.5px;
     text-align:center;
 }
 .columnshow10 {
   float:left;
   padding:10px;
   width: 10%;
   margin-top:-15px
 }
 .wealth
 {
   margin-top:-15px;
   max-width:100px
 }
 .carinsur
 {
   margin-top:-20px;
   max-width:100px
 }
 </style>";
 $html .= "<body><img class='wealth' src='{$wealththailogo}'  />
   <img class='carinsur' src='{$carinsurancelogo}' />

   <div style='width:450px;float:right;margin-top:-60px' >
     <p style='text-align:right;'>
       ต่อประกันครั้งหน้า อย่าลืมคิดถึงเรา
  เพราะความพึงพอใจของคุณ คือเป้าหมายสูงสุดของเรา
     </p>
   </div><br />
   <br />
   <br />

 ";




 $html .= "<p style='text-align:center;font-size:18px;margin-top:-70px'>ใบแจ้งหนี้ /Invoice</p>
 <div style='text-align:right'>
   <table style='width:250px;border:none;margin-left:600px;margin-top:-80px'>
     <tr>
     <td>วันที่</ttdh>
     <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>{$date}</td>
   </tr>
   <tr style='padding-bottom: 0;'>
     <td>เสนอราคาโดย</td>
     <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>{$username}  ({$usermobile})</td>
   </tr>
     <tr>

     <td>E-mail</td>
     <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>{$useremail}</td>
   </tr>
   </table>
 </div>
 <div style='text-align:left'>
   <table style='width:250px;border:none;margin-top:-60px'>
     <tr>
     <td>เรียนคุณ</td>
     <td>&nbsp;</td><td>{$customername} {$customerlastname}</td>
   </tr>
   <tr style='margin-top:-160px'>
     <td>เรื่อง</td>
     <td>&nbsp;</td><td>การแจ้งหนี้ชำระเบี้ยประกัน</td>
   </tr>
   </table>
 </div>
 <br/>
 <table style='width:1000px;margin-top:-20px' class='table'>
   <tr>
     <td class='table' style='width:100px;height:5px;text-align:center;background-color:#dedee3'>ชื่อผู้เอาประกันภัย</td>
     <td colspan='5'class='table'style='text-align:center'>{$customername} {$customerlastname}</td>
     <td class='table'style='width:100px;height:5px;text-align:center;background-color:#dedee3'>เบอร์โทรผู้เอาประกัน</td>
     <td colspan='4'class='table'style='text-align:center'>{$customermobile}</td>
   </tr>
   <tr>
     <td class='table'style='width:100px;height:5px;text-align:center;background-color:#dedee3'>ชื่อผู้ติดต่อ</td>
     <td colspan='5'class='table'style='text-align:center'>{$contactname}</td>
     <td class='table'style='width:100px;height:5px;text-align:center;background-color:#dedee3'>เบอร์โทรผู้ติดต่อ</td>
     <td colspan='4'class='table'style='text-align:center'>{$contactmobile}</td>
   </tr>
   <tr>
     <td class='table'style='background-color:#dedee3'>รหัสรถยนต์</td>
     <td colspan='3' class='table'style='background-color:#dedee3'>ยี่ห้อ</td>
     <td colspan='2' class='table' style='background-color:#dedee3'>รุ่น</th>
     <td  class='table'style='background-color:#dedee3;'>เลขทะเบียน</td>
     <td  class='table'style='background-color:#dedee3'>ปี/รุ่น</td>
     <td class='table'style='background-color:#dedee3'>รุ่นย่อย</td>
     <td colspan='2' class='table'style='background-color:#dedee3'>มูลค่าอุปกรณ์เสริม</td>

   </tr>
   <tr>
     <td class='table'>{$carcode}</td>
     <td  colspan='3' class='table'>{$carbrand}</td>
     <td colspan='2' class='table'>{$cargeneration}</td>
     <td  style=''class='table'>{$vehicleregistration}</td>
     <td class='table'>{$vehicleyear}</td>
     <td  class='table'>{$subgeneration}</td>
     <td colspan='2' class='table'>{$decoratevalue}</td>
   </tr>
   <tr>
       <td class='table' style='background-color:#dedee3'>รายการอุปกรณ์เสริม</td>
       <td  colspan='10'class='table'>{$decoratelist}</td>

   </tr>
   <tr>
       <td class='table' style='background-color:#dedee3'>ข้อมูลกรมธรรม์ภาคสมัครใจ</td>
       <td colspan='10'class='table'>{$insurancetype}</td>

   </tr>
   <tr>
       <td class='table' style='background-color:#dedee3'>ที่อยู่บริษัท</td>
       <td colspan='10'class='table'>{$companyaddress}</td>

   </tr>
   <tr>
       <td class='table' style='background-color:#dedee3'>หมายเลขผู้เสียภาษี</td>
       <td colspan='3'class='table'>{$organizecitizen}</td>
       <td class='table' style='background-color:#dedee3'>รหัสสาขา</td>
       <td colspan='2'class='table'>{$branchnumber}</td>
       <td class='table' style='background-color:#dedee3'>ชื่อสาขา</td>
       <td colspan='3'class='table'>{$branchname}</td>
   </tr>
   <tr>
       <td class='table' style='background-color:#dedee3'>ประเภทกรมธรรมภาคสมัครใจ</td>
       <td colspan='4'class='table'>2</td>
       <td colspan='6'class='table' style='background-color:#dedee3'>ความเสียหายต่อภายนอก</td>

   </tr>
   <tr>
     <td class='table' style='background-color:#dedee3'>รายละเอียดการชำระเงิน</td>
     <td class='table' style='background-color:#dedee3'>กรมธรรม์</td>
     <td class='table' style='background-color:#dedee3'>พรบ</td>
     <td class='table' style='background-color:#dedee3'>ภาษี</td>
     <td class='table' style='background-color:#dedee3'>รวม</td>
     <td class='table' style='background-color:#dedee3'>{$offervaluename3}</td>
     <td class='table'>{$offervalue3}</td>
     <td class='table' style='background-color:#dedee3'>{$offervaluename1}</td>
     <td class='table'>{$offervalue1}</td>
     <td class='table' style='background-color:#dedee3'>ต่อครั้ง</td>
     <td class='table'>{$offervalue1}</td>
   </tr>
   <tr>
     <td class='table'>{$offerpaymentvaluename1}</td>
     <td class='table'>{$offerinsurancepaymentpremium}</td>
     <td class='table'>{$offeractpaymentpremium}</td>
     <td class='table'>{$offertaxpaymentpremium}</td>
     <td class='table'>{$allpremium}</td>
     <td colspan='3' class='table' style='background-color:#dedee3'>ชดเชยความผิดส่วนแรก</td>
     <td colspan='3'  class='table'>{$offervalue4}</td>
   </tr>
   <tr>
     <td class='table'>{$offerpaymentvaluename2}</td>
     <td class='table'>{$insurancestamp}</td>
     <td class='table'>{$actstamp}</td>
     <td class='table'>{$taxstamp}</td>
     <td class='table'>{$allstamp}</td>
     <td colspan='6' class='table' style='background-color:#dedee3'>ความเสียหายต่อทรัพย์สิน (ผู้เอาประกัน)</td>
   </tr>
   <tr>
     <td class='table'>{$offerpaymentvaluename3}</td>
     <td class='table'>{$insurancevat}</td>
     <td class='table'>{$actvat}</td>
     <td class='table'>{$taxvat}</td>
     <td class='table'>{$allvat}</td>
     <td class='table' style='background-color:#dedee3'>{$offervaluename5}</td>
     <td class='table'>{$offervalue5}</td>
     <td class='table' style='background-color:#dedee3'>{$offervaluename7}</td>
     <td class='table'>{$offervalue7}</td>
     <td class='table' style='background-color:#dedee3'>{$offervaluename14}</td>
     <td class='table'>{$offervalue14}</td>
   </tr>
   <tr>
     <td class='table'>ยอดสุทธิก่อนหัก ณ ที่จ่าย</td>
     <td class='table'>{$calculatebeforetaxdeductinsurance}</td>
     <td class='table'>{$calculatebeforetaxdeductact}</td>
     <td class='table'>{$calculatebeforetaxdeducttax}</td>
     <td class='table'>{$allcalculatebeforetaxdeduct}</td>
     <td colspan='3' class='table' style='background-color:#dedee3'>ชดเชยความผิดส่วนแรก</td>
     <td colspan='3'  class='table'>{$offervalue6}</td>
   </tr>
   <tr>
     <td class='table'>ภาษี หัก ณ ที่จ่าย</td>
     <td class='table'>{$offerinsurancepaymenttaxdeduction}</td>
     <td class='table'>{$offeractpaymenttaxdeduction}</td>
     <td class='table'>{$offertaxpaymenttaxdeduction}</td>
     <td class='table'>{$alltaxdeduct}</td>
     <td colspan='6' class='table' style='background-color:#dedee3'>ความคุ้มครองต่อบุคคล(เสียชีวิต,สูญเสียอวัยวะ,ทุพพลภาพถาวร)</td>
   </tr>
   <tr>
     <td class='table'>ยอดสุทธิหลังหัก ณ ที่จ่าย</td>
     <td class='table'>{$calculateaftertaxdeductinsurance}</td>
     <td class='table'>{$calculateaftertaxdeductact}</td>
     <td class='table'>{$calculateaftertaxdeducttax}</td>
     <td class='table'>{$allcalculateaftertaxdeduct}</td>
     <td class='table' style='background-color:#dedee3'>{$offervaluename8}</td>
     <td class='table'>{$offervalue8}</td>
     <td class='table' style='background-color:#dedee3'>{$offervaluename19}</td>
     <td class='table'>{$offervalue19}</td>
     <td class='table' style='background-color:#dedee3'>{$offervaluename9}</td>
     <td class='table'>{$offervalue9}</td>
    </tr>
   <tr>
     <td colspan='5'style='background-color:#dedee3'>รายละเอียดเพิ่มเติม</td>
     <td class='table' style='background-color:#dedee3'>{$offervaluename10}</td>
     <td colspan='2'class='table'>{$offervalue10}</td>
     <td class='table' style='background-color:#dedee3'>{$offervaluename11}</td>
     <td colspan='2'class='table'>{$offervalue11}</td>

   <tr>
     <td colspan='5'class='table'>$moredetail</td>
     <td class='table' style='background-color:#dedee3'>{$offervaluename12}</td>
     <td colspan='2'class='table'>{$offervalue12}</td>
     <td class='table' style='background-color:#dedee3'>{$offervaluename13}</td>
     <td colspan='2'class='table'>{$offervalue13}</td>
   </tr>
   <tr>
     <td colspan='11'class='table'>ที่อยู่จัดส่งเอกสารใบหัก ณ ที่จ่าย Wealth Thai Insurance ชั้น4 อาคารชาร์เตอร์เฮาส์ เลขที่ 23 ซอย ลาดพร้าว 124 (สวัสดิการ) แขวงพลับพลา เขตวังทองหลาง กทม. 10310</td>

   </tr>

 </table>
 <div class='footer'>
   <p style='font-size:15.5px ;line-height: 2px';color:red;border-bottom:solid red 0.5px>หมายเหตุ</p>
   <p style='font-size:15.5px ;line-height: 2px'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     1. ทั้งนี้ราคาที่เสนอดังกล่าวอาจมีการเปลี่ยนแปลงได้หากกรมธรรมเดิมยังไม่หมดอายุและมีการเคลมประกันค่าเสียหายในระยะเวลา ก่อนกรมธรรมเดิมหมดอายุและลูกค้าต้องการใช้ประกันภัยบริษัทเดิม </p>
   <p style='font-size:15.5px ;line-height: 2px'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     2. ในกรณีเจ้าของกรรมธรรมเป็นนิติบุคคลและต้องการหัก ณ ที่จ่าย กรุณาแจ้งให้กับทางผู้แนะนำ [{$username},  {$usermobile} ] ของท่านเพื่อดำเนินการ </p>
   <p style='font-size:15.5px ;line-height: 2px'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     3. หากชำระเงินผ่านการโอนเงินทางธนาคาร ให้ผู้โอน ถ่ายสลิปหรือหลักฐานการโอนไว้ แล้วส่งให้กับผู้แนะนำเพื่อดำเนินการ หรือในกรณีชำระเป็นเช็ค หรือมีการออกใบหัก ณ ที่จ่าย รบกวนติดต่อ ผู้แนะนำ </p>
   <p style='font-size:15.5px ;line-height: 2px'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     เพื่อดำเนินการในส่วนที่เหลือต่อไป หรือในกรณีที่ติดต่อผู้แนะนำไม่ได้ สามารถติดต่อที่ Customer Help Desk ของเราได้ที่เบอร์ [094-165-6019]</p>

 </div></body>";
 $filename = "Invoice_".$customername;
 // include autoloader
 // reference the Dompdf namespace
 // instantiate and use the dompdf class
 $dompdf = new Dompdf();
 $dompdf->loadHtml($html);
 // (Optional) Setup the paper size and orientation
 $dompdf->setPaper('A4', 'landscape');
 // Render the HTML as PDF
 $dompdf->render();
 // Output the generated PDF to Browser
 return $dompdf->stream($filename);
      }
}
