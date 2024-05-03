<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\BankTransactionController;
use App\Http\Controllers\ProviderTransactionController;
use App\Http\Controllers\CardTransactionController;
use App\Http\Controllers\UserTransactionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CardDetailsController;
use App\Http\Controllers\TicketBookingController;
use App\Http\Controllers\TicketCustomerDataController;
use App\Http\Controllers\DonationCustomerDataController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MobileInternetBillController;
use App\Http\Controllers\MobileInternetBillCustomerDataController;
use App\Http\Controllers\UtilityBillController;
use App\Http\Controllers\UtilityBillCustomerDataController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProviderWalletController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


///////////////Public APIs////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
                                                                                                        //
    //////User Sign Up And In APIs///////                                                               //
    Route::get('/users', [UserController::class, 'index']);                                             //
    Route::get('/SignIn', [UserController::class, 'show']);                                             //
    Route::get('/SignUp', [UserController::class, 'store']);                                            //
                                                                                                        //
    //////Provider Sign Up And In APIs///////                                                           //
    Route::post('/service-providers/SignUp', [ServiceProviderController::class, 'store']);              //
    Route::get('/service-providers/SignIn', [ServiceProviderController::class, 'show']);                //
                                                                                                        //
//////////////////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////Autherized APIs////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
Route::middleware(['auth:sanctum'])->group(function () {                                            //
                                                                                                    //
    //////User Sign Up And In APIs///////                                                           //
    Route::put('/users/{id}', [UserController::class, 'update']);                                   //
    Route::delete('/users/{id}', [UserController::class, 'destroy']);                               //
                                                                                                    //
    //////Show and Update Wallet APIs//////                                                         //
    Route::post('/wallets/ShowBalance', [WalletController::class, 'show']);                         //
    Route::post('/wallets/topup', [WalletController::class, 'topupWallet']);                        //
                                                                                                    /////////
    //////Provider Sign Up And In APIs///////                                                              //
    Route::put('/service-providers/{ServiceProviderID}', [ServiceProviderController::class, 'update']);    //
    Route::delete('/service-providers/{ServiceProviderID}', [ServiceProviderController::class, 'destroy']);//
                                                                                                           //
    //////Transaction APIs///////                                                                          //
    Route::post('/bank-transactions', [BankTransactionController::class, 'store']);                        //
    Route::get('/bank-transactions/show', [BankTransactionController::class, 'show']);                     //
                                                                                                           //
    Route::post('/provider-transactions', [ProviderTransactionController::class, 'store']);                //
    Route::get('/provider-transactions/show', [ProviderTransactionController::class, 'show']);             // 
                                                                                                           //
    Route::post('/card-transactions', [CardTransactionController::class, 'store']);                        //
    Route::get('/card-transactions/show', [CardTransactionController::class, 'show']);                     //
                                                                                                           //
    Route::post('/user-transactions', [UserTransactionController::class, 'store']);                        //
    Route::get('/user-transactions/show', [UserTransactionController::class, 'show']);                     // 
                                                                                                           //
    ///////Send Mail and collect APIs///////                                                               // 
    Route::post('/collect', [Controller::class, 'collect']);                                               // 
    Route::post('/send-verification-email', [Controller::class, 'sendVerificationEmail']);                 // 
    Route::post('/verify-code', [Controller::class, 'verifyCode']);                                        //
    Route::post('/get-User-History', [Controller::class, 'getUserHistory']);                               //
                                                                                                           //
    //////Bank account APIs///////                                                                         //
    Route::post('/bank-accounts/Create', [BankAccountController::class, 'store']);                         //
    Route::get('/bank-accounts/{id}', [BankAccountController::class, 'show']);                             //
    Route::put('/bank-accounts/{id}', [BankAccountController::class, 'update']);                           //
                                                                                                           //
    //////Add and update/delete Cards APIs///////                                                          //
    Route::post('/card-details/Create', [CardDetailsController::class, 'store']);                          //
    Route::get('/card-details/{user_id}', [CardDetailsController::class, 'showCards']);                    //
    Route::put('/card-details/{id}', [CardDetailsController::class, 'update']);                            //
    Route::delete('/card-details/{card_id}', [CardDetailsController::class, 'destroy']);                   //
                                                                                                           //
    //////Get all services API///////                                                                      //                                                      
    Route::post('/service-provider/services', [ServiceProviderController::class, 'showServices']);         //
                                                                                                           //    
                                                                                                           //    
    //////Service Provider Wallets APIs ///////                                                            //
    Route::get('/provider-wallet', [ProviderWalletController::class, 'show']);                             //                                                                                                       //
    Route::post('/transfer-to-bank', [ProviderWalletController::class, 'transferToBank']);                 //
                                                                                                           //
    //////Add and update/delete Ticket Service APIs///////                                                 //
    Route::get('/ticket-bookings', [TicketBookingController::class, 'index']);                             // 
    Route::post('/ticket-bookings/Create', [TicketBookingController::class, 'store']);                     //
    Route::get('/ticket-bookings/{id}', [TicketBookingController::class, 'show']);                         //
    Route::put('/ticket-bookings/{id}', [TicketBookingController::class, 'update']);                       //
    Route::delete('/ticket-bookings/{id}', [TicketBookingController::class, 'destroy']);                   //
                                                                                                           //
    //////Add and Show/delete TicketCustomer_Data APIs///////                                              //
    Route::get('/ticket-customer-data', [TicketCustomerDataController::class, 'index']);                   //
    Route::post('/ticket-customer-data/Create', [TicketCustomerDataController::class, 'store']);           //
    Route::get('/ticket-customer-data/{id}', [TicketCustomerDataController::class, 'show']);               //
    Route::delete('/ticket-customer-data/{id}', [TicketCustomerDataController::class, 'destroy']);         //
                                                                                                           //
    //////Add and Show/delete Donation APIs///////                                                         //
    Route::get('/donations', [DonationController::class, 'index']);                                        //
    Route::post('/donations', [DonationController::class, 'store']);                                       //
    Route::get('/donations/{id}', [DonationController::class, 'show']);                                    //
    Route::put('/donations/{id}', [DonationController::class, 'update']);                                  //
    Route::delete('/donations/{id}', [DonationController::class, 'destroy']);                              //
                                                                                                           //
    //////Add and Show/delete DonationCustomer_Data APIs///////                                            //
    Route::get('/donation-customer-data', [DonationCustomerDataController::class, 'index']);               //
    Route::post('/donation-customer-data', [DonationCustomerDataController::class, 'store']);              //
    Route::get('/donation-customer-data/{id}', [DonationCustomerDataController::class, 'show']);           //
    Route::delete('/donation-customer-data/{id}', [DonationCustomerDataController::class, 'destroy']);     //
                                                                                                           //
    //////Add and Show/delete MobileInternetBill APIs///////                                               //
    Route::get('/mobile-internet-bills', [MobileInternetBillController::class, 'index']);                  //
    Route::post('/mobile-internet-bills', [MobileInternetBillController::class, 'store']);                 //
    Route::get('/mobile-internet-bills/{id}', [MobileInternetBillController::class, 'show']);              //
    Route::put('/mobile-internet-bills/{id}', [MobileInternetBillController::class, 'update']);            //
    Route::delete('/mobile-internet-bills/{id}', [MobileInternetBillController::class, 'destroy']);        //
                                                                                                           //
    //////Add and Show/delete InternetBillCustomerDataController APIs///////                               ///////////////////
    Route::get('/mobile-internet-bill-customer-data', [MobileInternetBillCustomerDataController::class, 'index']);          //
    Route::post('/mobile-internet-bill-customer-data', [MobileInternetBillCustomerDataController::class, 'store']);         //
    Route::get('/mobile-internet-bill-customer-data/{id}', [MobileInternetBillCustomerDataController::class, 'show']);      //
    Route::delete('/mobile-internet-bill-customer-data/{id}', [MobileInternetBillCustomerDataController::class, 'destroy']);//
                                                                                                           ///////////////////
    //////Add and Show/delete UtilityBillController APIs///////                                            //
    Route::get('/utility-bills', [UtilityBillController::class, 'index']);                                 //
    Route::post('/utility-bills', [UtilityBillController::class, 'store']);                                //
    Route::get('/utility-bills/{id}', [UtilityBillController::class, 'show']);                             //
    Route::put('/utility-bills/{id}', [UtilityBillController::class, 'update']);                           //
    Route::delete('/utility-bills/{id}', [UtilityBillController::class, 'destroy']);                       //
                                                                                                           //
    //////Add and Show/delete UtilityBillCustomerDataController APIs///////                                //
    Route::get('/utility-bill-customer-data', [UtilityBillCustomerDataController::class, 'index']);        //
    Route::post('/utility-bill-customer-data', [UtilityBillCustomerDataController::class, 'store']);       //
    Route::get('/utility-bill-customer-data/{id}', [UtilityBillCustomerDataController::class, 'show']);    //////
    Route::delete('/utility-bill-customer-data/{id}', [UtilityBillCustomerDataController::class, 'destroy']); //
});                                                                                                          //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////