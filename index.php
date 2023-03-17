<?php
require_once('class/config.php');
require_once('autoload.php');
$loadPage = new Load();
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
            $contact = new Contact($name, $birthdate, $email, $phone, $imgName);
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
    <fieldset class="border p-2">
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
            if (isset($loadPage)) {
                $loading = $loadPage->findAll();
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
                            <button type='button' class='btn btn-link btn-rounded btn-sm fw-bold' data-mdb-ripple-color='light'>
                                Edit
                            </button>
                            <button type='button' class='btn btn-link btn-rounded btn-sm fw-bold' data-mdb-ripple-color='dark'>
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
</body>

</html>