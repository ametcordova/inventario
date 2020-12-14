<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

 
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.21/b-1.6.2/r-2.2.4/sc-2.0.2/datatables.min.css"/>
</head>
<body>
    
</body>
</html>
<div id="app" class="container">
<h3 class="mt-3">Add User</h3>
  <hr>
<div class="row">
    <div class="col">
      <label>User ID</label>
      <input type="number" class="form-control" v-model="id">
    </div>

    <div class="col">
      <label>User name</label>
      <input type="text" class="form-control" v-model="name">
    </div>

    <div class="col">
      <label>User Email</label>
      <input type="text" class="form-control" v-model="email">
    </div>
    
  </div>
  <button class="btn btn-info mt-2" @click="addUser">Add User</button>
  <hr>
  
  <div class="row">
    <div class="col">
          <!-- <table id="user-table" class="display table-bordered nowrap" cellspacing="0" width="100%"> -->
          <table id="user-table" class="table table-bordered compact striped hover dt-responsive TablaFamilias" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Familia</th>
          <th>Ult. Mod</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div>
</div>

<!-- development version, includes helpful console warnings -->

<script defer src="vistas/js/app.js"></script>


