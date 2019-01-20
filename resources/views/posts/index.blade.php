
<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>       
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <br />
    <h3 align="center">Our Posts</h3>
    <br />
    <div align="right">
        <button type="button" name="add" id="add_post" class="btn btn-success btn-sm">Add Post</button>
    </div>
    <br />
    <table id="posts_table" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
<div id="postsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="posts_form">
                <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h4 class="modal-title">Add Post</h4>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <span id="form_output"></span>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" id="title" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                      	<textarea name="content" class="form-control" rows="5" id="content"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                	<input type="hidden" name="post_id" id="post_id" value="" />
                    <input type="hidden" name="button_action" id="button_action" value="insert" />
                    <input type="submit" name="submit" id="action" value="Add" class="btn btn-info" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
     $('#posts_table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "{{ route('getPosts') }}",
        "columns":[
            { "data": "title" },
            { "data": "content" },
            { "data": "action", orderable:false, searchable: false}
        ]
     });
    $('#add_post').click(function(){
        $('#postsModal').modal('show');
        $('#posts_form')[0].reset();
        $('#form_output').html('');
        $('#button_action').val('insert');
        $('#action').val('Add');
    });

    $('#posts_form').on('submit', function(event){
        event.preventDefault();
        var form_data = $(this).serialize();
        console.log("post");
        $.ajax({
            url:"{{route('store')}}",
            method:"POST",
            data:form_data,
            dataType:"json",
            success:function(data)
            {
            	console.log("waleed");
                if(data.error.length > 0)
                {
                    var error_html = '';
                    for(var count = 0; count < data.error.length; count++)
                    {
                        error_html += '<div class="alert alert-danger">'+data.error[count]+'</div>';
                    }
                    $('#form_output').html(error_html);
                }
                else
                {
                    $('#form_output').html(data.success);
                    $('#posts_form')[0].reset();
                    $('#action').val('Add');
                    $('.modal-title').text('Add Post');
                    $('#button_action').val('insert');
                    $('#posts_table').DataTable().ajax.reload();
                }
            }
        })
    });
    
    $(document).on('click', '.edit', function(){
        var id = $(this).attr("id");
        $('#form_output').html('');
        $.ajax({
            url:"{{route('getPost')}}",
            method:'get',
            data:{id:id},
            dataType:'json',
            success:function(data)
            {
                $('#title').val(data.title);
                $('#content').val(data.content);
                $('#post_id').val(id);
                $('#postsModal').modal('show');
                $('#action').val('Edit');
                $('.modal-title').text('Edit Data');
                $('#button_action').val('update');
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var id = $(this).attr('id');
        if(confirm("Are you sure you want to Delete this post?"))
        {
            $.ajax({
                url:"{{route('deletePost')}}",
                mehtod:"get",
                data:{id:id},
                success:function(data)
                {
                    alert(data);
                    $('#posts_table').DataTable().ajax.reload();
                }
            })
        }
        else
        {
            return false;
        }
    }); 
});
</script>
</body>
</html>

