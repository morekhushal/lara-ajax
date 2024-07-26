<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h2>Login</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="email" id="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="password" id="password" class="form-control">
                        </div>
                        <button id="logginButton" class="btn btn-primary">Login</button>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('#logginButton').on('click', function(){
                const email = $('#email').val();
                const password = $('#password').val();

                $.ajax({
                    url:'/api/login',
                    type:'POST',
                    contentType:'application/json',
                    data:JSON.stringify({
                        email:email, 
                        password:password
                    }),
                    success: function(response){
                        console.log('response', response);
                        localStorage.setItem('api_token', response.token);
                        window.location.href = '/allposts';
                    },
                    error: function(xhr, status, error) {
                        alert('Error:'+xhr.responseText);
                    }
                })
            });
        });
    </script>
</body>
</html>