<?php
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/********************** START EMAIL VERIFICATION ********************/
//View instructing the user to click the email verification link that was emailed to them by Laravel after registration
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

//Route that will handle requests generated when the user clicks the email verification link that was emailed to them
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $user  = User::find($request->id);
    if($user) {
        if($user->agent) {
            $user->agent->inactive = 0;
            $user->agent->save();
        }
    }
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

//Resend verification link. Call ths route from verification view by adding form/button to resubmit
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
/********************** END EMAIL VERIFICATION ********************/
