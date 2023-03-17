<?php
require_once('class/config.php');
require_once('autoload.php');
$contact = new Contact();
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
        $globalError = "All fields are required";
    } else {
        //validate image and save in folder if ok (ADJUST LATER TO ONLY MOVE IF EVERYTHING IS OK)
        if (checkImage($_FILES['contact-image'])) {
            $format = pathinfo($_FILES['contact-image']['name'], PATHINFO_EXTENSION);
            $imgName = uniqid() . ".$format";
            $tmp = $_FILES['contact-image']['tmp_name'];
            //this will be used later: move_uploaded_file($tmp, "contact-images/$imgName");
        } else {
            $globalError = "There is a problem with this file";
        }
        if ($uploadOk) {
            $contact->setContactInfo($name, $birthdate, $email, $phone, $imgName);
            if ($contact->insert()) {
                move_uploaded_file($tmp, "contact-images/$imgName");
                $globalMessage = "Success!";
            }
        }
        //inset into database if thereis no problem
    }
} else {
    //user submit with empty fields
    if (isset($_POST['submit'])) {
        $globalError = "All fields are required";
    }
}
///EDIT PORTIONS \/
if (
    isset($_POST['nameEdit']) && isset($_POST['emailEdit']) && isset($_POST['birthdateEdit']) && isset($_POST['phoneEdit']) && isset($_POST['submitEdit']) && !($_FILES['contact-image-edit']['error'] == 4 || ($_FILES['contact-image-edit']['size'] == 0 && $_FILES['contact-image-edit']['error'] == 0))
) {
    //anti inject
    $nameEdit = clearInputs($_POST['nameEdit']);
    $birthdateEdit = clearInputs($_POST['birthdateEdit']);
    $emailEdit = clearInputs($_POST['emailEdit']);
    $phoneEdit = clearInputs($_POST['phoneEdit']);

    //check if fields are empty
    if (empty($nameEdit) || empty($emailEdit) || empty($birthdateEdit) || empty($phoneEdit)) {
        $globalError = "All fields are required to edit";
    } else {
        //validate image and save in folder if ok (ADJUST LATER TO ONLY MOVE IF EVERYTHING IS OK)
        //REMEMBER TO ADJUST TO DELETE CURRENT IMAGE!!!
        if (checkImage($_FILES['contact-image-edit'])) {
            $formatEdit = pathinfo($_FILES['contact-image-edit']['name'], PATHINFO_EXTENSION);
            $imgNameEdit = uniqid() . ".$formatEdit";
            $tmpEdit = $_FILES['contact-image-edit']['tmp_name'];
        } else {
            $globalError = "There is a problem with this file";
        }
        if ($uploadOk) {
            $idEdit = $_POST['idEdit'];
            $contact->update($idEdit, $nameEdit, $birthdateEdit, $emailEdit, $phoneEdit, $imgNameEdit);
            if ($contact->update($idEdit, $nameEdit, $birthdateEdit, $emailEdit, $phoneEdit, $imgNameEdit)) {
                unlink("contact-images/" . $_POST['photoFileEdit']);
                move_uploaded_file($tmpEdit, "contact-images/$imgNameEdit");
                $globalMessage = "Success Editing!";
            } else {
                $globalMessage = $contact->error['updateError'];
            }
        }
    }
} else {
    //user submit with empty fields
    if (isset($_POST['submitEdit'])) {
        $globalError = "All fields are required";
    }
}

//delete portions
if (isset($_POST['submitDelete'])) {
    $nameDelete = clearInputs($_POST['nameDelete']);
    $emailDelete = clearInputs($_POST['emailDelete']);
    $phoneDelete = clearInputs($_POST['phoneDelete']);
    $idDelete = clearInputs($_POST['idDelete']);
    if (empty($nameDelete) || empty($emailDelete) || empty($phoneDelete) || empty($idDelete)) {
        echo $nameDelete, $emailDelete, $phoneDelete, $idDelete;
        $globalError = "All fields are required to delete";
    } else {
        if ($contact->delete($idDelete, $nameDelete, $emailDelete, $phoneDelete)) {
            unlink("contact-images/" . $_POST['photoFileDelete']);
            $globalMessage = "Contact deleted.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

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
                    <div class='img-container-delete'>
                        <img src='' alt='' style='width: 60px; height: 60px' class="contact-image-file-delete image-delete" class='rounded-circle' />
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
                    <input readonly type="tel" name="phoneDelete" id="phoneDelete" class="form-control" minlength="10" maxlength="13" pattern="[0-9]{10,13}">
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
                    <input value=" " type="text" name="nameEdit" id="nameEdit" class="form-control" placeholder="Edit Full Name">
                </div>
                <div class="col">
                    <label for="emailEdit">Edit Email address</label>
                    <input type="email" name="emailEdit" id="emailEdit" class="form-control" placeholder="Edit Email">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <label for="birthdateEdit">Edit Birthdate</label>
                    <input type="date" max="" name="birthdateEdit" id="birthdateEdit" class="form-control" placeholder="Edit Birthdate">
                </div>
                <div class="col">
                    <label for="phoneEdit">Edit Phone (10 to 13 digits)</label>
                    <input type="tel" name="phoneEdit" id="phoneEdit" class="form-control" placeholder="Edit Phone (Only numbers)" minlength="10" maxlength="13" pattern="[0-9]{10,13}">
                </div>
            </div>
            <br>
            <div class=" form-group">
                <label for="contact-image-edit">Edit Contact image</label> <br>
                <input type="file" name="contact-image-edit" class="form-control-file" id="contact-image-edit">
                <input hidden type='text' class='photoFile' value='' name='photoFileEdit' id="contact-image-file-edit" readonly>

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
        <p class="global-error"><?php if (isset($globalError)) {
                                    echo "$globalError";
                                }
                                if (isset($globalMessage)) {
                                    echo "$globalMessage";
                                }
                                if (isset($contact->error['contactError'])) {
                                    echo $contact->error['contactError'];
                                }
                                ?></p>
        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Full Name">
                </div>
                <div class="col">
                    <label for="email">Email address</label>

                    <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <label for="date">Birthdate</label>
                    <input type="date" max="" name="birthdate" id="date" class="form-control" placeholder="Birthdate">
                </div>
                <div class="col">
                    <label for="phone">Phone (10 to 13 digits)</label>
                    <input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone (Only numbers)" minlength="10" maxlength="13" pattern="[0-9]{10,13}">
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
    <table class="table align-middle mb-0 bg-white">
        <thead class="bg-light">
            <tr>
                <th>Name</th>
                <th>Birthdate</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($contact)) {
                $loading = $contact->findAll();
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

            ?>


        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="./script/edit-delete.js"></script>
</body>

</html>