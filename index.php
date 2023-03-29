<?php
require_once('class/config.php');
require_once('autoload.php');
$contact = new Contact();
$name = '';
$birthdate = '';
$email = '';
$phone = '';
$maxBirth = date("Y-m-d");
//check isset for name, email, birth, phone, image, and not empty image
if (
    isset($_POST['name']) && isset($_POST['email']) && isset($_POST['birthdate']) && isset($_POST['phone']) && isset($_POST['submit']) && !($_FILES['contact-image']['error'] == 4 || ($_FILES['contact-image']['size'] == 0 && $_FILES['contact-image']['error'] == 0))
) {
    //anti inject
    $name = clearInputs($_POST['name']);
    $birthdate = clearInputs($_POST['birthdate']);
    $email = clearInputs($_POST['email']);
    $phone = clearInputs($_POST['phone']);
    //check if fields are empty
    if (empty($name) || empty($email) || empty($birthdate) || empty($phone)) {
        $globalError = "All fields are required.";
    } else {
        //validate image 
        if (checkImage($_FILES['contact-image'])) {
            $format = pathinfo($_FILES['contact-image']['name'], PATHINFO_EXTENSION);
            $imgName = uniqid() . ".$format";
            $tmp = $_FILES['contact-image']['tmp_name'];
        }
        //after all validation and insert, upload image
        if ($uploadOk) {
            $contact->setContactInfo($name, $birthdate, $email, $phone, $imgName);
            $contact->validateInfoInsert();
            if (isset($nameOkay) && isset($emailOkay) && isset($phoneOkay) && isset($birthOkay)) {
                if ($contact->insert()) {
                    move_uploaded_file($tmp, "contact-images/$imgName");
                    $globalSuccess = "New contact added!";
                }
            }
        }
    }
} else {
    //user submit with empty fields
    if (isset($_POST['submit'])) {
        $globalError = "All fields are required.";
    }
}


///EDIT PORTIONS \/
// validate image set
if (isset($_POST['editImage'])) {
    $imageOption = clearInputs($_POST['editImage'][0]);
}

if (
    isset($_POST['nameEdit']) && isset($_POST['emailEdit']) && isset($_POST['birthdateEdit']) && isset($_POST['phoneEdit']) && isset($_POST['submitEdit'])
) {
    //anti inject
    $nameEdit = clearInputs($_POST['nameEdit']);
    $birthdateEdit = clearInputs($_POST['birthdateEdit']);
    $emailEdit = clearInputs($_POST['emailEdit']);
    $phoneEdit = clearInputs($_POST['phoneEdit']);
    //check if fields are empty
    if (empty($nameEdit) || empty($emailEdit) || empty($birthdateEdit) || empty($phoneEdit)) {
        $globalErrorEdit = "All fields are required to edit.";
    } else {
        //validate image if option is to CHANGE image
        if ($imageOption == 'change') {
            if (!($_FILES['contact-image-edit']['error'] == 4 || ($_FILES['contact-image-edit']['size'] == 0 && $_FILES['contact-image-edit']['error'] == 0))) {
                if (checkImage($_FILES['contact-image-edit'])) {
                    $formatEdit = pathinfo($_FILES['contact-image-edit']['name'], PATHINFO_EXTENSION);
                    $imgNameEdit = uniqid() . ".$formatEdit";
                    $tmpEdit = $_FILES['contact-image-edit']['tmp_name'];
                } else {
                    $globalErrorEdit = "There is a problem with this file.";
                }

                if ($uploadOk) {
                    $contact->validateInfoUpdate($nameEdit, $emailEdit, $phoneEdit, $birthdateEdit);
                    if (isset($nameOkayEdit) && isset($emailOkayEdit) && isset($phoneOkayEdit) && isset($birthOkayEdit)) {
                        $idEdit = $_POST['idEdit'];
                        if ($contact->update($idEdit, $nameEdit, $birthdateEdit, $emailEdit, $phoneEdit, $imgNameEdit)) {
                            unlink("contact-images/" . $_POST['photoFileEdit']);
                            move_uploaded_file($tmpEdit, "contact-images/$imgNameEdit"); //saving image
                            $globalSuccess = "Success Editing!";
                        } else {
                            $globalErrorEdit = "";
                        }
                    } else {
                        $globalErrorEdit = "";
                    }
                }
                //user submit with empty fields
            } elseif (isset($_POST['submitEdit'])) {
                $globalErrorEdit = "To change this contact image, please select an image.";
            }
        }
        //validations if image to keep
        if ($imageOption == 'keep') {
            $imageKeep = $_POST['photoFileEdit'];
            $contact->validateInfoUpdate($nameEdit, $emailEdit, $phoneEdit, $birthdateEdit);
            if (isset($nameOkayEdit) && isset($emailOkayEdit) && isset($phoneOkayEdit) && isset($birthOkayEdit)) {
                $idEdit = $_POST['idEdit'];
                if ($contact->update($idEdit, $nameEdit, $birthdateEdit, $emailEdit, $phoneEdit, $imageKeep)) {
                    $globalSuccess = "Success Editing!";
                } else {
                    $globalErrorEdit = "";
                }
            } else {
                $globalErrorEdit = "";
            }
        }
    }
    //user submit with empty fields
} elseif (isset($_POST['submitEdit'])) {
    $globalErrorEdit = "All fields are required to edit.";
}


