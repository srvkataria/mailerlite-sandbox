<?php 
	//include("AppController.php"); 
	//$controller = new AppController();	
//include 'db_connection.php';
//$conn = OpenCon();
//echo "Connected Successfully";
//CloseCon($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MailerLite SandBox</title>
    <!-- Bootstrap core CSS-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.compatibility.js" integrity="sha256-MWsk0Zyox/iszpRSQk5a2iPLeWw0McNkGUAsHOyc/gE=" crossorigin="anonymous"></script>
    <!-- Page level plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js" integrity="sha256-JG6hsuMjFnQ2spWq0UiaDRJBaarzhFbUxiUTxQDA9Lk=" crossorigin="anonymous"></script>
    <script src="assets/mlsandbox.js"></script>
    <link href="assets/mlsandbox.css" rel="stylesheet">
</head>
<body id="ml-page">
    <div class="section-header">
        <div class="header-logo"></div>
        <div class="header-title"><b>Sandbox Environment</b> for managing <span class="code-text">subscribers</span> & <span class="code-text">fields</span> in <span class="code-text">PHP</span></div>
        <div class="header-links">
            <ul>
                <li class="link link-subscribers active" section="subscriber-section"><i class="fa fa-user"></i>&nbsp;&nbsp;Subscribers</li>
                <li class="link link-fields" section="fields-section"><i class="fa fa-link"></i>&nbsp;&nbsp;Fields</li>
                <li class="link link-tests" section="test-section"><i class="fa fa-code"></i>&nbsp;&nbsp;Tests</li>
                <li class="link link-docs" section="docs-section"><i class="fa fa-file"></i>&nbsp;&nbsp;Docs</li>
            </ul>
        </div>
    </div>
    <div class="section subscriber-section">
        <div class="section-content">
            <div class="row">
                <div class="col-md-12">
                    <button id="btn-add-subscriber" class="btn btn-action" >Add new subscriber</button>
                </div>
            </div>
            <div id='add-subscriber-modal' class='modal'>
                <!-- Modal content -->
                <div class='modal-content add-subscriber-modal-content'>

                </div>
            </div>
            
            <div id='edit-subscriber-modal' class='modal'>
                <!-- Modal content -->
                <div class='modal-content edit-subscriber-modal-content'>

                </div>
            </div>
            
            <div class="table-responsive">
                <table id="subscriber-table" class="table table-bordered" width="100%" cellspacing="0" style="font-size: 13px;">
                  <thead>
                    <tr>
                      <th>Email</th>
                      <th>Name</th>
                      <th>Status</th>
                      <th>Source</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="section fields-section" style="display: none;">
        <div class="section-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="title-1">Create new field</div>
                        </div>
                    </div>
                    <div id="field-error-msg-block" class="row" style="display: none;">
                        <div class="col-md-12">
                        <p class="field-error-text" style=""></p>
                        </div>
                    </div>
                    <div id="field-success-msg-block" class="row" style="display: none;">
                        <div class="col-md-12">
                        <p class="field-success-text">New field created &#10003;</p>
                        </div>
                    </div>

                    <hr/>
                    <div class="row">
                        <div class="col-md-3 field-inputs">
                            <input type="text" id="field-name" class="form-control" placeholder="Field Name" style="font-size: 12px;"/>
                        </div>
                        <div class="col-md-3 field-inputs">
                            <input type="hidden" id="field-type">
                            <div class="dropdown field-type-dropdown" >
                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                  Type
                                </button>
                                <div class="dropdown-menu" id="field-type-dropdown-options">
                                  <a class="dropdown-item" data-value="Text">Text</a>
                                  <a class="dropdown-item" data-value="Number">Number</a>
                                  <a class="dropdown-item" data-value="Date">Date</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 field-inputs">
                            <button id="btn-crt-field" class="btn btn-action-main" >Create</button>
                        </div>
                    </div>
                    <hr/>
                </div>
            </div>
            <div class="table-responsive">
                <table id="fields-table" class="table table-bordered" width="100%" cellspacing="0" style="font-size: 13px;">
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th>Type</th>
                      <th>Created_On</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="section test-section" style="display: none;">
        <div class="section-content">
            <div class="row">
                <div class="col-md-12">
                    <p align="center">The API usage, instructions & examples are documented here, <a target="_blank" href="https://documenter.getpostman.com/view/9252054/SVzw51h9?version=latest#intro">MailerLite SandBox API Docs</a></p>

                    <p align="center">Some example API tests for get, create, update & delete operations also exist in the tests folder inside home directory.</p>

                    <p align="center">These tests are implemented using PHP-Curl & can easily be run in the web browser. Example: 
                        <br/>
                        <a target="_blank" href="http://localhost/mailerlite-sandbox/tests/get-subscribers.php">http://localhost/mailerlite-sandbox/tests/get-subscribers.php</a>
                        <br/>
                        <a target="_blank" href="http://localhost/mailerlite-sandbox/tests/create-subscriber.php">http://localhost/mailerlite-sandbox/tests/create-subscriber.php</a>
                        <br/>
                        <a target="_blank" href="http://localhost/mailerlite-sandbox/tests/edit-subscriber.php">http://localhost/mailerlite-sandbox/tests/edit-subscriber.php</a>
                        <br/>
                        <a target="_blank" href="http://localhost/mailerlite-sandbox/tests/delete-subscriber.php">http://localhost/mailerlite-sandbox/tests/delete-subscriber.php</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="section docs-section" style="display: none;">
        <div class="section-content">
            <div class="row">
                <div class="col-md-12">
                    <p align="center">Project structure, source-code & installation documentation is availavailable at, <a target="_blank" href="https://documenter.getpostman.com/view/9252054/SVzw51h9?version=latest#intro">MailerLite SandBox Github Page</a></p>

                    <p align="center">The API usage, instructions & examples are documented here, <a target="_blank" href="https://documenter.getpostman.com/view/9252054/SVzw51h9?version=latest#intro">MailerLite SandBox API Docs</a></p>
                    <p align="center"><a target="_blank" href="https://www.youtube.com/watch?v=xCi9ggfJntM&feature=youtu.be">MailerLite Sandbox Web Interface Demo Video Link</a></p>
                    
                </div>
            </div>
        </div>
    </div>
    <p align="center" style="font-size: 15px; font-weight: 700; color: #887979; font-style: italic; margin-top: 30px;">
        <i class="fa fa-coffee"></i>&nbsp;&nbsp;Cheers!
    </p> 
</body>