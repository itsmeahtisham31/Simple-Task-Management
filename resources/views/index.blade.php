<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />

    <!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />

    <!-- DataTables Buttons Dependencies -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- DataTables Buttons CSS -->

    <!-- DataTables Buttons Dependencies -->
    <!-- Bootstrap CSS -->
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">


        <!-- Links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item ">
                <a class="nav-link" href="{{ route('logout') }}">Logout</a>
            </li>


        </ul>
    </nav>

    <div class="container">
        <br>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Task List</h1>
                    </div>
                    <div class="col-sm-6">
                        <button class="btn btn-primary" style="float:right" data-toggle="modal"
                            data-target="#exampleModal">New Task</button>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table class="table table-hover text-nowrap" id="datatable">

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <form id="taskform">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" id="task_id" name="task_id" value="">
                        <!-- Add a hidden input for task ID -->
                        <div class="form-group">
                            <label for="name">Name:*</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                value="">
                            <div id="name-error" class="error-message" style="color:red;"></div>
                        </div>

                        <div class="form-group">
                            <label for="categorySelect">Select Category</label>
                            <select class="form-control" id="categorySelect">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <ul id="errorlist"></ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitTask">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script>

        let editTaskId = null;
        $(document).ready(function() {

            // Load initial data into the DataTable
            loadDataTable();

            $('#exampleModal').on('show.bs.modal', function() {
                // Reset form fields
                $('#name').val('');
                $('#name-error').text('');
                $('#exampleModalLabel').text('Add Task');
                $('#submitTask').text('Add');
                
            });


            $('#submitTask').click(function() {
                var formData = new FormData();
                formData.append('name', $('#name').val());
                formData.append('category_id', $('#categorySelect').val());

                var requiredFields = ['name'];
                var hasEmptyField = false;

                for (var i = 0; i < requiredFields.length; i++) {
                    var field = requiredFields[i];
                    var value = $('#' + field).val().trim();

                    if (value === '') {
                        hasEmptyField = true;
                        $('#' + field + '-error').text('This field is required.'); // Display error message
                    } else {
                        $('#' + field + '-error').text('');
                    }
                }

                if (hasEmptyField) {
                    return; // Prevent form submission if there are empty required fields
                }

                

                var url = editTaskId ? '{{ route("tasks.update", ["id" => "__task_id"]) }}'.replace('__task_id', editTaskId) : '{{ route("tasks.store") }}';

                // Get CSRF token from the meta tag
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                formData.append('_token', csrfToken);

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Pass the CSRF token in the headers
                    },
                    processData: false, // Important: prevent jQuery from processing the data
                    contentType: false,
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#exampleModal').modal('hide');
                            editTaskId = null;
                            alert(response.message);
                            loadDataTable(); // Reload the page after successful submission
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });


        });






        // Function to load data into the DataTable
        var dataTable; // Declare a variable to hold the DataTable instance
       

        function loadDataTable() {
            // Check if DataTable exists
            if ($.fn.DataTable.isDataTable('#datatable')) {
                dataTable.destroy(); // Destroy existing DataTable
            }

            dataTable = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('get.data') }}",
                    type: "GET",
                },
                columns: [{
                        title: 'ID',
                        data: 'id',
                        name: 'id',
                        searchable:false
                        
                    },
                    {
                        title: 'Name',
                        data: 'name',
                        name: 'name',
                        searchable:false
                        
                    },
                    {
                        title: 'Category',
                        data: 'category_name',
                        name: 'category',
                        searchable:true
                        
                        
                    },
                    {
                        title: "Action",
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="btn btn-primary edit-button custom-header-class" onclick="editTask(' +
                                row.id + ')">Edit</button>' + "&nbsp" +
                                '<button class="btn btn-danger"  onclick="deleteTask(' + row.id +
                                ')">Delete</button>';
                        }
                    }
                ],
                language: {
                    emptyTable: "No Records Found"
                },
                scrollX: true,
 
            });
            

        }

        // Function to handle user deletion
        function deleteTask(id) {
            // Ask for confirmation before deleting
            if (confirm("Are you sure you want to delete this task?")) {
                let delurl = `tasks/${id}`;
                $.ajax({
                    url: delurl,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert("Successfully deleted");
                        loadDataTable();
                    },
                    error: function(xhr, status, error) {
                        alert("Error");
                    }
                });
            }
        }

        function editTask(id) {
            editTaskId = id;
            let editurl = `tasks/${editTaskId}/edit`;
            $.ajax({
                type: "GET",
                url: editurl,
                success: function(response) {
                    if (response.status == 'success') {
                        $('#exampleModal').modal('show');
                        var task = response.task;
                        $('#name').val(task.name);
                        $('#exampleModalLabel').text('Update Task');
                        $('#submitTask').text('Update');
                        var categoryId = task.category_id; // Assuming category_id is present in the task object

                        // Loop through the options and set the selected attribute for the appropriate option
                        $('#categorySelect').find('option').each(function() {
                            if ($(this).val() == categoryId) {
                                $(this).prop('selected', true);
                            }
                        });
                    } else {
                        alert('Error')
                    }

                }

            })
        }
    </script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>

</html>