//delete portions
if (isset($_POST['submitDelete'])) {
    $nameDelete = clearInputs($_POST['nameDelete']);
    $emailDelete = clearInputs($_POST['emailDelete']);
    $phoneDelete = clearInputs($_POST['phoneDelete']);
    $idDelete = clearInputs($_POST['idDelete']);
    if (empty($nameDelete) || empty($emailDelete) || empty($phoneDelete) || empty($idDelete)) {
        echo $nameDelete, $emailDelete, $phoneDelete, $idDelete;
        $globalError = "All fields are required to delete.";
    } else {
        if ($contact->delete($idDelete, $nameDelete, $emailDelete, $phoneDelete)) {
            unlink("contact-images/" . $_POST['photoFileDelete']);
            $globalSuccess = "Contact removed!";
        }
    }
}
?>



<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1 class="title">Contact List</h1>
    <fieldset hidden class="border p-2 delete">
        <legend class="float-none w-auto p-2">Delete Contact</legend>
        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col">
                    <div class='container text-center'>
                        <img src='' alt='' style='width: 60px; height: 60px' class="contact-image-file-delete image-delete rounded-circle" />
                        <input hidden type='text' class='photoFile' value='' name='photoFileDelete' id="contact-image-file-delete" readonly>
                    </div>
                </div>
                <div class="col">
                    <label for="nameDelete">Name</label>
                    <input readonly value=" " type="text" name="nameDelete" id="nameDelete" class="form-control">
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col">
                    <label for="phoneDelete">Phone</label>
                    <input readonly type="tel" name="phoneDelete" id="phoneDelete" class="form-control" minlength="10" maxlength="13">
                </div>
                <div class="col">
                    <label for="emailDelete">Email</label>
                    <input readonly type="email" name="emailDelete" id="emailDelete" class="form-control">
                </div>
            </div>
            <input hidden type='number' class='idDelete' value='' name='idDelete' readonly>

            <br>
            <div class="row">
                <div class="col">
                    <div class="submitContainer"> <input name="submitDelete" type="submit" class="btn btn-danger" value='Delete Contact'></div>

                </div>
                <div class="col">
                    <div class="submitContainer"> <input name="cancelDelete" type="submit" class="btn btn-secondary" value='Cancel'>

                    </div>
                </div>
                <br>

            </div>



        </form>
    </fieldset>
    <fieldset hidden class="border p-2 edit">
        <legend class="float-none w-auto p-2">Edit Contact Infos</legend>
        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col">
                    <label for="nameEdit">Edit Full Name</label>
                    <input value="" type="text" name="nameEdit" id="nameEdit" class="form-control" placeholder="Edit Full Name">
                </div>
                <div class="col">
                    <label for="emailEdit">Edit Email address</label>
                    <input value="" type="text" name="emailEdit" id="emailEdit" class="form-control" placeholder="Edit Email">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <label for="birthdateEdit">Edit Birthdate</label>
                    <input type="date" max="<?php //echo $maxBirth; 
                                            ?>" name="birthdateEdit" id="birthdateEdit" class="form-control" placeholder="Edit Birthdate">
                </div>
                <div class="col">
                    <label for="phoneEdit">Edit Phone (10 to 13 digits)</label>
                    <input value="" type="tel" name="phoneEdit" id="phoneEdit" class="form-control" placeholder="Edit Phone (Only numbers)">
                </div>
            </div>
            <br>
            <div class=" form-group text-center">
                <label for="contact-image-edit">Edit Contact image</label> <br>
                <img src='' alt='' style='width: 60px; height: 60px' class="contact-image-file-edit image-edit rounded-circle" />
                <br> <br>
                <input type="file" name="contact-image-edit" class="form-control-file" id="contact-image-edit">
                <input hidden type='text' class='photoFile' value='' name='photoFileEdit' id="contact-image-file-edit" readonly>
                <br><br>
                <input type="radio" class="btn-check" value='keep' name="editImage[]" id="keepImage" autocomplete="off" checked>
                <label class="btn btn-outline-info" for="keepImage">Keep same image</label>

                <input type="radio" class="btn-check" value='change' name="editImage[]" id="changeImage" autocomplete="off">
                <label class="btn btn-outline-info" for="changeImage">Change contact image</label>
            </div>
            <br>
            <input hidden type='number' class='idEdit' value='' name='idEdit' readonly>
            <div class="row">
                <div class="col">
                    <div class="submitContainer">
                        <input name="submitEdit" type="submit" class="btn btn-success" value='Confirm Edit'>
                    </div>
                </div>
                <div class="col">
                    <div class="submitContainer">
                        <input name="cancelEdit" type="submit" class="btn btn-secondary" value='Cancel'>
                    </div>
                </div>
            </div>
        </form>
    </fieldset>
    <fieldset class="border p-2 insert">
        <legend class="float-none w-auto p-2">Add new contact</legend>

        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col">
                    <label for="name">Full Name</label>
                    <input value="<?php
                                    if (!empty($name) && !isset($globalSuccess)) {
                                        echo "$name";
                                    }
                                    ?>" type="text" name="name" id="name" class="form-control" placeholder="Full Name">
                </div>
                <div class="col">
                    <label for="email">Email address</label>

                    <input value="<?php
                                    if (!empty($email) && !isset($globalSuccess)) {
                                        echo "$email";
                                    }
                                    ?>" type="text" name="email" id="email" class="form-control" placeholder="Email">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <label for="date">Birthdate</label>
                    <input type="date" value="<?php
                                                if (!empty($birthdate) && !isset($globalSuccess)) {
                                                    echo "$birthdate";
                                                }
                                                ?>" max="<?php //echo $maxBirth; 
                                                            ?>" name="birthdate" id="date" class="form-control" placeholder="Birthdate">
                </div>
                <div class="col">
                    <label for="phone">Phone (10 to 13 digits)</label>
                    <input value="<?php
                                    if (!empty($phone) && !isset($globalSuccess)) {
                                        echo "$phone";
                                    }
                                    ?>" type="tel" name="phone" id="phone" class="form-control" placeholder="Phone (Only numbers)">
                </div>
            </div>
            <br>
            <div class=" form-group">
                <label for="exampleFormControlFile1">Contact image</label> <br>
                <input type="file" name="contact-image" class="form-control-file" id="exampleFormControlFile1">
            </div>
            <br>

            <div class="submitContainer"> <input name="submit" type="submit" class="btn btn-primary" value='Submit'>
            </div>

        </form>
    </fieldset>
    <br>

    <div class="container" id="message-container">
        <?php
        if (isset($globalError)) {
            echo "<div class='alert alert-warning alert-dismissible fade show text-center' role='alert'>
            <strong>Failed to add new contact. </strong>$globalError
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }
        if (isset($globalErrorEdit)) {
            echo "<div class='alert alert-warning alert-dismissible fade show text-center' role='alert'>
            <strong>Failed to edit this contact. </strong>$globalErrorEdit
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }
        if (isset($imageError)) {
            echo "<div class='alert alert-warning alert-dismissible fade show text-center' role='alert'>
            <strong>An error occurred while uploading this file.</strong> $imageError
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }
        if (isset($contact->error['nameError'])) {
            echo "<div class='alert alert-warning alert-dismissible fade show text-center' role='alert'>
            <strong>This name is not supported.</strong> {$contact->error['nameError']}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }
        if (isset($contact->error['duplicateError'])) {
            echo "<div class='alert alert-warning alert-dismissible fade show text-center' role='alert'>
            <strong>Failed to add contact. </strong> {$contact->error['duplicateError']}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }
        if (isset($contact->error['emailError'])) {
            echo "<div class='alert alert-warning alert-dismissible fade show text-center' role='alert'>
            <strong>There is a problem with this email. </strong> {$contact->error['emailError']}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }
        if (isset($contact->error['phoneError'])) {
            echo "<div class='alert alert-warning alert-dismissible fade show text-center' role='alert'>
            <strong>There is a problem with this phone number. </strong> {$contact->error['phoneError']}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }
        if (isset($contact->error['birthError'])) {
            echo "<div class='alert alert-warning alert-dismissible fade show text-center' role='alert'>
            <strong>Invalid birthdate. </strong> {$contact->error['birthError']}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }

        if (isset($contact->error['updateError'])) {
            echo "<div class='alert alert-warning alert-dismissible fade show text-center' role='alert'>
            <strong>There was a problem editing this contact. </strong> {$contact->error['updateError']}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }
        if (isset($globalSuccess)) {
            echo "<div class='alert alert-success alert-dismissible fade show text-center' role='alert'>
            <strong>$globalSuccess</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }
        ?>

    </div>
    <div class="orderList container">
        <?php if (isset($contact)) {
                if (isset($_POST['orderList']) && isset($_POST['orderDirection'])) {
                   
                    $direction = $_POST['orderDirection'][0];
                    $categoryOrder = $_POST['orderList'];
                    }}
                    ?>
        <form method='post' class='d-flex justify-content-md-center align-items-center'>
            <label for="orderList">Order by&nbsp; &nbsp; </label>
            <select class="form-select form-select-sm" id="select-search" name="orderList" aria-label=".form-select-sm example">
                <option <?php if(isset($categoryOrder)){
                    if($categoryOrder == 'name'){
                        echo "selected";
                    }
                } ?>  value="name">Name</option>
                <option <?php 
                if(isset($categoryOrder)){
                if($categoryOrder == 'birth'){
                    echo "selected";
                }}?> value="birth">Birthdate</option>
                <option <?php 
                if(isset($categoryOrder)){
                if($categoryOrder == 'email'){
                    echo "selected";
                }}?> value="email">Email</option>
                <option <?php
                if(isset($categoryOrder)){ 
                if($categoryOrder == 'phone'){
                    echo "selected";
                }}?> value="phone">Phone</option>
            </select> &nbsp; &nbsp;
            <div class="form-check form-check-inline">
                <input  <?php 
                if(!isset($direction)){
                    echo "checked";
                }
                if(isset($direction)){
                    if($direction == 'ASC'){
                        echo "checked"; 
                    }
                } ?> class="form-check-input" type="radio" name="orderDirection[]" id="ascRadio" value="ASC">
                <label  class="form-check-label" for="ascRadio">Asc</label>
            </div>
            <div class="form-check form-check-inline">
                <input <?php if(isset($direction)){
                    if($direction == 'DESC'){
                        echo "checked"; 
                    }
                } ?> class="form-check-input" type="radio" name="orderDirection[]" id="descRadio" value="DESC">
                <label  class="form-check-label" for="descRadio">Desc</label>
            </div>
            <input type="submit" value="Apply">
        </form>
    </div>
    <div class="search container">
    <?php 
    if (isset($contact)) {
        if (isset($_POST['orderListSearch']) && isset($_POST['orderDirectionSearch'])) {
           
            $directionSearch = $_POST['orderDirectionSearch'][0];
            $categoryOrderSearch = $_POST['orderListSearch'];
            $columnSearch = $_POST['columnSearch'];
            }}
    ?>
    
        <form method="POST" class='d-flex justify-content-md-center align-items-center'>
            <label  for="search">Search contact</label>&nbsp; &nbsp;
            <input <?php 
            if(isset($_POST['search'])){
                echo "value='{$_POST["search"]}'";
            } else{
                echo "triste";
            }
                ?> type="text" name="search" id="search" class="form-control">&nbsp; by &nbsp;
            <select class="form-select form-select-sm" id="search-select-column" name="columnSearch" aria-label=".form-select-sm example">
                <option <?php if(isset($columnSearch)){
                    if($columnSearch == 'name'){
                        echo "selected";
                    }
                } ?> value="name">Name</option>
                <option <?php if(isset($columnSearch)){
                    if($columnSearch == 'birth'){
                        echo "selected";
                    }
                } ?> value="birth">Birthdate</option>
                <option <?php if(isset($columnSearch)){
                    if($columnSearch == 'email'){
                        echo "selected";
                    }
                } ?> value="email">Email</option>
                <option <?php if(isset($columnSearch)){
                    if($columnSearch == 'phone'){
                        echo "selected";
                    }
                } ?> value="phone">Phone</option>
    
            </select> &nbsp;&nbsp;ording by&nbsp; &nbsp;
            <select class="form-select form-select-sm" id="search-select" name="orderListSearch" aria-label=".form-select-sm example">
                <option <?php if(isset($categoryOrderSearch)){
                    if($categoryOrderSearch == 'name'){
                        echo "selected";
                    }
                } ?> value="name">Name</option>
                <option <?php if(isset($categoryOrderSearch)){
                    if($categoryOrderSearch == 'birth'){
                        echo "selected";
                    }
                } ?> value="birth">Birthdate</option>
                <option <?php if(isset($categoryOrderSearch)){
                    if($categoryOrderSearch == 'email'){
                        echo "selected";
                    }
                } ?> value="email">Email</option>
                <option <?php if(isset($categoryOrderSearch)){
                    if($categoryOrderSearch == 'phone'){
                        echo "selected";
                    }
                } ?> value="phone">Phone</option>
    
            </select>&nbsp; &nbsp;
            <div class="form-check form-check-inline">
                <input 
                <?php 
                if(!isset($directionSearch)){
                    echo "checked";
                }
                if(isset($directionSearch)){
                    if($directionSearch == 'ASC'){
                        echo "checked"; 
                    }
                }

                ?>
                 class="form-check-input" type="radio" name="orderDirectionSearch[]" id="ascRadioSearch" value="ASC">
                <label class="form-check-label" for="ascRadioSearch">Asc</label>
            </div>
            <div class="form-check form-check-inline">
                <input 
                <?php if(isset($directionSearch)){
                    if($directionSearch == 'DESC'){
                        echo "checked"; 
                    }
                } ?>
                class="form-check-input" type="radio" name="orderDirectionSearch[]" id="descRadioSearch" value="DESC">
                <label class="form-check-label" for="descRadioSearch">Desc</label>
            </div>
            <input type="submit" value="Search">&nbsp; &nbsp;
        </form>
    </div>

    <table class="table align-middle mb-0 bg-white">
        <thead class="bg-light">
            <tr>
                <th>Name</th>
                <th>Birthdate(Y-M-D)</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
            if (isset($contact)) {
                if (isset($_POST['orderList']) && isset($_POST['orderDirection'])) {
                    $direction = $_POST['orderDirection'][0];
                    $categoryOrder = $_POST['orderList'];
                    $loading = $contact->orderBy($categoryOrder, $direction);
                    if (count($loading) > 0) {
                        foreach ($loading as $ctt) {
                            echo "<tr>
                        <td>
                            <div class='d-flex align-items-center'>
                                <img src='./contact-images/{$ctt['photo']}' alt='' style='width: 45px; height: 45px' class='rounded-circle' />
                                <div class='ms-3'>
                                    <p class='fw-bold mb-1'>{$ctt['name']}</p>
                                </div>
                            </div>
                        </td>
        
                        <td>
                            <p class='fw-normal mb-1'>{$ctt['birth']}</p>
                        </td>
                        <td>
                            <p class='fw-normal mb-1'>{$ctt['email']}</p>
                        </td>
                        <td>{$ctt['phone']}</td>
                        <td>
                            <button onclick='editContact(this)' data-birth='{$ctt['birth']}' data-photo='{$ctt['photo']}' data-id='{$ctt['id']}' data-name='{$ctt['name']}' data-email='{$ctt['email']}' data-phone='{$ctt['phone']}' type='button' class='btn btn-primary btn-rounded btn-sm fw-bold' data-mdb-ripple-color='light'>
                                Edit
                            </button>
                            <button onclick='deleteContact(this)' data-birth='{$ctt['birth']}' data-photo='{$ctt['photo']}' data-id='{$ctt['id']}' data-name='{$ctt['name']}' data-email='{$ctt['email']}' data-phone='{$ctt['phone']}'type='button' class='btn btn-warning btn-rounded btn-sm fw-bold' data-mdb-ripple-color='dark'>
                                Delete
                            </button>
                        </td>
                    </tr>";
                        }
                    }
                } elseif (isset($_POST['orderListSearch']) && isset($_POST['orderDirectionSearch'])) {
                    $directionSearch = $_POST['orderDirectionSearch'][0];
                    $columnSearch = $_POST['columnSearch'];
                    $categoryOrderSearch = $_POST['orderListSearch'];
                    $searchText = clearInputs($_POST['search']);
                    $loading = $contact->search($searchText, $columnSearch, $categoryOrderSearch, $directionSearch);
                    if (count($loading) > 0) {
                        foreach ($loading as $ctt) {
                            echo "<tr>
                        <td>
                            <div class='d-flex align-items-center'>
                                <img src='./contact-images/{$ctt['photo']}' alt='' style='width: 45px; height: 45px' class='rounded-circle' />
                                <div class='ms-3'>
                                    <p class='fw-bold mb-1'>{$ctt['name']}</p>
                                </div>
                            </div>
                        </td>
        
                        <td>
                            <p class='fw-normal mb-1'>{$ctt['birth']}</p>
                        </td>
                        <td>
                            <p class='fw-normal mb-1'>{$ctt['email']}</p>
                        </td>
                        <td>{$ctt['phone']}</td>
                        <td>
                            <button onclick='editContact(this)' data-birth='{$ctt['birth']}' data-photo='{$ctt['photo']}' data-id='{$ctt['id']}' data-name='{$ctt['name']}' data-email='{$ctt['email']}' data-phone='{$ctt['phone']}' type='button' class='btn btn-primary btn-rounded btn-sm fw-bold' data-mdb-ripple-color='light'>
                                Edit
                            </button>
                            <button onclick='deleteContact(this)' data-birth='{$ctt['birth']}' data-photo='{$ctt['photo']}' data-id='{$ctt['id']}' data-name='{$ctt['name']}' data-email='{$ctt['email']}' data-phone='{$ctt['phone']}'type='button' class='btn btn-warning btn-rounded btn-sm fw-bold' data-mdb-ripple-color='dark'>
                                Delete
                            </button>
                        </td>
                    </tr>";
                        }
                    }
                } else{
                    $loading = $contact->orderBy("name", "ASC");
                    if (count($loading) > 0) {
                        foreach ($loading as $ctt) {
                            echo "<tr>
                        <td>
                            <div class='d-flex align-items-center'>
                                <img src='./contact-images/{$ctt['photo']}' alt='' style='width: 45px; height: 45px' class='rounded-circle' />
                                <div class='ms-3'>
                                    <p class='fw-bold mb-1'>{$ctt['name']}</p>
                                </div>
                            </div>
                        </td>
        
                        <td>
                            <p class='fw-normal mb-1'>{$ctt['birth']}</p>
                        </td>
                        <td>
                            <p class='fw-normal mb-1'>{$ctt['email']}</p>
                        </td>
                        <td>{$ctt['phone']}</td>
                        <td>
                            <button onclick='editContact(this)' data-birth='{$ctt['birth']}' data-photo='{$ctt['photo']}' data-id='{$ctt['id']}' data-name='{$ctt['name']}' data-email='{$ctt['email']}' data-phone='{$ctt['phone']}' type='button' class='btn btn-primary btn-rounded btn-sm fw-bold' data-mdb-ripple-color='light'>
                                Edit
                            </button>
                            <button onclick='deleteContact(this)' data-birth='{$ctt['birth']}' data-photo='{$ctt['photo']}' data-id='{$ctt['id']}' data-name='{$ctt['name']}' data-email='{$ctt['email']}' data-phone='{$ctt['phone']}'type='button' class='btn btn-warning btn-rounded btn-sm fw-bold' data-mdb-ripple-color='dark'>
                                Delete
                            </button>
                        </td>
                    </tr>";
                        }
                    }
                }
            }

            ?>


        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="./script/edit-delete.js"></script>
</body>

</html>