<?php ?>

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
        <form method="post">
            <div class="row">
                <div class="col">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="col">
                    <label for="email">Email address</label>

                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <label for="date">Birthdate</label>
                    <input type="date" max="" name="date" id="date" class="form-control" placeholder="Birthdate" required>
                </div>
                <div class="col">
                    <label for="phone">Phone</label>
                    <input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone" required>
                </div>
            </div>
            <br>
            <div class="form-group">
                <label for="exampleFormControlFile1">Contact image</label> <br>
                <input type="file" class="form-control-file" id="exampleFormControlFile1">
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
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                        <div class="ms-3">
                            <p class="fw-bold mb-1">John Doe</p>
                        </div>
                    </div>
                </td>

                <td>
                    <p class="fw-normal mb-1">01/01/1000</p>
                </td>
                <td>
                    <p class="fw-normal mb-1">teste@gmail.com</p>
                </td>
                <td>(21)111111111</td>
                <td>
                    <button type="button" class="btn btn-link btn-rounded btn-sm fw-bold" data-mdb-ripple-color="dark">
                        Edit
                    </button>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="https://mdbootstrap.com/img/new/avatars/6.jpg" class="rounded-circle" alt="" style="width: 45px; height: 45px" />
                        <div class="ms-3">
                            <p class="fw-bold mb-1">Kate Hunington</p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="fw-normal mb-1">20/01/1990</p>
                </td>
                <td>
                    <p class="fw-normal mb-1">teste@gmail.com</p>
                </td>
                <td>(21)111111111</td>
                <td>
                    <button type="button" class="btn btn-link btn-rounded btn-sm fw-bold" data-mdb-ripple-color="dark">
                        Edit
                    </button>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="https://mdbootstrap.com/img/new/avatars/7.jpg" class="rounded-circle" alt="" style="width: 45px; height: 45px" />
                        <div class="ms-3">
                            <p class="fw-bold mb-1">Kate Hunington</p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="fw-normal mb-1">01/01/0111</p>
                </td>
                <td>
                    <p class="fw-normal mb-1">teste@gmail.com</p>
                </td>
                <td>(21)111111111</td>
                <td>
                    <button type="button" class="btn btn-link btn-rounded btn-sm fw-bold" data-mdb-ripple-color="dark">
                        Edit
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>