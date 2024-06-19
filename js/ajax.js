$(document).ready(function() {
    // Automatically fetch user data on page load
    fetchUserPost();
    fetchCategory();
    fetchUser();

    function fetchUserPost() {
        $.ajax({
            url: '../admin/controller/post.php?read=1',
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#postData').html(response);  // Make sure #postData exists in your HTML
            },
            error: function(xhr, status, error) {
                console.log("Error occurred:");
                //console.log(xhr.responseText); // Log the full response for debugging
                console.log(status + ': ' + error);
            }
        });
    }

    function fetchCategory() {
        $.ajax({
            url: '../admin/controller/category.php?cate=2',
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#categoryData').html(response);  // Make sure #categoryData exists in your HTML
            },
            error: function(xhr, status, error) {
                console.log("Error occurred:");
                // console.log(xhr.responseText); // Log the full response for debugging
                console.log(status + ': ' + error);
            }
        });
    }

    function fetchUser() {
        $.ajax({
            url: '../admin/controller/user.php?user=3',
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#userData').html(response);  // Make sure #UsersData exists in your HTML
            },
            error: function(xhr, status, error) {
                console.log("Error occurred:");
                //console.log(xhr.responseText); // Log the full response for debugging
                console.log(status + ': ' + error);
            }
        });
    }

    // Category
    // Send data from category form
    $(document).on('submit', '#add-category-form', function(e) {
        e.preventDefault();  // Correct the method name
        const formData = new FormData(this);  // Pass the form element to FormData
        formData.append("add", 1);  // Append additional data if needed
    
        $.ajax({
            url: '../admin/controller/category.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,  // Prevent jQuery from automatically transforming the data into a query string
            contentType: false,  // Prevent jQuery from overriding the content type
            success: function(response) {
                if (response.status === 'success') {
                    $('.showAlert').html('<div class="alert__message success">' + response.message + '</div>');
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 2000);  // Redirect after 2 seconds
                } else {
                    $('.showAlert').html('<div class="alert__message error">' + response.message + '</div>');
    
                    // Re-populate the form fields
                    if(response.data) {
                        $('input[name="title"]').val(response.data.title);
                            $('textarea[name="body"]').val(response.data.body);
                    }
    
                    // Clear error messages after 2 seconds
                    setTimeout(function(){
                        $('.showAlert').empty();
                    }, 2000);
                    
                }
            },
            error: function(xhr, status, error) {
                console.log("Error occurred:");
                // console.log(xhr.responseText);  // Log the full response for debugging
                console.log(status + ': ' + error);
            }
        });
    });

    // Edit category
    $('#categoryData').on('click', 'a.editLink', function(e) {
        e.preventDefault();
        let id = $(this).data('id');  // Use data() to fetch the data-id attribute value
        // console.log(id);

        // Redirect to edit-category.php with the category ID as a URL parameter
        window.location.href = 'edit-category.php?id=' + id;
        
        // Now you can use the ID to perform any actions like fetching category details for editing
        // fetchCategoryDetails(id);
    });
    
    // Delete category
    $('#categoryData').on('click', 'a.deleteLink', function(e) {
        e.preventDefault();
        let deleteCategoryid = $(this).data('id');  // Use data() to fetch the data-id attribute value
        // console.log(deleteCategoryid);

        // Redirect to edit-category.php with the category ID as a URL parameter
        ;
        
        // Now you can use the ID to perform any actions like fetching category details for editing
        fetchCategoryDetails(deleteCategoryid);
    });

    function fetchCategoryDetails(deleteCategoryid) {
        // Example AJAX call to fetch category details using the ID
        $.ajax({
            url: `../admin/controller/category.php?deleteCategory=1&id${deleteCategoryid}`, // Your backend endpoint to fetch category details
            type: 'GET',
            data: { id: deleteCategoryid },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('.showAlert').html('<div class="alert__message success">' + response.message + '</div>');
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 2000);
                } else {
                    $('.showAlert').html('<div class="alert__message error">' + response.message + '</div>');

                    // Clear error messages after 2 seconds
                    setTimeout(function(){
                        $('.showAlert').empty();
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error occurred:");
                //console.log(xhr.responseText); // Log the full response for debugging
                console.log(status + ': ' + error);
            }
        });
    }

    // Post
    // Send data from post form
    $(document).on('submit', '#add-post', function(e) {
        e.preventDefault();  // Correct the method name
        const formData = new FormData(this);  // Pass the form element to FormData
        formData.append("add", 1);  // Append additional data if needed
    
        $.ajax({
            url: '../admin/controller/post.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,  // Prevent jQuery from automatically transforming the data into a query string
            contentType: false,  // Prevent jQuery from overriding the content type
            success: function(response) {
                if (response.status === 'success') {
                    $('.showAlert').html('<div class="alert__message success">' + response.message + '</div>');
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 2000);  // Redirect after 2 seconds
                } else {
                    $('.showAlert').html('<div class="alert__message error">' + response.message + '</div>');
                    if(response.data) {
                        // Re-populate the form fields
                        $('input[name="title"]').val(response.data.title);
                        $('select[name="category"]').val(response.data.category_id);
                        $('textarea[name="body"]').val(response.data.body);
                        $('input[name="thumbnail"]').val(response.data.thumbnail);
                        if(response.data.is_featured) {
                            $('input[name="is_featured"]').prop('checked', true);
                        } else {
                            $('input[name="is_featured"]').prop('checked', false);
                        }
                    }
                    // Clear error messages after 2 seconds
                    setTimeout(function(){
                        $('.showAlert').empty();
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error occurred:");
                console.log(xhr.responseText); // Log the full response for debugging
                console.log(status + ': ' + error);
            }
        });
    });

    // Edit post
    $('#postData').on('click', 'a.editPost', function(e) {
        e.preventDefault();
        let id = $(this).data('id');  // Use data() to fetch the data-id attribute value
        // console.log(id);

        // Redirect to edit-category.php with the category ID as a URL parameter
        window.location.href = 'edit-post.php?id=' + id;
        
        
    });

    $(document).on('submit', '#edit-post-form', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("update", 1);

        $.ajax({
            url: '../admin/controller/post.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    $('.showAlert').html('<div class="alert__message success">' + response.message + '</div>');
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 1000);
                } else {
                    $('.showAlert').html('<div class="alert__message error">' + response.message + '</div>');

                    // Clear error messages after 2 seconds
                    setTimeout(function(){
                        $('.showAlert').empty();
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error occurred:");
                console.log(xhr.responseText);
                console.log(status + ': ' + error);
            }
        });
    });

     // Delete post
     $('#postData').on('click', 'a.deletePost', function(e) {
        e.preventDefault();
        let deletePostid = $(this).data('id');  // Use data() to fetch the data-id attribute value
        // console.log(deleteid);

        // Redirect to edit-category.php with the category ID as a URL parameter
        ;
        
        // Now you can use the ID to perform any actions like fetching category details for editing
        fetchPostDetails(deletePostid);
    });

    function fetchPostDetails(deletePostid) {
        // Example AJAX call to fetch category details using the ID
        $.ajax({
            url: `../admin/controller/post.php?deletePost=1&id${deletePostid}`, // Your backend endpoint to fetch category details
            type: 'GET',
            data: { id: deletePostid },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('.showAlert').html('<div class="alert__message success">' + response.message + '</div>');
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 2000);
                } else {
                    $('.showAlert').html('<div class="alert__message error">' + response.message + '</div>');

                    // Clear error messages after 2 seconds
                    setTimeout(function(){
                        $('.showAlert').empty();
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error occurred:");
                console.log(xhr.responseText); // Log the full response for debugging
                console.log(status + ': ' + error);
            }
        });
    }

     // User
    // Send data from user form
    $(document).on('submit', '#add-user-form', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("addUser", 1);
    
        $.ajax({
            url: '../admin/controller/user.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    $('.showAlert').html('<div class="alert__message success">' + response.message + '</div>');
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 2000);  // Redirect after 2 seconds
                } else {
                    $('.showAlert').html('<div class="alert__message error">' + response.message + '</div>');
    
                    // Re-populate the form fields
                    if(response.data) {
                        $('input[name="firstname"]').val(response.data.firstname);
                        $('input[name="lastname"]').val(response.data.lastname);
                        $('input[name="username"]').val(response.data.username);
                        $('input[name="email"]').val(response.data.email);
                        $('select[name="userrole"]').val(response.data.userrole);
                    }
    
                    // Clear error messages after 2 seconds
                    setTimeout(function(){
                        $('.showAlert').empty();
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error occurred:");
                //console.log(xhr.responseText);
                console.log(status + ': ' + error);
            }
        });
    });

    // Edit user
    $('#userData').on('click', 'a.userEdit', function(e) {
        e.preventDefault();
        let id = $(this).data('id');  // Use data() to fetch the data-id attribute value
        // console.log(id);

        // Redirect to edit-category.php with the category ID as a URL parameter
        window.location.href = 'edit-user.php?id=' + id;
        
        
    });

    $(document).on('submit', '#edit-user-form', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("updateUser", 1);

        $.ajax({
            url: '../admin/controller/user.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    $('.showAlert').html('<div class="alert__message success">' + response.message + '</div>');
                    setTimeout(function() {
                        window.location.href = 'manage-user.php';
                    }, 1000);
                } else {
                    $('.showAlert').html('<div class="alert__message error">' + response.message + '</div>');

                    // Clear error messages after 2 seconds
                    setTimeout(function(){
                        $('.showAlert').empty();
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error occurred:");
                console.log(xhr.responseText);
                console.log(status + ': ' + error);
            }
        });
    });

     // Delete user
     $('#userData').on('click', 'a.userDelete', function(e) {
        e.preventDefault();
        let deleteUserid = $(this).data('id');  // Use data() to fetch the data-id attribute value
        // console.log(deleteid);

        // Redirect to edit-category.php with the category ID as a URL parameter
        ;
        
        // Now you can use the ID to perform any actions like fetching category details for editing
        fetchPostDetails(deleteUserid);
    });

    function fetchPostDetails(deleteUserid) {
        // Example AJAX call to fetch category details using the ID
        $.ajax({
            url: `../admin/controller/user.php?deleteUser=1&id${deleteUserid}`, // Your backend endpoint to fetch category details
            type: 'GET',
            data: { id: deleteUserid },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('.showAlert').html('<div class="alert__message success">' + response.message + '</div>');
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 2000);
                } else {
                    $('.showAlert').html('<div class="alert__message error">' + response.message + '</div>');

                    // Clear error messages after 2 seconds
                    setTimeout(function(){
                        $('.showAlert').empty();
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error occurred:");
                // console.log(xhr.responseText); // Log the full response for debugging
                console.log(status + ': ' + error);
            }
        });
    }
    
    


});
