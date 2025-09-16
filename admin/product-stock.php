<?php include('includes/header.php');
include('server/connection.php')?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4> Product List
              </h4>
            </div>
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Product Name</th>
                    <th>Stocks</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Database ID</td>
                    <td>Database Category</td>
                    <td>Database Product Name</td>
                    <td>Database Stocks</td>
                    <td><button type="submit" name="delete" class="btn btn-success">Add Stocks</button>
                    <button type="submit" name="delete" class="btn btn-danger">Sold Out</button><td>
                  </tr>
                </tbody>
              </table>
              
              </div>
          </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>