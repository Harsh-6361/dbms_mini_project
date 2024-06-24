<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <!-- Include Vanta.js and Three.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.clouds.min.js"></script>
    <style>
        body {
            margin: 0;
         
            background-size: 100%;
            background-attachment: fixed;
            color: #261A15;
            font-family: 'Yantramanav', sans-serif;
            font-size: 100%;
        }
       .content {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        h1 {
            color: white;
            text-align: center;
            font-size: 3em;
        }
        h3 {
            color: rgb(44, 62, 80);
            font-family: verdana;
            font-size: 120%;
        }
        form {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            margin: 20px auto;
        }
        form input[type="text"], form input[type="password"] {
            padding: 10px;
            margin-bottom: 10px;
            width: calc(100% - 20px);
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }
        form input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1em;
        }
        form input[type="submit"]:hover {
            background-color: #45a049;
        }
        a {
            color: black;
            text-decoration: none;
            font-size: 1.2em;
            margin-top: 20px;
        }
        a:hover {
            text-decoration: underline;
        }
        fieldset {
            background-color: white;
            color: black;
            opacity: 0.7;
        }
    </style>
</head>
<body id="vanta-background" class="vanta-background">
    <!-- Vanta.js Clouds Background -->
    <div ></div>

    <!-- Content -->
    <div class="content">
        <h1><u>AIRLINE RESERVATION SYSTEM</u></h1>
        <br><br>
        <legend>
        <fieldset>
        <center>
        <br>
        <h3>Admin Login Form</h3>
        <form action="" method="POST">
            <br /><b>Employee name:</b>&nbsp;<input type="text" name="empname"><br />
            <br /><b>Password:</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input type="password" name="emppass"><br />
            <br /><input type="submit" value="Login" name="submit" /><br />
            <br /><div class="right"><button class="button">
                <a href="index.php" style="color:black">Back</a></button>
        </fieldset>
        </legend>
        </form>
        <?php
            $con=mysqli_connect("localhost","root","","airline",3307);
            //mysql_select_db("Practice")
            //if (isset($_POST['email']))

            if(isset($_POST['submit']))
            {
                $empname = $_POST['empname'];
                $emppass = $_POST['emppass'];

                $sql="SELECT * FROM admin WHERE Name='".$empname."' AND Pswd='".$emppass."'";
                $query=mysqli_query($con,$sql);  
                $numrows=mysqli_num_rows($query);  
                if($numrows!=0)  
                {  
                    while($row=mysqli_fetch_assoc($query))  
                    {  
                        $dbename=$row['Name'];  
                        $dbpassword=$row['Pswd'];  
                    }  
  
                    if($empname == $dbename && $emppass == $dbpassword)  
                    {  
                        session_start();  
                        $_SESSION['sess_ename']=$empname;  
     
                        /* Redirect browser */  
                        header("Location: adminhome.php");  
                    }} 
                else 
                {  
                    echo "Invalid username or password!";  
                }  
            }

        ?>
    </div>

    <!-- Script for Vanta.js Clouds Background -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            VANTA.CLOUDS({
                el: "#vanta-background",
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                minWidth: 200.00,
                skyColor: 0x589eb8,
                cloudColor: 0xc0c0d9,
                cloudShadowColor: 0x38749d,
                sunColor: 0xff6100,
                sunGlareColor: 0xff7200,
                sunlightColor: 0xffffff,
                speed: 0.70
            });
        });
    </script>
</body>
</html>