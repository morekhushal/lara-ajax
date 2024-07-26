<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-8 bg-primary text-white mb-4">
                <h1>Create Post</h1>
            </div>
        </div>
    
        <div class="row mb-4">
            <div class="col-8">
                <form id="addform" >
                    <input type="text" id="title" class="form-control mb-3" placeholder="title">
                    <textarea id="description" cols="30" rows="10" class="form-control mb-3"></textarea>
                    <input type="file" id="image" class="form-control mb-3">
                    <input type="submit" class="btn btn-primary">
                    <a href="/allposts" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        var addform = document.querySelector('#addform');
        addform.onsubmit = async (e) => {
            e.preventDefault();
            const token = localStorage.getItem('api_token');
            const title = document.querySelector('#title').value;
            const description = document.querySelector('#description').value;
            const image = document.querySelector('#image').files[0];

            var formData = new FormData();
            formData.append('title', title);
            formData.append('description', description);
            formData.append('image', image);

            let response = await fetch('/api/posts', {
                method:'POST',
                body: formData,
                headers:{
                    'Authorization':`Bearer ${token}`
                }
            })
            .then(reponse=>response.json())
            .then(data =>{
                window.location.href = 'http://localhost:8000/allposts';
            });
        }
    </script>
</body>
</html>

