<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Student</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include "SideBar.php"; ?>

    <main>
        <br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h4>Create New Student</h4>
                    <form action="Backend/AddStudent.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Roll No</label>
                            <input type="text" class="form-control" id="fname" name="rollno" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="lname" name="fname" required>
                        </div>
                        <div class="mb-3">
                            <label for="Department" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="dept" name="lname" required>
                        </div>
                        <div class="mb-3">
                            <label for="Designation" class="form-label">Class</label>
                            <input type="text" class="form-control" id="desg" name="class" required placeholder="e.g., BS IT, ADP CS, etc.">
                        </div>
                        <div class="mb-3">
                            <label for="Designation" class="form-label">Batch</label>
                            <input type="text" class="form-control" id="desg" name="batch" required placeholder="e.g., Spring 2000 - Fall 2004, etc.">
                        </div>
                        <div class="mb-3">
                            <label for="Designation" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="desg" name="dob" required>
                        </div>
                        <div class="mb-3">
                            <label for="Designation" class="form-label">Email</label>
                            <input type="email" class="form-control" id="MAIL" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="Designation" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="PHONE" name="phone" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Enroll Student</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
