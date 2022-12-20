
<!DOCTYPE html>
<html lang="en-Us">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    </head>
    <body class="bg-white">
        <div class="page-wrapper w-100 d-block mx-auto color-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body border-blue bg-white">
                            <div class="email-body">
                                <h5>Dear <span class="text-danger">User</span></h5>
                                <p class="text-info-details">Your Verification code for Todo Application is mentioned below</p>
                                <p><b>{{$verification_code}}</b></p>
                            </div>
                            <hr>
                            <div class="email-footer my-2">
                                <p class="mb-0">Kind Regards</p>
                                <p class="mb-0"><b class="text-danger">Brand 786</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
