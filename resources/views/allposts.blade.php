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
                <h1>All Posts</h1>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-8">
            <a href="/addpost" class="btn btn-sm btn-primary">Add New</a>
            <button class="btn btn-sm btn-danger" id="logoutBtn">Logout</button>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div id="postContainer">
                
            </div>
        </div>
    </div>
    
    {{-- View Post Modal --}}
    <div class="modal fade" id="singlePostModal" tabindex="-1" aria-labelledby="singlePostModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="singleModalLabel">Single Post</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      {{-- View Post Modal --}}
    <div class="modal fade" id="updatePostModal" tabindex="-1" aria-labelledby="updatePostLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="updatePostLabel">Update Post</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateform">
                <div class="modal-body">
                    <input type="hidden" id="postId" class="form-control">
                    <b>Title</b> <input type="text" id="postTitle" class="form-control mb-3" placeholder="title">
                    <b>Description</b> <textarea id="postBody" cols="30" rows="10" class="form-control mb-3"></textarea>
                    <img id="showImage" width="150px">
                    <p>Upload image</p> <input type="file" id="postImage" class="form-control mb-3">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" value="Save changes" class="btn btn-primary">
                </div>
            </form>
        </div>
        </div>
      </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        document.querySelector('#logoutBtn').addEventListener('click', function(){
            const token = localStorage.getItem('api_token');
            fetch('/api/logout', {
                method:'POST',
                headers:{
                    'Authorization':`Bearer ${token}`
                }
            })
            .then(response=>response.json())
            .then(data =>{
                console.log('dt', data);
                window.location.href = 'http://localhost:8000/';
            });
        });

        function loadData() {
            const token = localStorage.getItem('api_token');
            fetch('/api/posts', {
                method:'GET',
                headers:{
                    'Authorization':`Bearer ${token}`
                }
            })
            .then(response=>response.json())
            .then(data =>{
                const postContainer = document.querySelector('#postContainer');
                var allpost = data.data.posts;
                var tabledata = `<table class="table table-borderd">
                    <tr class="table-dark">
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>View</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>`;
                allpost.forEach(post => {
                    tabledata += `<tr>
                           <td><img src="/uploads/${post.image}" width="150px"/></td> 
                           <td><h6>${post.title}</h6></td> 
                           <td><p>${post.description}</p></td> 
                           <td><button type="button" class="btn btn-sm btn-primary" data-bs-postid="${post.id}" data-bs-toggle="modal" data-bs-target="#singlePostModal" >View</button></td> 
                           <td><button type="button" class="btn btn-sm btn-success" data-bs-postid="${post.id}" data-bs-toggle="modal" data-bs-target="#updatePostModal" >Update</button></td> 
                           <td><button type="button" class="btn btn-sm btn-danger" onclick="deletePost(${post.id})">Delete</button></td> 
                    </tr>`;
                })
                tabledata+=`</table>`;
                postContainer.innerHTML = tabledata;
            });
        }
        loadData(); 

        //Open single post modal
        var singleModel = document.querySelector('#singlePostModal');
        if(singleModel) {
            singleModel.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget

                const modalBody = document.querySelector('#singlePostModal .modal-body');
                modalBody.innerHTML = '';
                
                var id = button.getAttribute('data-bs-postid');
                const token = localStorage.getItem('api_token');
                fetch(`/api/posts/${id}`, {
                    method:'GET',
                    headers:{
                        'Authorization':`Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response=>response.json())
                .then(data =>{
                    const post = data.data.post[0];                    
                    modalBody.innerHTML = `
                        Title: ${post.title}
                        <br>
                        Description: ${post.description}
                        <br>
                        <img width="150px" src="http://localhost:8000/uploads/${post.image}">
                    `;
                });
            })
        }

        //Update post
        var updateModal = document.querySelector('#updatePostModal');
        if(updatePostModal) {
            updateModal.addEventListener('show.bs.modal', (event) => {
                var button = event.relatedTarget
                
                var id = button.getAttribute('data-bs-postid');
                const token = localStorage.getItem('api_token');
                fetch(`/api/posts/${id}`, {
                    method:'GET',
                    headers:{
                        'Authorization':`Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response=>response.json())
                .then(data =>{
                    const post = data.data.post[0]; 
                    document.querySelector('#postId').value = post.id;
                    document.querySelector('#postTitle').value = post.title;
                    document.querySelector('#postBody').value = post.description;
                    document.querySelector('#showImage').src = `uploads/${post.image}`;
                });
            })
        }

        //Update post modal
        var updateform = document.querySelector('#updateform');
        updateform.onsubmit = async (e) => {
            e.preventDefault();
            const token = localStorage.getItem('api_token');
            const postId = document.querySelector('#postId').value;
            const title = document.querySelector('#postTitle').value;
            const description = document.querySelector('#postBody').value;

            var formData = new FormData();
            formData.append('id', postId);
            formData.append('title', title);
            formData.append('description', description);

            if(!document.querySelector('#postImage').files[0] == ""){
                const image = document.querySelector('#postImage').files[0];
                formData.append('image', image);
            }

            let response = await fetch(`/api/posts/${postId}`, {
                method:'POST',
                body: formData,
                headers:{
                    'Authorization':`Bearer ${token}`,
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(response=>response.json())
            .then(data =>{
                window.location.href = 'http://localhost:8000/allposts';
            });
        }

        //Delete Post
        async function deletePost(postId) {
            const token = localStorage.getItem('api_token');

            let response = await fetch(`/api/posts/${postId}`, {
                method:'DELETE',
                headers:{
                    'Authorization':`Bearer ${token}`
                }
            })
            .then(response=>response.json())
            .then(data =>{
                window.location.href = 'http://localhost:8000/allposts';
            });
        }
    </script>
</body>
</html>

