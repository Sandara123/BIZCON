<?php include('includes/header.php');
include('server/connection.php')?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4> Account List
              </h4>
            </div>
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Database ID</td>
                    <td>Database Name</td>
                    <td>Database Username</td>
                    <td><button type="submit" name="delete" class="btn btn-danger">Delete</button><td>
                  </tr>
                </tbody>
              </table>
              
              </div>
          </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>